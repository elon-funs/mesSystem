<?php

/**
 * @Description		用户
 * @Author			LiuS
 * @Date			2021-11-02 15:07:46
 * @LastEditTime	2022-02-02 15:07:46
 */

namespace api\app\controller;

use api\app\model\UserModel;
use api\app\model\MessageModel;
use think\Db;
use think\facade\Cache;

class UserController extends RestBaseController
{
	public function __construct(){
		parent::__construct();
		$this->_initUser();
	}

	// --------------------------------------------------------------------
	/**
	 * @access		public
	 * @example		个人中心
	 * @param		string 	variable		explain
	 * @author		LiuS
	 * @version		2021-05-23 17:47:28
	 * @return		json
	 */
	public function getUserInfo(){


		$user 						= UserModel::getUserInfo();
        
		if($this->role == 1){
		    //待确认
		    $confirmed_count        = Db::name('porder')->where('status',0)->count();
		    //待加工
		    $processing_count       = Db::name('porder')->where('status',1)->count();
		    //加工中
		    $completed_count        = Db::name('porder')->where('status',2)->count();
		}elseif ($this->role == 2) {
		    //待确认
		  //  $confirmed_count        = Db::name('porder')->alias('a')->join('pocart b','a.oid=b.poid','left')->where('b.proid','in',$this->role_no)->where('b.completion_time =0')->where('b.cstatus <4')->where('a.status',0)->count();
		    //待加工
		    $processing_count       = Db::name('porder')->alias('a')->join('pocart b','a.oid=b.poid','left')->where('b.proid','in',$this->role_no)->where('b.completion_time =0')->where('b.cstatus <4')->where('a.status','in','1,2')->count();
		    //加工中
		    $completed_count        = Db::name('porder')->alias('a')->join('pocart b','a.oid=b.poid','left')->where('b.proid','in',$this->role_no)->where('b.completion_time >0')->where('b.cstatus <4')->where('a.status',2)->count();
		    //已完成
		    $completed_count        = Db::name('porder')->alias('a')->join('pocart b','a.oid=b.poid','left')->where('b.proid','in',$this->role_no)->where('b.completion_time >0')->where('b.cstatus <4')->where('a.status',2)->count();
		}

		$data 						= [
			'user'					=> $user,
			'confirmed_count'       => $confirmed_count ??0,
			'processing_count'      => $confirmed_count ??0,
			'completed_count'       => $confirmed_count ??0,
		];

		return $this->success('success',$data);
	}
	
	// --------------------------------------------------------------------
	/**
	 * @access		public
	 * @example		投诉建议
	 * @param		string 	variable		explain
	 * @author		LiuS
	 * @version		2021-08-19 09:07:45
	 * @return		json
	 */

	public function complaintSuggestion(){
		$title 								= $this->request->post('title','');				//主题
		$content 							= $this->request->post('content','');			//投诉建议

		$form_id							= $this->request->post('form_id','');			//form_id

		if(empty( $title))					return $this->error('complaint_subject_title_is_empty');
		if(empty( $content))				return $this->error('complaint_subject_content_is_empty');

		$insert_data						= [
			'title'						     => $title,
			'content'					     => $content,
			'create_time'				     => time(),
			'status'					     => 0,
			'read_time'					     => 0,
			'type'						     => 0,
			'user_id'					     => $this->userId,
			'form_id'					     => $form_id,
		];

		$result							    = MessageModel::create($insert_data);

		if(empty($result)){
			return $this->error('complaint_subject_content_submit_failure');	//提交投诉建议失败
		}else{
			return $this->success('success',[]);
		}

	}
    
    // --------------------------------------------------------------------
	/**
	 * @access		public
	 * @example		通知
	 * @param		string 	variable		explain
	 * @author		LiuS
	 * @version		2021-09-19 09:07:45
	 * @return		json
	 */
	public function pushindex(){
	   
	    $data                               = MessageModel::getMessageDetails(1,7,$this->userId); 
	    return $this->success('success',$data);
	}
	
	// --------------------------------------------------------------------
	/**
	 * @access		public
	 * @example		通知
	 * @param		string 	variable		explain
	 * @author		LiuS
	 * @version		2021-09-29 09:47:45
	 * @return		json
	 */
	public function setRead(){
	    $id     							= $this->request->post('id','');			//id
	    $data = MessageModel::setMessage($id,$this->userId); 
	    
	    if($data)
	        return $this->success('success',$data);
	    else
	        return $this->error(['code' => 202, 'msg' => 'Set as read failed']);
	}

	
	// --------------------------------------------------------------------
	/**
	 * @access		public
	 * @example		查看加工订单
	 * @param		string 	variable		explain
	 * @author		LiuS
	 * @version		2021-11-02 17:47:28
	 * @return		json
	 */
	public function getPorder(){
	    $search                     = $this->request->post('search',0);
	    $month                      = $this->request->post('month',[]);//month[0=>year,1=>month]
	    $page                       = $this->request->post('page',0);
	    $status                     = $this->request->post('status',1);//订单状态 0 待确认 1 待加工  2 加工中 3处理完成  4 删除
	    $limit                      = $this->request->post('limit',20);
	    $Porder                     = Db::name('porder');
	    $Porder                     = $Porder->alias('a');
	    if($search) 
	        $Porder->where('a.user_name|a.name|a.material|a.remark','like','%'.$search.'%');
	    if(!empty($month)){
	        $y                      = $month[0] ?? date('Y');
	        $m                      = $month[1] ?? date('m');
	        $str_time               = strtotime($y.'-'.$m.'-1 00:00:00');
	        if($m ==12 ){
	            $y++;
	            $end_time           = strtotime("$y-1-1 00:00:00");
	        }else{
	            $m++;
	            $end_time               = strtotime("$y-$m-1 00:00:00");
	        }
	        $Porder->where('a.add_time','>=',$str_time)->where('a.add_time','<',$end_time);
	    }
	    if($status > 3) $status = 3;
	    if($status < 1) $status = 1;
	    if($status) $Porder->where('a.status ='.$status);
	    else  $Porder->where('a.status < 4 and a.status > 0');
	    
	    if(empty($this->role)){
	        $list                   = $Porder->join('pocart b','a.oid=b.poid','left')->field('a.*,sum(b.defective) all_defective')->where('a.uid',$this->userId)->GROUP('a.order_no')->order('a.osort desc,a.add_time desc')->page($page,$limit)->select();
	    }else if($this->role ==1){
	        $list                   = $Porder->join('pocart b','a.oid=b.poid','left')->field('a.*,sum(b.defective) all_defective')->GROUP('a.order_no')->order('a.osort desc,a.add_time desc')->page($page,$limit)->select();
	    }else if($this->role ==2){
	        $list                   = $Porder->join('pocart b','a.oid=b.poid','left')->field('a.*,sum(b.defective) all_defective')->where('b.proid','in',$this->role_no)->GROUP('a.oid')->order('a.osort desc,a.add_time desc')->page($page,$limit)->select();
	    }else{}
	    
	    return $this->success('success',['list'=>$list,'page'=>$page,'limit'=>$limit,'sql'=>$Porder->getLastSql()]);
	}

    // --------------------------------------------------------------------
	/**
	 * @access		public
	 * @example		查看加工订单详情
	 * @param		string 	variable		explain
	 * @author		LiuS
	 * @version		2021-11-03 17:47:28
	 * @return		json
	 */
	public function getProductionDetail(){
	    $id                         = $this->request->post('id',0);      //id
	    
	    if(empty($id))              return $this->error(['code' => 202, 'msg' => '请填写完整!']);
	    
	    $data                       = [];
	    if(!empty($this->role)){ //最高权限
	    
	        $data['detail']             = Db::name('pocart')->alias('a')->join('Production b','a.proid = b.pid','left')->where('a.poid',$id)->where('a.cstatus <4')->order('b.sort desc,a.pcid asc')->field('a.*,b.pname,b.sort')->select();
	   // }else if($this->role ==2){ // 一般权限
	   //     $data['detail']             = Db::name('pocart')->alias('a')->join('Production b','a.proid = b.pid','left')->where('a.proid','in',$this->role_no)->where('a.poid',$id)->order('a.add_time desc')->field('a.*,b.pname')->select();
	    }else{  //普通用户
	        
	        $data['detail']             = Db::name('pocart')->alias('a')->join('Production b','a.proid = b.pid','left')->join('Porder c','a.poid = c.oid','left')->where('c.uid',$this->userId)->where('a.poid',$id)->where('a.cstatus <4')->order('b.sort desc,a.pcid asc')->field('a.*,b.pname,b.sort')->select();
	    }
	    
	    $data['data']                   = Db::name('Porder')->where('oid',$id)->where('status < 4')->find();
	    
	    
	    return $this->success('success',$data);
	}
    
    // --------------------------------------------------------------------
	/**
	 * @access		public
	 * @example		加工订单操作
	 * @param		string 	variable		explain
	 * @author		LiuS
	 * @version		2021-11-03 17:47:28
	 * @return		json
	 */
    public function setProduction(){
        // if(empty($this->role))     return $this->error(['code' => 202, 'msg' => '权限不足!']);
        $pcid                           = $this->request->post('pcid',0);//处理路程ID
	    $poid                           = $this->request->post('poid',0);//材料订单ID
	    $option_id                      = $this->request->post('option_id',0);//处理工人ID
	    $completioner                   = $this->request->post('completioner','');//工艺流程ID
	    $com_num                        = $this->request->post('com',0);//完成数量
	    $sum_num                        = $this->request->post('sum',0);//材料总数
	    $cstatus                        = $this->request->post('cstatus',0);//完成与否 1 完成 0未完 2部分完成(量大订单分批次操作时使用)
	    $remark                         = $this->request->post('remark','');//备注 option_id
	    $com_pocart                     = $this->request->post('com_pocart',0);//完成度 com_pocart 100为单位
	    $defective                      = $this->request->post('defective',0);//残次品数
	    
        $data                       = [
            'sum_num'               => $sum_num,
            'com_num'               => $com_num,
            'sum_num'               => $sum_num,
            'cstatus'               => $cstatus, 
            'option_id'             => $option_id,
            'remark'                => $remark,
            'completioner'          => $completioner,
            'defective'             => $defective
        ];  
        if(!empty($cstatus))
            $data['completion_time']= time();
        else
            $data['op_time']        = time();
        if(!empty($com_pocart)){ 
            $status = 2;
            if($com_pocart == 100 ) $status = 3;
            Db::name('porder')->where('oid',$poid)->update(['com_pocart'=>$com_pocart,'status'=>$status]);
        }
        if($this->role ==2){
            $res                    = Db::name('pocart')->where('pcid',$pcid)->where('poid',$poid)->where('proid','in',$this->role_no)->update($data);    
        }else{
            $res                    = Db::name('pocart')->where('pcid',$pcid)->where('poid',$poid)->update($data); 
        }
        
        if($res)
            return $this->success('success',$data);
        else
            return $this->error(['code' => 202, 'msg' => '处理失败!']);
    }
    
    /**
	 * @access		public
	 * @example		加工订单操作
	 * @param		string 	variable		explain
	 * @author		LiuS
	 * @version		2021-11-03 17:47:28
	 * @return		json
	 */
	public function getUsers(){
        if(empty($this->role))     return $this->error(['code' => 202, 'msg' => '权限不足!']);
	     
        $search                         = $this->request->post('search','');//g关键词ID
        
        $role                           = $this->request->post('type',0);//g关键词ID
        if(empty($role)){
            $data                           = Db::name('user')->where('user_nickname|user_login|signature','like','%'.$search.'%')->where('role',0)->where('user_status',1)->where('user_type',2)->field('user_nickname,user_login,signature,id')->select();
        }else{
            $data                           = Db::name('user')->where('user_nickname|user_login|signature','like','%'.$search.'%')->where('role > 0')->where('user_status',1)->where('user_type',2)->field('user_nickname,user_login,signature,id')->select();
        }
        
        if($data)
            return $this->success('success',$data);
        else
            return $this->error(['code' => 202, 'msg' => '没有数据!']);
        
	}
	
	/**
	 * @access		public
	 * @example		手机端用户信息修改
	 * @param		string 	variable		explain
	 * @author		LiuS
	 * @version		2021-11-03 17:47:28
	 * @return		json
	 */
	public function up_user(){
	    $user_login                         = $this->request->post('user_login','');//用户名
        $user_nickname                      = $this->request->post('user_nickname','');//用户昵称
        $signature                          = $this->request->post('signature','');//用户公司名称
        // $user_login                         = $this->request->post('user_login','');//用户名
        if(empty($user_login) || empty($user_nickname) || empty($signature)) return $this->error('请输入参数!');
        $data                               = [
            'user_login'                    => $user_login,
            'user_nickname'                 => $user_nickname,
            'signature'                     => $signature,
            ];
        $user 						        = Db::name('user')->where('id',$this->userId)->find();
		if(empty($user))				return $this->error(['code' => 202, 'msg' => '用户信息错误!']);
        $res =Db::name('user')->where('id', $user["id"])->update($data);
        if($res)	
            return $this->success('LOGIN_SUCCESS', []);
		return $this->error(['code' => 202, 'msg' => '更新失败!']);
	}
	
	/**
	 * @access		public
	 * @example		手机端密码修改 密码/昵称
	 * @param		string 	variable		explain
	 * @author		LiuS
	 * @version		2021-11-03 17:47:28
	 * @return		json
	 */
	public function up_pws(){
        // $user_login                         = $this->request->post('user_login','');//用户名
        $user_nickname                      = $this->request->post('user_nickname','');//用户昵称
        // $signature                          = $this->request->post('signature','');//用户公司名称
        // $user_login                         = $this->request->post('user_login','');//用户名
        $password                           = $this->request->post('user_pass','');//用户密码
        $old_pass                           = $this->request->post('old_pass','');//用户旧密码

		if(empty($password))				return $this->error('请输入新密码!');
		if(empty($old_pass))			    return $this->error('请输入旧密码!');


		$user 						        = Db::name('user')->where('id',$this->userId)->find();
		if(empty($user))				return $this->error(['code' => 202, 'msg' => '用户信息错误!']);

		$user_pass					        = $user['user_pass'];

		if(cmf_password($old_pass) != $user_pass ){
			return $this->error(['code' => 202, 'msg' => '帐号/密码错误!']);
		}

		if($user['user_status'] != 1){
			return $this->error(['code' => 202, 'msg' => '该帐号已被禁用!']);
		}
		if(!empty($user_nickname)) $data['user_nickname'] = $user_nickname;//cmf_password($user_nickname);
		$data['user_pass']                  = cmf_password($password);
		$res =Db::name('user')->where('id', $user["id"])->update($data);
		if($res)	return $this->success('LOGIN_SUCCESS', []);
		return $this->error(['code' => 202, 'msg' => '更新失败!']);
	}
	
	/**
	 * @access		public
	 * @example		工作日报表
	 * @param		string 	variable		explain
	 * @author		LiuS
	 * @version		2021-11-03 17:47:28
	 * @return		json
	 */
	public function jobday_table(){
	    $str_time             		= $this->request->param('str_time','');      //开始时间
        $end_time             		= $this->request->param('end_time','');      //结束时间
        $option_id             		= $this->request->param('option_id','');      //操作人ID
        if($end_time) $end_time     = strtotime($end_time);
        else                    $end_time       = time();
        if($str_time) $str_time     = strtotime($str_time);
        else                    $str_time       = strtotime(date('Y-m-1 00:00:00'));//strtotime('-1 month ',$str_time);
        if(empty($this->role))     return $this->error(['code' => 202, 'msg' => '权限不足!']);
            
        if($this->role == 1){
            // $cust_list                          = Db::name('pocart')->alias('c')->join('user u','c.option_id = u.uid','left')->where('c.cstatus','<',4)->select();
            $Obj                    = Db::name('pocart')->alias('c')->join('user u','c.option_id = u.id','left')->join('Production p','c.proid = p.pid','left');

            if($end_time) $Obj->where('completion_time','<',$end_time);
            if($str_time) $Obj->where('completion_time','>=',$str_time);
            $job_list               = $Obj->where('c.option_id','>',0)->where('c.cstatus','in','1,2')->field('c.*,p.pname,u.user_login,u.user_nickname')->select();
        }else{
            $Obj                    = Db::name('pocart')->alias('c')->join('user u','c.option_id = u.id','left')->join('Production p','c.proid = p.pid','left');

            if($end_time) $Obj->where('completion_time','<',$end_time);
            if($str_time) $Obj->where('completion_time','>=',$str_time);
            $job_list               = $Obj->where('c.option_id',$this->userId)->where('c.cstatus','in','1,2')->field('c.*,p.pname,u.user_login,u.user_nickname')->select();
            // $job_list                   = Db::name('pocart')->alias('c')->join('user u','c.option_id = u.uid','left')->join('Production p','c.proid = p.pid','left')->where('c.option_id',$this->userId)->where('c.cstatus','in','1,2')->field('c.*,p.pname,u.user_login,u.user_nickname')->order('completion_time desc')->select();//列表主要显示 完成时间 工序 完成数量
        }
        if($job_list){
            return $this->success('LOGIN_SUCCESS', ['list'=>$job_list]);
        }
        return $this->error(['code' => 202, 'msg' => '没有数据!']);
	 } 
	 
}