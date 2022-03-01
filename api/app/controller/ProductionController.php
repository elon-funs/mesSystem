<?php

/**
 * @Description		工艺流程模块
 * @Author			LiuS POrderController
 * @Date			2020-11-02 15:07:46
 * @LastEditTime	2022-02-02 15:07:46
 */


namespace api\app\controller;

// use api\app\model\OptionModel;
// use api\app\model\UserPlatformInfoModel;
// use api\app\model\UserModel;
// use api\app\model\AssetModel;
// use api\app\model\roductionModel;
use think\Db;
use think\facade\Cache;

class ProductionController extends RestBaseController
{
    public function __construct(){
		parent::__construct();
		$this->_initUser();
	}
	
	/**
	 * @access         public
	 * @example        获取加工工序列表－分组显示
	 * @param          string  variable        explain
	 * @author         LiuS
	 * @version        2020-11-02 10:55:19
	 * @return         json
	 */
	public function get_pro_by_group(){
		// $id = $this->request->param('id',0);      //id
	    $pro_list = Db::name('Production')->where('status',1)->where('parentid',0)->order('parentid asc,sort asc')->field('pid,pname,parentid, sort,is_yes,is_choice')->select()->toArray();
	    foreach ($pro_list as $key => $value){
	        $tmp        	            = Db::name('Production')->where('parentid',$value['pid'])->where('status',1)->order('sort asc')->field('pid,pname,is_yes,sort,is_choice')->select()->toArray();
	        if(!empty($tmp))        
	            $pro_list[$key]['next_level']    = $tmp;
	        else 
	            $pro_list[$key]['next_level']    = [];
	    }
	    
	    return $this->success('success',$pro_list);
	}


	public function get_pro(){
	    $pro_list = Db::name('Production')->where('status',1)->order('sort asc')->field('pid,pname, sort,is_yes,is_choice')->select()->toArray();
	    return $this->success('success',$pro_list);
	}

	/**
	 * @access         public
	 * @example        添加订单信息
	 * @param          string  variable        explain
	 * @author         LiuS
	 * @version        2020-11-03 10:00:19
	 * @return         json
	 * 下单字段
	 * 订单号
	 * 订单日期
	 * 业务员
	 * 产品名称
	 * 产吕规格
	 * 排产数
	 * 交付数
	 * 客户名称
	 * 交付期
	 */
	public function add_porder(){
		$id = $this->request->post('id',0);      //id
		$user_name = $this->request->post('user_name',0);      //客户名
	    $name = $this->request->post('name','');      //产品名称
		$number = $this->request->post('number',0); //排产数量 number 
		$com_number = $this->request->post('com_number',0); //交付数量 com_number 
		$com_time = $this->request->post('com_time',0);      //交货日期
		$spec = $this->request->post('spec');      //商品规格
		$order_no = $this->request->post('order_no');
		$seller = $this->request->post('seller');      //业务员名
		$proids = $this->request->post('proids',[]);      //工序id列表
		$remark = $this->request->post('remark','');  //备注

		if(empty($this->userId)) return $this->error(['code' => 201, 'msg' => '请重新登录!']);
		if(empty($name) || empty($user_name) || empty($number) || empty($com_number) || empty($com_time) || empty($spec) || empty($seller) || empty($order_no) )
		   	return $this->error(['code' => 202, 'msg' => '请填写完整!']);

		// $pocart = Db::name('Production')->where('pid','in',$proids)->where('status',1)->field('pid as proid,pname as remark,sort as `order`')->order('sort asc')->select()->toArray();
		// var_dump($pocart);
		// exit();
		$data = [
		    'uid'                   => $this->userId,
		    'user_name'             => $user_name,
		    'name'                  => $name, 
		    'number'                => $number, 
		    'spec'            		=> $spec, 
		    'com_time'              => strtotime($com_time.' 23:59:59'),
		    'com_number'            => $com_number,
		    'seller'             	=> $seller,
		    'order_no'             	=> $order_no,
		    'remark'                => $remark, 
		];

        if(empty($id) or $id<1){
            $data['add_time']       = time();
            // $data['status']       	= empty($this->role) ? 0 : 1;
            $data['status']       	= 0;
            $data['order_no']       = $order_no;
            $poid = Db::name('porder')->insertGetId($data);
            
            if($poid>=1){
				$pocart = Db::name('Production')->whereIn('pid',$proids)->where('status',1)->field('pid as proid,pname as remark,sort as `order`')->order('sort asc')->select()->toArray();
		        foreach ($pocart as $k=>$v){
		        	$pocart[$k]['poid']=$poid;
		        	$pocart[$k]['add_time']=$data['add_time'];
		        	$pocart[$k]['op_time']=time();
		        	$pocart[$k]['option_id']=$data['uid'];
		        }
        	    Db::name('pocart')->data($pocart)->insertAll();
        	    $data['insert'] = $pocart;
                return $this->success('success',$data);
            }
        }else{
        	// ！！修改会牵扯到已生产和订单数据工序数据等信息！！
            $res = Db::name('porder')->where('oid',$id)->update($data);
            $data['id']             = $id;
            if($res) {
            	return $this->success('success',$data);
        	}
        }

        return $this->error('提交失败，请检查网络或稍后再试!');

	}


	public function order_finish()
	{        
		$id = $this->request->param('id',0);
	    if(empty($id)) $this->error("参数错误！");
		
    	$uporder=Db::name('Porder')->where('oid',$id)->where('status = 2')->update(['status'=>3]);
    	if ($uporder!=1) $this->error("订单有误或已完成,无法修改");
        return $this->success('success',[]);


	    /*订单完成时，可能不改工序的状态。代码先留着不用。还有权限功能没加
		Db::startTrans();
		try {
	    	$uporder=Db::name('Porder')->where('oid',$id)->where('status = 2')->update(['status'=>3]);
	    	if ($uporder<1) {
			    Db::rollback();
				return $this->error("订单有误或已完成,无法修改");
	    	}
	    	Db::name('Pocart')->where('poid',$id)->where('cstatus','<>',1)->update(['status'=>1]);

		    Db::commit();
		} catch (\Exception $e) {
		    Db::rollback();
		}*/
	}



	// ---------------------------------------------------------
	/**
	 * @access         public
	 * @example        添加材料加工工艺信息
	 * @param          string  variable        explain
	 * @author         LiuS
	 * @version        2020-11-03 10:02:19
	 * @return         json
	 */
	public function add_pcart(){
	    if(empty($this->userId)) return $this->error(['code' => 202, 'msg' => '请重新登录!']);
	    $data                       = $this->request->post('data/a',[]);      //材料id
// 	    $poid       				= $this->request->post('oid',0);      //材料id
		$proid       			    = $this->request->post('production_id/a',[]);   //工艺流程ID
// 		$remark            			= $this->request->post('remark','');  //工艺流程备注 remark $this->userId
        $insert                     = [];
        if(empty($data))            return $this->error(['code' => 202, 'msg' => '请确认参数无误!']);   
        // $data                       = json_decode($data,true);
        foreach ($data as $value){
            $tmp                    = [];
            foreach ($value['proid'] as $key => $val){
                $tmp[]              = [
                    'poid'          => $value['oid'],
                    'proid'         => $key,
                    'add_time'      => time(),
                    'remark'        => $val,
                ];
            }
            $insert                 = $tmp;
        }
	    if(empty($data) || empty($insert)) return $this->error(['code' => 202, 'msg' => '请确认参数无误!']);

        $res             = Db::name('pocart')->data($insert)->insertAll();
        if($res){
            return $this->success('success',$insert);
        }else{
            return $this->error('提交失败，请检查网络或稍后再试!');
        }
       
	}
	
	/**
	 * @access         public
	 * @example        修改材料加工工艺信息-分批按钮
	 * @param          string  variable        explain
	 * @author         LiuS
	 * @version        2020-11-07 10:02:19
	 * @return         json
	 */
	public function buch_pcart(){
	    if(empty($this->userId)) return $this->error(['code' => 202, 'msg' => '请重新登录!']);
	    $proid       				= $this->request->post('proid',0);      //加工工艺id
	    $poid       				= $this->request->post('poid',0);      //订单id
	    if(empty($proid) || empty($poid))            return $this->error(['code' => 202, 'msg' => $proid.'请确认参数无误!']); 
	    $insert                     = ['poid'=>$poid,'proid'=>$proid,'add_time'=>time()];
	    $res             = Db::name('pocart')->insert($insert);
	    if($res){
            return $this->success('success',$insert);
        }else{
            return $this->error('操作失败，请检查网络或稍后再试!');
        }
	}
	/**
	 * @access         public
	 * @example        修改材料加工工艺信息----旧的
	 * @param          string  variable        explain
	 * @author         LiuS
	 * @version        2020-11-03 10:02:19
	 * @return         json
	 */
	public function edit_pcart(){
	    if(empty($this->userId)) return $this->error(['code' => 202, 'msg' => '请重新登录!']);
	    $data                       = $this->request->post('data/a',[]);      //材料id
	    $poid       				= $this->request->post('oid',0);      //材料id
// 		$proid       			    = $this->request->post('production_id/a',[]);   //工艺流程ID
// 		$remark            			= $this->request->post('remark','');  //工艺流程备注 remark $this->userId
        $insert                     = [];
        if(empty($data) || empty($poid))            return $this->error(['code' => 202, 'msg' => '请确认参数无误!']);   
        
        foreach ($data as $value){
            $tmp                    = [];
            foreach ($value['proid'] as  $val){
                $tmp[]              = [
                    'poid'          => $value['oid'],
                    'op_time'       => time(),
                    'proid'         => $val,
                ];
            }
            $insert                 = $tmp;
        }
	    if(empty($data) || empty($insert)) return $this->error(['code' => 202, 'msg' => '请确认参数无误!']);
        if(Db::name('pocart')->where('poid',$poid)->delete())
        $res             = Db::name('pocart')->data($insert)->insert();
        if($res){
            return $this->success('success',$insert);
        }else{
            return $this->error('提交失败，请检查网络或稍后再试!');
        }
	}
	
	public function workload_table(){
	    if(empty($this->role))     return $this->error(['code' => 202, 'msg' => '权限不足!']);    
	}

	public function check_order_no(){
		$order_no=$this->request->param('order_no');
	 	if (empty($order_no)) $this->error('请确认参数');

        $exist = Db::name('porder')->where('order_no',$order_no)->findOrEmpty();
        
	 	if (empty($exist)) $this->success('success',$order_no);       
	 	$this->error('订单号已占用请更换');
	}

}