<?php

/**
 * @Description		生产进度
 * @Author			LiuS
 * @Date			2021-10-02 15:14:52
 * @LastEditTime	2021-11-02 19:2
 */

namespace app\admin\controller;

use cmf\controller\AdminBaseController;
use think\Db;
use api\app\model\PorderModel;


class MarkController extends AdminBaseController
{
    // --------------------------------------------------------------------
    /**
     * @access		public
     * @example		生产工程列表页
     * @param		string 	variable		explain
     * @author		LiuS
     * @version		2021-11-01 17:37:25
     * @return		json
     */
    public function index(){
        $search             				    = $this->request->param('search',0);      //搜索
        $str_time             				    = $this->request->param('str_time','');      //开始时间
        $end_time             				    = $this->request->param('end_time','');      //结束时间
        if($end_time)           $end_time       = strtotime($end_time);
        if($str_time)           $str_time       = strtotime($str_time);
        // $page                           	= $this->request->param('page',1);		//页码
        // $limit                           	= $this->request->param('limit',20);		//每页条数
        $Porder                                 = Db::name('porder')->alias('o');//->where('o.status','<',4)->paginate(20);
        
        if($search){
            $Porder->where('o.user_name|o.name|o.material','like',$search);
        }
        if($str_time){
            $Porder->where('o.add_time >= '.$str_time);
        }
        if($end_time){
            $Porder->where('o.add_time <'.$end_time);
        }
        $porder = $Porder->where('status < 4')->order('osort desc,oid desc')->paginate(10);
        // 获取分页显示
        $page = $porder->render();
        $this->assign("order_list", $porder);
        // var_dump($page);
        $this->assign("page", $page);
        // $this->assign("limit", $limit);
        return $this->fetch();
    }
     // --------------------------------------------------------------------
    /**
     * @access		public
     * @example		生产工程详情页 及 修改页
     * @param		string 	variable		explain
     * @author		LiuS
     * @version		2021-11-01 17:37:25
     * @return		json
     */
    public function detail(){
        $id                                	= $this->request->param('id',0);		//生产排序
        
        if(empty($id))          $this->error("参数错误！");
        $order_list                         = Db::name('pocart')->alias('a')->join('Production b','a.proid = b.pid','left')->join('Porder c','a.poid = c.oid','left')->where('a.poid',$id)->where('cstatus <4')->order('b.sort desc,a.pcid asc')->field('a.*,b.pname')->select();
        
        $data                              = Db::name('Porder')->where('oid',$id)->where('status < 4')->find();
        $this->assign("data", $data);
        $this->assign("order_list", $order_list);
        return $this->fetch();
    }
    
    // --------------------------------------------------------------------
    /**
     * @access		public
     * @example		生产工程添加
     * @param		string 	variable		explain
     * @author		LiuS
     * @version		2021-11-02 17:37:25
     * @return		json
     */
    public function add(){
        return $this->fetch();
    }
    /**
	 * @access         public
	 * @example        获取加工工艺
	 * @param          string  variable        explain
	 * @author         LiuS
	 * @version        2021-11-02 10:55:19
	 * @return         json
	 */
	public function get_pro(){
	    $pro_list                       = Db::name('Production')->where('status',1)->where('parentid',0)->order('sort desc')->field('pid,pname,is_yes,is_choice')->select()->toArray();
	    
	    foreach ($pro_list as $key => $value){
	        $tmp        	            = Db::name('Production')->where('parentid',$value['pid'])->where('status',1)->order('sort desc')->field('pid,pname,is_yes,is_choice')->select()->toArray();
	        if(!empty($tmp))        
	            $pro_list[$key]['next_level']    = $tmp;
	        else 
	            $pro_list[$key]['next_level']    = [];
	       //$pro_list[$key]['next_levelsql']    =  Db::name('Production')->getLastSql();
	    }
	    return ['data'=> $pro_list,'code'=> 1,'msg' => '操作成功'];
	   // return $this->success('success',$pro_list);
	}
    // --------------------------------------------------------------------
    /**
     * @access		public
     * @example		生产工程 加工工艺修改
     * @param		string 	variable		explain
     * @author		LiuS
     * @version		2021-11-02 17:37:25
     * @return		json
     */
    public function edit(){
        $id                                	= $this->request->param('id',0);		//生产排序
        
        if(empty($id))          $this->error("参数错误！");
        $order_list                         = Db::name('pocart')->alias('a')->join('Production b','a.proid = b.pid','left')->join('Porder c','a.poid = c.oid','left')->where('a.poid',$id)->where('cstatus <4')->order('b.sort desc,a.pcid asc')->field('a.*,b.pname,b.sort')->select();
        
        $data                              = Db::name('Porder')->where('oid',$id)->where('status < 4')->find();
        $this->assign("data", $data);
        $this->assign("order_list", $order_list);
        return $this->fetch();
    }
    // --------------------------------------------------------------------
    /**
     * @access		public
     * @example		生产工程添加与修改
     * @param		string 	variable		explain
     * @author		LiuS
     * @version		2021-11-02 17:37:25
     * @return		json
     */
    public function save(){
		$id                         = $this->request->post('id',0);      //id
		$uid                        = $this->request->post('uid',0);      //客户id
		$user_name                  = $this->request->post('user_name',0);      //客户名称
        $name       				= $this->request->post('name','');      //产品名称
		$material       			= $this->request->post('material','');      //材质 
		$number            			= $this->request->post('number',0); //来料数量 number 
		$length            			= $this->request->post('length',0); //长度length 
		$width            			= $this->request->post('width',0); //宽度 width 
		$inch_length            	= $this->request->post('inch_length',0); //坑纸长度length 
		$inch_width            		= $this->request->post('inch_width',0); //坑纸宽度 width 
		$com_number       			= $this->request->post('com_number',0); //交付数量 com_number 
		$remark            			= $this->request->post('remark','');  //备注 remark $this->userId
		$user_name                  = $this->request->post('user_name',0);      //id
		$com_time                   = $this->request->post('com_time',0);      //交货日期
		$pro_data                   = $this->request->post('pro_data/a',[]);     ///加工工艺 流程
		$is_return                  = $this->request->post('is_return',0);      //是否回厂 1是 0否
		$return                     = $this->request->post('return',0);      //回厂时间
		$factory                    = $this->request->post('factory','');      //印刷工厂
		$image                      = $this->request->post('image','');      //来料图片 image
		$status                     = $this->request->post('status',1);     //是否确认 0-1 后台添加订单自动确认
		if(empty($name) || empty($material) || empty($number) || empty($length) || empty($width)){
		    $this->error("请填写完整！");  // 	return $this->error(['code' => 202, 'msg' => '请填写完整!']);
		}
		if(empty($uid)) $this->error("请选择用户！"); 
		if(!empty($com_time)) $com_time = strtotime($com_time.' 00:00:00');
		if(!empty($return)) $return = strtotime($return.' 00:00:00');
		$data                       = [
		    'uid'                   => $uid, 
		    'name'                  => $name, 
		    'user_name'            => $user_name,
		    'material'              => $material, 
		    'number'                => $number, 
		    'length'                => $length, 
		    'width'                 => $width, 
		    'inch_length'           => $inch_length, 
		    'inch_width'            => $inch_width, 
		    'remark'                => $remark, 
		    'user_name'             => $user_name,
		    'com_time'              => $com_time ,
		    'com_number'            => $com_number,
		    'is_return'             => $is_return,
		    'factory'               => $factory,
		    'image'                 => $image,
		    'status'                => $status,
		    ];
        if(!empty($is_return))  $data['return_time'] = $return;
        if(empty($id)){
            $data['add_time']       = time();
            $no                     = Db::name('porder')->max('oid');
            $no                     = $no + 10000;
            $data['order_no']       = date('Ymd').$no;//'gdno'.time().mt_rand(1000,9999);
            $res                    = Db::name('porder')->insert($data);
            if($res){                    // Db::commit();
                $data['id']         = Db::name('porder')->getLastInsID();
                $insert             = [];
                if(!empty($pro_data)){
                    foreach ($pro_data as $v){
                       $insert[]    = ['poid'=>$data['id'],'proid'=>$v]; 
                    }
                }
                $res                = Db::name('pocart')->data($insert)->insertAll();
                $this->success('操作成功');
            }
        }else{
            $data['edit_time']      = time();
            $res                    = Db::name('porder')->where('oid',$id)->update($data);
            
            if($res){
                $insert             = [];
                if(!empty($pro_data)){
                    foreach ($pro_data as $v){
                       $insert[]    = ['poid'=>$id,'proid'=>$v]; 
                    }
                }
                $res                = Db::name('pocart')->data($insert)->insertAll();
                $this->success('操作成功');
            }
        }
        $this->error('操作失败，请稍后再试!');
    }
    
    // --------------------------------------------------------------------
    /**
     * @access		public
     * @example		生产工程添加与修改
     * @param		string 	variable		explain
     * @author		LiuS
     * @version		2022-12-22 17:37:25
     * @return		json
     */
    public function save_pcart(){
        $pcid                           = $this->request->post('pcid',0);//处理ID
        $proid                          = $this->request->post('proid',0);//处理工艺ID
	    $poid                           = $this->request->post('poid',0);//处理材料订单ID
	    $completioner                   = $this->request->post('completioner','');//操作人名称ID
	    $option_id                      = $this->request->post('option_id','');//操作人ID
	    $com_num                        = $this->request->post('com',0);//完成数量
	    $sum_num                        = $this->request->post('sum',0);//材料总数
	    $cstatus                        = $this->request->post('cstatus',0);//完成与否 1 完成 0未完
	    $remark                         = $this->request->post('remark','');//备注 
	    $com_pocart                     = $this->request->post('com_pocart',0);//订单完成度 com_pocart 100为单位
        $data                       = [
            'sum_num'               => $sum_num,
            'com_num'               => $com_num,
            'sum_num'               => $sum_num,
            'cstatus'               => $cstatus, 
            'remark'                => $remark,
            'option_id'             => $option_id,
            'completioner'          => $completioner,
        ];  
        if(!empty($com_pocart)){ 
            $status = 2;
            if($com_pocart == 100 ) $status = 3;
            Db::name('porder')->where('oid',$poid)->update(['com_pocart'=>$com_pocart,'status'=>$status]);
        }
        if(empty($pcid)){
            $data['add_time']           = time();
            $data['proid']              = $proid;
            $data['poid']               = $poid;
            if(!empty($cstatus))        $data['completion_time'] = time();
            $res                        = Db::name('pocart')->insert($data);
        }else{
            if(!empty($cstatus))        $data['completion_time'] = time();
            $res                        = Db::name('pocart')->where('pcid',$pcid)->where('poid',$poid)->update($data);
        }
        if($res)
            return $this->success('操作成功');
        
        $this->error('操作失败');
    }
    
    // --------------------------------------------------------------------
    /**
     * @access		public
     * @example		生产工程订单删除
     * @param		string 	variable		explain
     * @author		LiuS
     * @version		2021-12-02 17:37:25
     * @return		json
     */
    public function delete(){
        $id        				    = $this->request->param('id',0);      //名称
        if(empty($id))              $this->error("参数错误！");
        if (Db::name('porder')->where('oid', $id)->data(['status' => 4,'edit_time'=> time()])->update() !== false) {
            $this->success("删除成功！");
        } else {
            $this->error("删除失败！");
        }
    }
    
    // --------------------------------------------------------------------
    /**
     * @access		public
     * @example		生产工程删除 
     * @param		string 	variable		explain
     * @author		LiuS
     * @version		2021-11-09 17:37:25
     * @return		json
     */
    public function cart_delete(){
        $id        				    = $this->request->param('id',0);      //名称
        $oid        				= $this->request->param('oid',0);      //名称
        if(empty($id))              $this->error("参数错误！");
        if (Db::name('pocart')->where('pcid', $id)->where('poid',$oid)->data(['cstatus' => 4])->update() !== false) {
            $this->success("删除成功！");
        } else {
            $this->error("删除失败！");
        }
    }
    
    /**
	 * @access         public
	 * @example        修改材料加工工艺信息-分批按钮
	 * @param          string  variable        explain
	 * @author         LiuS
	 * @version        2021-11-07 10:02:19
	 * @return         json
	 */
	public function buch_pcart(){
	    if(empty($this->userId)) return $this->error(['code' => 202, 'msg' => '请重新登录!']);
	    $proid       				= $this->request->post('proid',0);      //加工工艺id
	    $poid       				= $this->request->post('poid',0);      //订单id
	    $remark       				= $this->request->post('remark',0);      //订单id
	    if(empty($proid) || empty($poid))            return $this->error(['code' => 202, 'msg' => $proid.'请确认参数无误!']); 
	    $insert                     = ['poid'=>$poid,'proid'=>$proid,'remark'=>$remark];
	    $res             = Db::name('pocart')->insert($insert);
	    if($res){
            $this->success('success');
        }else{
            $this->error('操作失败');
        }
	}
    
    // --------------------------------------------------------------------
    /**
     * @access		public
     * @example		生产工程订单确认  用户直接提交的订单 需要系统后台确认
     * @param		string 	variable		explain
     * @author		LiuS
     * @version		2021-11-09 17:37:25
     * @return		json
     */
    public function confirm(){
        $id        				    = $this->request->param('id',0);      //名称
        $status                     = $this->request->param('status',1);      //订单是否确认 1确认 0不确认
        if(empty($id))              $this->error("参数错误！");
        
        $order=PorderModel::where('oid',$id)->findOrEmpty();
        if (empty($order)) {
            $this->error("确认失败！");
        } else {
            $order->status=1;
            $order->edit_time=time();
            $order->save();
            $updata=[
                'oid'=>$id,
                'pid'=>$order->pid,
                'sum_num'=>$order->number,
                'sub_pcid'=>0,
                'sub_proid'=>0,
                'sub_time'=>time(),
                'sub_id'=>$this->user['id'],
                'sub_name'=>$this->user['user_nickname'],
                'sub_remark'=>'初始排产',
            ];
            DB::name('trans_log')->insert($updata);
            $this->success("确认成功！");
        }
    }
    
    // --------------------------------------------------------------------
    /**
     * @access		public
     * @example		生产工程进度报表
     * @param		string 	variable		explain
     * @author		LiuS
     * @version		2021-11-02 17:37:25
     * @return		json
     */
    public function production_table(){
        $search             				    = $this->request->param('search',0);      //搜索
        $langth             				    = $this->request->param('langth',20);      //每页条数
        $pro_data = Db::name('production')->where('status < 4')->where('parentid',0)->order('sort DESC')->field('pid,pname')->select()->toArray();
        
        foreach ($pro_data as $key => $value){
	        $tmp        	                    = Db::name('Production')->where('status',1)->where('parentid',$value['pid'])->order('sort desc')->field('pid,pname')->select()->toArray();
	        if(!empty($tmp))        
	            $pro_data[$key]['next_level']   = $tmp;
	        else 
	            $pro_data[$key]['next_level']   = [];
	    }
	    //加工工艺
        $this->assign("pro_data", $pro_data);
        
        $Porder                                 = Db::name('porder')->alias('o');//->where('o.status','<',4)->paginate(20);
        
        if($search){
            $Porder->where('o.user_name|o.name|o.material','like',$search);
        }
        
        $porder_list                            = $Porder->where('o.status < 4')->order('oid desc')->paginate($langth);//Db::name('porder')->alias('o')->where('')
        $page = $porder_list->render();
        $porder_list                            = $porder_list->toArray();
        $por_list                               = $porder_list['data'];
        
        foreach ($por_list as $key=>$val){
            $por_list[$key]['Production']          = Db::name('pocart')->where('poid',$val['oid'])->where('cstatus < 4')->order('order desc')->column('proid,pcid,com_num,sum_num,cstatus','proid');
        }
        // var_dump($porder_list->toArray());
        // var_dump($por_list);exit;
        $this->assign('porder_list', $por_list);
        $this->assign('page', $page);
        
        return $this->fetch();
    }
    
    // --------------------------------------------------------------------
    /**
     * @access		public
     * @example		生产日报报表
     * @param		string 	variable		explain
     * @author		LiuS
     * @version		2021-12-06 17:37:25
     * @return		json
     */
    public function user_job_table(){
        $search             				    = $this->request->param('search',0);      //搜索
        $langth             				    = $this->request->param('langth',20);      //每页条数
        $str_time             				    = $this->request->param('str_time','');      //开始时间
        $end_time             				    = $this->request->param('end_time','');      //结束时间
        if($end_time)           $end_time       = strtotime($end_time);
        // else                    $end_time       = time();
        if($str_time)           $str_time       = strtotime($str_time);
        // else                    $str_time       = time() - 2592000;
        $Porder                                 = Db::name('porder')->alias('o');//->where('o.status','<',4)->paginate(20);
        
        if($search){
            $Porder->where('o.user_name|o.name|o.material','like',$search);
        }
        if($str_time){
            $Porder->where('o.add_time >= '.$str_time);
            // $this->assign('str_time', $str_time);
        }
        if($end_time){
            $Porder->where('o.add_time <'.$end_time);
        }
        
        $porder_list                            = $Porder->where('o.status < 4')->order('o.osort desc,o.oid desc')->paginate($langth);//Db::name('porder')->alias('o')->where('')
        $page                                   = $porder_list->render();
        $this->assign('page', $page);
        $porder_list                            = $porder_list->toArray();
        $por_list                               = $porder_list['data'];
        foreach ($por_list as $key=>$val){
            $por_list[$key]['production']       = Db::name('pocart')->alias('c')->join('Production p','c.proid=p.pid','left')->join('user u','c.option_id = u.id','left')->where('c.poid',$val['oid'])->where('c.cstatus < 4')->order('p.sort desc,pcid')->field('c.*,p.pname,u.user_login,u.user_nickname')->select()->toArray();
        }
        // var_dump($por_list);exit;
        $this->assign('str_time', $str_time??0);
        $this->assign('end_time', $end_time??0);
        $this->assign('porder_list', $por_list);
        return $this->fetch();
    }
    /**
     * @access		public
     * @example		产能报表
     * @param		string 	variable		explain
     * @author		LiuS
     * @version		2021-12-12 17:37:25
     * @return		json
     */
    public function capacity_table(){
        $list                                       = [];
        for($i=0;$i<6;$i++){
            if($i>0){
                $end_time                           = $str_time ?? strtotime(date('Y-m-1 00:00:00'));
                $str_time                           = strtotime('-1 month ',$str_time);
            }else{
                $str_time                                   = strtotime(date('Y-m-1 00:00:00'));//                                      =
                $end_time                                   = time();  
            }
            
            $tmp['date']                                = date('Y/m',$str_time);
            //总订单数
            $tmp['total_order']                         = Db::name('porder')->alias('o')->where('o.status < 4')->where('o.add_time >= '.$str_time)->where('o.add_time <'.$end_time)->count(); 
            //订单件数
            $tmp['total_number']                        = Db::name('porder')->alias('o')->where('o.status < 4')->where('o.add_time >= '.$str_time)->where('o.add_time <'.$end_time)->sum('o.number');
            //订单交货件数
            $tmp['total_comnumber']                     = Db::name('porder')->alias('o')->where('o.status < 4')->where('o.add_time >= '.$str_time)->where('o.add_time <'.$end_time)->sum('o.com_number');
            //完成件数
            $tmp['total_com']                           = Db::name('porder')->alias('o')->join('pocart c','c.poid=o.oid','left')->where('o.status < 4')->where('o.add_time >= '.$str_time)->where('o.add_time <'.$end_time)->sum('c.com_num');
            $list[]         = $tmp; 
        }
        $this->assign('str_time', $str_time??0);
        $this->assign('end_time', $end_time??0);
        $this->assign('list', $list);
        // var_dump($list);
        return $this->fetch();
    }
    // --------------------------------------------------------------------
    /**
     * @access		public
     * @example		工作量报表
     * @param		string 	variable		explain
     * @author		LiuS
     * @version		2022-01-02 17:37:25
     * @return		json
     */
    public function workload_table(){
        $str_time             				    = $this->request->param('str_time','');      //开始时间
        $end_time             				    = $this->request->param('end_time','');      //结束时间
        if($end_time)           $end_time       = strtotime($end_time);
        else                    $end_time       = time();
        if($str_time)           $str_time       = strtotime($str_time);
        else                    $str_time       = strtotime(date('Y-m-1 00:00:00'));//strtotime('-1 month ',$str_time);
        $list                                   = Db::name('user')->where('role > 0')->where('user_type',2)->where('user_status',1)->field('id,user_login,user_nickname,signature,role_no')->select()->toArray();
        foreach ($list as $key=>$value){
            // var_dump($value);
            $pro_data                           = Db::name('pocart')->alias('c')->where('c.option_id',$value['id'])->where('c.cstatus < 4')->join('Production p','c.proid=p.pid')->GROUP('c.proid')->field('c.proid,p.pname')->select()->toArray();
            // var_dump(Db::name('pocart')->getLastSql());
            $list[$key]['production_data']           = [];
            foreach ($pro_data as $val){
                $tmp                            = [];
                $tmp['proid']                   = $val['proid'];
                $tmp['pname']                   = $val['pname'];
                //承接总量
                $tmp['total_number']            = Db::name('porder')->alias('o')->join('pocart c','c.poid=o.oid','left')->where('c.option_id',$value['id'])->where('c.proid',$val['proid'])->sum('c.sum_num');//->where('o.status < 4')->where('o.add_time >= '.$str_time)->where('o.add_time <'.$end_time)
                $tmp['total_number']            = $tmp['total_number'] ??0 ;
                //完成量
                $tmp['total_complete']          = Db::name('porder')->alias('o')->join('pocart c','c.poid=o.oid','left')->where('c.option_id',$value['id'])->where('c.proid',$val['proid'])->sum('c.com_num');
                $tmp['total_complete']          = $tmp['total_complete']??0;
                $tmp['total_unaccomp']          = $tmp['total_number'] - $tmp['total_complete'];
                $list[$key]['production_data'][]     = $tmp; 
            }
            
        }
        $this->assign('date', [$str_time,$end_time]);
        
        $this->assign('list', $list);
        // var_dump($list);
        return $this->fetch();
    }
     // --------------------------------------------------------------------
    /**
     * @access		public
     * @example		生产订单置顶
     * @param		string 	variable		explain
     * @author		LiuS
     * @version		2022-01-12 17:37:25
     * @return		json
     */
    public function topping(){
        $oid            			= $this->request->param('oid',0); //订单id
        $type                       = $this->request->param('type',1); //置顶 类型 订单置顶 1 工艺置顶2
        $is_yes                     = $this->request->param('is_yes',1); //操作 置顶 类型 1置顶 2 取消置顶
        if(empty($oid)) $this->error("参数错误！");
        if($type){
            if($is_yes){
                $max =  Db::name('porder')->max('osort');
                $max ++;
                $res = Db::name('porder')->where('oid',$oid)->update(['osort'=>$max,'edit_time'=>time()]);
            }else{
                $res = Db::name('porder')->where('oid',$oid)->update(['osort'=>0,'edit_time'=>time()]);
            }
        }else{
            if($is_yes){
                
            }else{
                
            }
        }
        if($res)
            $this->success("置顶成功！");
        else
            $this->error("置顶失败！");
        
    }
    
    //         $name       				= $this->request->post('name','');      //名称
// 		$material       			= $this->request->post('material','');      //材质 
// 		$number            			= $this->request->post('number',0); //数量 number 
// 		$length            			= $this->request->post('length',0); //长度length 
// 		$width            			= $this->request->post('width',0); //宽度 width 
// 		$remark            			= $this->request->post('remark','');  //备注 remark $this->userId
// 		$id                         = $this->request->post('id',0);      //id
// 		if(empty($name) || empty($material) || empty($number) || empty($length) || empty($width)){
// 		   	return $this->error(['code' => 202, 'msg' => '请填写完整!']);
// 		}
// 		if(empty($this->userId)) return $this->error(['code' => 202, 'msg' => '请重新登录!']);
// 		$data                       = [
// 		    'uid'                   => $this->userId??0, 
// 		    'name'                  => $name, 
// 		    'material'              => $material, 
// 		    'number'                => $number, 
// 		    'length'                => $length, 
// 		    'width'                 => $width, 
// 		    'remark'                => $remark, 
// 		    ];

//         if(empty($id)){
//             $data['add_time']       = time();
//             $res             = Db::name('porder')->insert($data);
//             if($res){                    // Db::commit();
//                 $data['id']         = Db::name('porder')->getLastInsID();
//                 return $this->success('success',$data);
//             }
//         }else{
//             $data['edit_time']      = time();
//             $res                    = Db::name('porder')->where('oid',$id)->update($data);
//             $data['id']             = $id;
//             if($res){
//                 return $this->success('success',$data);
//             }
//         }

        
}