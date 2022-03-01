<?php

/**
 * @Description		工艺流程模块
 * @Author			LiuS POrderController
 * @Date			2020-11-02 15:07:46
 * @LastEditTime	2022-02-02 15:07:46
 */


namespace api\app\controller;

// use api\app\model\UserPlatformInfoModel;
// use api\app\model\UserModel;
// use api\app\model\AssetModel;
// use api\app\model\roductionModel;
use think\Db;
use think\facade\Cache;
use api\app\model\PocartModel;
use api\app\model\TransLogModel;

class PocartController extends RestBaseController
{
    public function __construct(){
		parent::__construct();
		$this->_initUser();
	}
	
	/**
     * @access		public
     * @example		获取订单信息列表
     * @param		string 	variable		explain
     * @author		LiuS
     * @version		2021-11-01 17:37:25
     * @return		json
     */
    public function order_list(){
        $id = $this->request->param('id',0);
        $status = $this->request->param('status',3);
        $where=[['status','<',$status]];

        if(!empty($id) OR $id>=1) array_push($where, ['oid','=',$id]);

        $order_list= Db::name('porder')->where($where)->field('oid,order_no,user_name,name,number,add_time,status,remark,com_time,com_number,osort,com_pocart,spec,seller')->select()->toArray();
        if($order_list){
            return $this->success('success',$order_list);
        }else{
            return $this->error('查询失败，请检查网络或稍后再试!');
        }
    }

    //订单详情及生产详情追踪与修改
    public function detail(){
        $id = $this->request->param('id',0);
	    if(empty($id)) $this->error("参数错误！");

        if($this->request->isPost()){
        	$pcid=$this->request->post('pcid');
	    	if(empty($pcid)) $this->error("参数错误！");

	        $data ['order_info']= Db::name('Porder')->where('oid',$id)->where('status = 1 or status = 2')->field('oid,order_no,user_name,name,number,add_time,status,remark,com_time,com_number,osort,com_pocart,spec,seller')->find();
	        if (empty($data ['order_info'])) 
	        	$this->error('订单号正确或已完成,当前不能修改!');

			$updata=PocartModel::get($pcid);
        	//修改订单会对历史数据有影响需要加权限
			// $updata->poid=>$id;
        	// $updata->proid = $this->request->post('proid');
        	
        	$updata->op_time = time();
        	$updata->option_id=$this->userId;
        	$updata->completioner = $this->userName;

			if (!empty($sum_num=$this->request->post('sum_num')) and $sum_num>=1)
	    		$updata->sum_num=['inc',$this->request->post('sum_num')];

			if (!empty($cstatus=$this->request->post('cstatus')) and $cstatus>=1)
	    		$updata->cstatus=$this->request->post('cstatus');

	    	if ( !empty($defective=$this->request->post('defective')) and $defective>=1)
        		$updata->defective = ['inc',$this->request->post('defective')];

	    	$updata->save();

			$pocartdata=$updata->toArray();
			if (!empty($remark=$this->request->post('remark')))
				$process_log['remark']=$remark;
			
			$process_log['postdata']=json_encode($this->request->post());
			$process_log['poid']=$pocartdata['poid'];
			$process_log['pid']=$pocartdata['pcid'];
			$process_log['pcid']=$pocartdata['pcid'];
			$process_log['proid']=$pocartdata['proid'];
			$process_log['sum_num']=$pocartdata['sum_num'];
			$process_log['op_time']=$pocartdata['op_time'];
			$process_log['cstatus']=$pocartdata['cstatus'];
			$process_log['option_id']=$pocartdata['option_id'];
			$process_log['defective']=$pocartdata['defective'];

			DB::name('process_log')->insert($process_log);

        }else{
	        $data ['order_info']= Db::name('Porder')->where('oid',$id)->where('status < 4')->field('oid,order_no,user_name,name,number,add_time,status,remark,com_time,com_number,osort,com_pocart,spec,seller')->find();
        }

        $data['process_list']  = Db::name('pocart')->alias('a')->join('Production b','a.proid = b.pid','left')->join('Porder c','a.poid = c.oid','left')->where('a.poid',$id)->where('a.cstatus <4')->order('b.sort asc,a.pcid asc')->field('a.*,b.pname')->select()->toArray();
        
        if($data){
            return $this->success('success',$data);
        }else{
            return $this->error('查询失败，请检查网络或稍后再试!');
	    }
    } 

    /*
	工序间交接
    */

    public function trans_process(){
    	$action=$this->request->post('action');//1创建2修改3接受    	
    	$sum_num=$this->request->post('sum_num');

    	if(empty($sum_num) or empty($action)) 
    		$this->error("参数错误！");
    	
    	//提交交接单
    	if ($action==1) {
			$pcid=$this->request->post('sub_pcid');
	    	if (empty($pcid)) $this->error('参数错误！');

	    	$pocartdata=Db::name('pocart')->where('pcid',$pcid)->where('sum_num','>=',$sum_num)->find();
	    	if (empty($pocartdata)) $this->error('请确认订单及完成数量');

	    	$updata=[
	    		'oid'=>$pocartdata['poid'],
	    		// 'pid'=>$pocartdata['pid'],
	    		'pid'=>0,
	    		'sum_num'=>$sum_num,
	    		'sub_pcid'=>$pcid,
	    		'sub_proid'=>$pocartdata['proid'],
	    		'sub_time'=>time(),
	    		'sub_id'=>$this->userId,
	    		'sub_name'=>$this->userName,
	    		'sub_remark'=>$this->request->post('remark',''),
	    	];
			$updata['id']=DB::name('trans_log')->insertGetId($updata);
            return $this->success('success',$updata);
    	}elseif($action==2){
    		$id=$this->request->post('id');
    		$pcid=$this->request->post('sub_pcid');

	    	if (empty($pcid) or empty($id)) $this->error('参数错误！');

	    	$pocartdata=Db::name('pocart')->where('pcid',$pcid)->where('sum_num','>=',$sum_num)->find();
	    	if (empty($pocartdata)) $this->error('请确认订单及完成数量');
			$edit_able=DB::name('trans_log')->where('id',$id)->where('sub_id',UID)->where('get_id',0)->find();
	    	if(empty($edit_able)) $this->error('只能修改本人提交且未被下一工序接受的订单');

	    	$updata=[
	    		'sum_num'=>$sum_num,
	    		'sub_time'=>time(),
	    		'sub_remark'=>$this->request->post('sub_remark',''),
	    	];
			DB::name('trans_log')->where('id',$id)->update($updata);
    	}elseif($action==3){
    		$id=$this->request->post('id');
    		$get_pcid=$this->request->post('get_pcid');
	    	if (empty($id)or empty($get_pcid)) $this->error('参数错误！');

	     	$get_proid=DB::name('pocart')->where('pcid',$get_pcid)->value('proid');
			$translog=TransLogModel::where('id',$id)->findOrEmpty();
    		$translog->get_pcid=$get_pcid;
    		$translog->get_proid=$get_proid;
    		$translog->get_time=time();
    		$translog->get_id=$this->userId;
    		$translog->get_name=$this->userName;
    		$translog->get_remark=$this->request->post('get_remark','');
			$translog->save();
			DB::name('pocart')->where('pcid',$get_pcid)->update(['number'=>$translog->sum_num]);
    	}
    	return $this->success('success');
    }
    
    public function finish_process($pcid=''){
    	$pcdata=PocartModel::where('cstatus',0)->where('pcid',$pcid)->findOrEmpty();
    	if (empty($pcdata)) $this->error('该工序无法修改或已完成');
    		
    	$pcdata->cstatus=1;
    	if ($pcdata->save()){
    		$order=PocartModel::where('poid',$pcdata->poid)->field('count(pcid) as total,count(`cstatus=1`) done')->select();
    		if($order['total']==$order['done']){
    			DB::name('porder')->where('oid',$pcdata->poid)->update(['status'=>3]);
    		}
    		var_dump($order);exit();
			$this->success('success','成功');
    	}
		$this->error('该工序无法修改或已完成');
    }


    //所有记录汇总
    public function trans_log_count(){
    	$oid=$this->request->post('oid',0);
    	if(empty($oid)) $this->error('参数错误！');

		$getdata=DB::name('trans_log')->where('oid',$oid)->field('sub_name,sub_id,get_name,get_id,sum(sum_num) as get_num,get_time,get_pcid')->order('id asc')->group('get_pcid')->select()->toArray();

		$subdata=DB::name('trans_log')->where('oid',$oid)->field('sub_name,sub_id,sum(sum_num) as sub_num,get_name,sub_time,sub_pcid,get_id')->order('id asc')->group('sub_pcid')->select()->toArray();
		
		$data=DB::name('pocart')->where('poid',$oid)->field('pcid,poid as oid,proid,remark as pname,sum_num,defective as def_num,option_id')->order('order asc')->select()->toArray();
		//取出所有相关的user_id,合并去重过滤空和0
		$uids=array_filter( array_unique(array_merge(
			array_column($getdata,'sub_id'), 
			array_column($getdata,'get_id'),
			array_column($subdata,'sub_id'),
			array_column($subdata,'get_id'),
			array_column($data,'option_id')
		)));

		$depts=$this->getDeptsByid($uids);
		foreach($data as $key=>$value){
			foreach($getdata as $k=>$v){
				if($v['get_pcid']==$value['pcid']){
					$v['get_dept_name']= $depts[$v['get_id']];
					$v['sub_dept_name']= $depts[$v['sub_id']];
					$data[$key]['getdata']=$v;
				}else{
					$data[$key]['getdata']=[];
				}
			}

			foreach($subdata as $ke=>$va){
				if($va['sub_pcid']==$value['pcid']){
					$va['get_dept_name']= $depts[$va['get_id']];
					$va['sub_dept_name']= $depts[$va['sub_id']];
					$data[$key]['subdata']=$va;
				}else{
					$data[$key]['subdata']=[];
				}
			}

		}
    	return $this->success('success',$data);
    }
    
    //每条记录细节
    public function trans_log(){	
    	$where['oid']=$this->request->post('oid','');
    	$where['sub_pcid']=$this->request->post('sub_pcid','');
    	$where['get_pcid']=$this->request->post('get_pcid','');
    	$where['sub_id']=$this->request->post('sub_id','');
    	$where=array_filter($where);
    	if($this->request->has('get_id'))
    		$where['get_id']=$this->request->post('get_id');

		$data=DB::name('trans_log')->where($where)->order('sub_time desc')->select()->toArray();
		if ($this->request->has('oid')) {
			$sub_pcids=array_unique(array_column($data, 'sub_pcid'));
			$get_pcids=array_unique(array_filter(array_column($data, 'get_pcid')));
			$count=['sub_count'=>0,'get_count'=>0];
			// var_dump($data);
			// var_dump($get_pcids);
			// exit();
			foreach($data as $k=>$v){
				if(in_array($v['sub_pcid'],$sub_pcids))
					$count['sub_count']+=$v['sum_num'];

				if(in_array($v['get_pcid'],$get_pcids))
					$count['get_count']+=$v['sum_num'];
			}
			$data['count']=$count;
		}
    	return $this->success('success',$data);
    }


    // 根据订单号获取工序id列表默认值下一工序
    public function get_next_pcid(){
    	$poid=$this->request->post('oid');
    	$pcid=$this->request->post('pcid',0);
		$data=DB::name('pocart')->where('poid',$poid)->field('pcid,poid as oid,proid,remark as pname')->order('order asc')->select()->toArray();
		foreach ($data as $k=>$value) {
			if ($value['pcid']==($pcid+1)){
				$data[$k]['default']=1;
			}else{
				$data[$k]['default']=0;
			}
		}
    	return $this->success('success',$data);
    }

    public function wait2store(){
		$data=DB::name('porder')->where('status',3)->order('com_time asc')->select()->toArray();
    	return $this->success('success',$data);
    }

    public function put2store(){
    	$oid=$this->request->post('oid');
	    if (empty($oid)) $this->error('参数错误！');

    	$data=PocartModel::where('status',3)->where('oid',$oid)->findOrEmpty();
		if (empty($data)) $this->error('无法入库请确认此订单信息');	

		// $data->status=5; 
		// 等老板按排处理流程再定
    	return $this->success('success','入库成功');
    }

    public function getDeptsByid($uids=[])
    {
    	// echo $uids;exit();
		$user=DB::name('user')->max('id')->find();

		var_dump($user);exit();
		// $user=DB::name('user')->whereIn('id',$uids)->field('id,dept_name')->select()->toArray();
		$depts=[];
		foreach($user as $k=>$v){
			$depts[$v['id']]=$v['dept_name'];
		}
		$depts[0]='未分配';
		return $depts;
    }


}