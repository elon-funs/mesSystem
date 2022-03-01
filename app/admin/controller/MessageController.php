<?php

/**
 * @Description		消息控制器
 * @Author			HJ
 * @Date			2019-05-20 15:14:52
 * @LastEditTime	2019-05-20 15:14:52
 */


namespace app\admin\controller;

use cmf\controller\AdminBaseController;
use think\Db;

use api\app\model\WechatMessageModel;
use api\app\model\MessageModel;


class MessageController extends AdminBaseController
{

	// --------------------------------------------------------------------
	/**
	 * @access		public
	 * @example		投诉建议
	 * @param		string 	variable		explain
	 * @author		HJ
	 * @version		2019-08-19 14:07:15
	 * @return		json
	 */
	public function complaintsIndex(){


		$content 							= hook_one('admin_message_complaints_index_view');


        if (!empty($content)) {
            return $content;
        }
        $where 								= [];
        $where[]							= ['m.status', '<', 4];
        $where[]							= ['m.type', '=', 0];
        
        $user_id                        	= $this->request->param('user_id',0);				//用户ID
        $search                     	   	= $this->request->param('search','');				//主题关键词搜索

        $status                     		= $this->request->param('status', -1);				//0: 未回复 1:已回复


        if(!empty($user_id)){
        	 $where[]                    = ['m.user_id','=', $user_id];
        }
        if(!empty($search)){
        	 $where[]                    = ['m.title','like', '%'.$search.'%'];
        }
        if($status != -1){
        	 $where[]                    = ['m.status','=', $status];
        }

        $list 								= Db::name('message')->alias('m')
											->join('user u','u.id = m.user_id','LEFT')->where($where)->order('m.create_time DESC')
											->field('m.*,u.user_nickname,u.avatar,u.mobile')->paginate(10);


		$list->appends(['user_id' => $user_id, 'search' => $search,'status' => $status]);


        // 获取分页显示
        $page 								= $list->render();

        //用户列表
        $user_list							= Db::name('user')->where('user_type', 2)->field('id,user_status,user_nickname,avatar')->select();

        $this->assign("user_list", $user_list);

        $this->assign("page", $page);
        $this->assign("list", $list);
        return $this->fetch();

	}


	// --------------------------------------------------------------------
	/**
	 * @access		public
	 * @example		投诉建议 删除 联系
	 * @param		string 	variable		explain
	 * @author		HJ
	 * @version		2019-07-10 15:21:42
	 * @return		json
	 */
	public function complaintsDelete()
	{
		$message_id 							= $this->request->param('message_id');							//message_id
		if(empty($message_id))					return ['data' => [], 'code' => 0, 'message' => '删除失败'];

		$result 								= Db::name('message')->where('id',$message_id)->data(['status' => 4])->update();

		if($result){
			return ['data' => [], 'code' => 1, 'message' => '删除成功'];
		}else{
			return ['data' => [], 'code' => 0, 'message' => '删除失败'];
		}
	}

	// --------------------------------------------------------------------
	/**
	 * @access		public
	 * @example		投诉建议 批量删除
	 * @param		string 	variable		explain
	 * @author		HJ
	 * @version		2019-07-10 11:01:44
	 * @return		json
	 */
	public function complaintsDeleteBatch()
	{
		$message_id 						= $this->request->param('message_id/a',[]);

		if(empty($message_id))			return $this->error("批量删除失败！",'message/complaintsIndex');

		$message_id_ary 					= [];

		foreach ($message_id as $key => $value) {
			$message_id_ary[] 				= intval($value);
		}

		if ( DB::name('message')->where('id','in', $message_id_ary)->data(['status' => 4])->update() !== false) {

			return $this->success("删除成功！");
		} else {
			return $this->error("删除失败！");
		}
	}



	// --------------------------------------------------------------------
	/**
	 * @access		public
	 * @example		发送微信消息
	 * @param		string 	variable		explain
	 * @author		HJ
	 * @version		2019-08-19 15:14:14
	 * @return		json
	 */
	public function sendWechatMessage(){

		$message_id 							= $this->request->param('message_id', 0);			//回复消息ID

		$title									= '投诉受理通知回复';
		$content								= $this->request->param('content', '');				//回复内容
		if(empty($content))								return $this->error('请输入回复内容!');

		$message								= Db::name('message')->where('id', $message_id)->field('user_id,form_id,create_time')->find();

		$form_id								= $message['form_id'];

		if(empty($form_id))								return $this->error('缺少form_id，该消息不能回复!');

		$expiration_time						= $message['create_time'] + (60 * 60 * 24 * 7);
		$now_time								= time();
		if( ($expiration_time - 360) < $now_time )		return $this->error('form_id 已过期,不可回复!');


		$user_id								= $message['user_id'];
		$spr_openid								= Db::name('user_platform_info')->where('user_id', $user_id)->value('spr_openid');

		$other									= [
			'title'								=> $title,
			'content'							=> $content,
		];

		$result									= WechatMessageModel::XCXSendNotice($spr_openid, $form_id, $user_id, $other, 1);


		if($result['errcode'] == 0){

			Db::name('message')->where('id', $message_id)->data(['status' => 1,'reply_content' => $content])->update();

			return $this->success('发送消息成功');
		}else{
			return $this->error($result['errmsg']);
		}

	}


	// --------------------------------------------------------------------
	/**
	 * @access		public
	 * @example		推送列表
	 * @param		string 	variable		explain
	 * @author		HJ
	 * @version		2019-08-19 15:44:04
	 * @return		json
	 */
	public function pushIndex(){

		$content 							= hook_one('admin_message_push_index_view');


        if (!empty($content)) {
            return $content;
        }
        $where 								= [];
        $where[]							= ['m.status', '<', 4];
        $where[]							= ['m.type', '=', 1];
        
        $user_id                        	= $this->request->param('user_id',0);				//用户ID
        $search                     	   	= $this->request->param('search','');				//主题关键词搜索

        $status                     		= $this->request->param('status', -1);				//0:未阅读 1:已阅读 


        if(!empty($user_id)){	
        	 $where[]                    = ['m.user_id','=', $user_id];
        }
        if(!empty($search)){
        	 $where[]                    = ['m.title','like', '%'.$search.'%'];
        }
        if($status != -1){
        	 $where[]                    = ['m.status','=', $status];
        }

        $list 								= Db::name('message')->alias('m')
											->join('user u','u.id = m.user_id','LEFT')->where($where)->order('m.create_time DESC')
											->field('m.*,u.user_nickname,u.avatar,u.mobile')->paginate(10);


		$list->appends(['user_id' => $user_id, 'search' => $search,'status' => $status]);


        // 获取分页显示
        $page 								= $list->render();

        //用户列表
        $user_list							= Db::name('user')->where('user_type', 2)->field('id,user_status,user_nickname,avatar')->select();

        $this->assign("user_list", $user_list);

        $this->assign("page", $page);
        $this->assign("list", $list);
        return $this->fetch();
	}


	// --------------------------------------------------------------------
	/**
	 * @access		public
	 * @example		给用户发送消息
	 * @param		string 	variable		explain
	 * @author		HJ
	 * @version		2019-08-19 15:55:02
	 * @return		json
	 */
	public function sendUserMessage(){

		$type 									= $this->request->param('type',0);					//0:全部推送			1:指定用户ID
		$user_id								= $this->request->param('user_id',0);				//用户ID
		$content								= $this->request->param('content','');				//消息内容


		if($type == 1){
			if(empty($user_id))					return $this->error('请选择指定用户！');
		}
		if(empty($content))						return $this->error('请输入推送消息内容！');

		$result									= MessageModel::addMessagePush($content, 7, $user_id);

		if($result){
			return $this->success('消息推送成功');
		}else{
			$this->error('消息推送失败');
		}

	}

}