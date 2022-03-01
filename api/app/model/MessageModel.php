<?php

/**
 * @Description		投诉建议模块
 * @Author			LiuS
 * @Date			2021-08-19 09:18:01
 * @LastEditTime	2021-08-19 09:18:01
 */


namespace api\app\model;

use think\Model;
use think\Db;

class MessageModel extends Model{

	protected $pk 					= 'id';
	protected $name  				= 'message';
	protected static $message_length		= 20;


	// --------------------------------------------------------------------
	/**
	 * @access		public
	 * @example		添加消息推送
	 * @param		string 	title		主题
	 * @param		string 	content		内容
	 * @param		int 	type		1:发货确认消息推送	2:订单确认消息推送 3:产品知识推送 4:新产品信息推广新老客户 5:产品信息变动推送(?) 6:企业新产品、新咨询进行推送
	 * @author		LiuS
	 * @version		2021-08-19 10:31:47
	 * @return		json
	 */
	public static function addMessagePush(string $content = '', int $type = 1,int $user_id = 0) : bool
	{
		if(empty($content))				return false;

		$title						= '';

		if($type == 1){
			$title						= '发货确认消息';
		}elseif($type == 2){
			$title						= '订单确认消息';
		}elseif($type == 3){
			$title						= '产品知识';
		}elseif($type == 4){
			$title						= '新产品信息推广新老客户';
		}elseif($type == 5){
			$title						= '产品信息变动';
		}elseif($type == 6){
			$title						= '企业新产品、新咨询';
		}elseif($type == 7){
			$title						= '系统消息';
		}

		$insert_data					= [
			'title'						=> $title,
			'user_id'					=> $user_id,
			'content'					=> $content,
			'create_time'				=> time(),
			'status'					=> 0,
			'type'						=> 1,				//0:投诉建议  1:消息推送
		];


		$result							= MessageModel::create($insert_data);
		if(empty($result)){
			return false;
		}else{
			return true;
		}

	}


	// --------------------------------------------------------------------
	/**
	 * @access		public
	 * @example		获取最新消息
	 * @param		string 	variable		explain
	 * @author		LiuS
	 * @version		2021-08-19 11:23:58
	 * @return		json
	 */
	public static function getMessageList(int $user_id) : array
	{
		// $message_list					= MessageModel::where(function($query) use ($user_id) {
		// 									$query->whereOr('user_id',$user_id);
		// 									$query->whereOr('user_id',0);
		// 								})->where('type',1)->group('message_type')->order('id DESC')->field('id,title,content,create_time,status,message_type')->limit(10)->select();

		// if($message_list->isEmpty())	return [];
		// $message_list					= $message_list->toArray();

		// echo MessageModel::getLastSql();

		// print_r($message_list);


		$message_list					= Db::query('select id,title,content,create_time,status,message_type from (select * from mes_message order by id desc limit 99) a where (user_id = '.$user_id.' or user_id = 0) GROUP BY a.message_type order by id DESC');

		// print_r($message_list);

		//获取未阅读个数
		$message_status_list			= MessageModel::where(function($query) use ($user_id) {
											$query->whereOr('user_id',$user_id);
											$query->whereOr('user_id',0);
										})->where('type',1)->where('status',0)->group('message_type')->order('create_time DESC')->field('message_type,count(id) as unread_num')->select();

		$now_time						= time();
		$day_time						= 60 * 60 * 24;
		foreach ($message_list as $key => &$value) {
			if($value['message_type'] == 0){
				$value['is_new']				= 0;
				if($value['create_time'] - $day_time < $now_time){
					$value['is_new']				= 1;
				}
			}
			$value['unread_num'] 				= 0;
			$value['create_time']				= date('Y-m-d H:i:s',$value['create_time']);
			foreach ($message_status_list as $k => $v) {
				if($v['message_type'] == $value['message_type'] && $value['message_type'] != 0){
					$value['unread_num']		= $v['unread_num'];
					break;
				}
			}
		}

		return $message_list;
	}


	// --------------------------------------------------------------------
	/**
	 * @access		public
	 * @example		获取该类型的详情消息推送
	 * @param		string 	variable		explain
	 * @author		LiuS
	 * @version		2021-08-19 11:34:11
	 * @return		json
	 */
	public static function getMessageDetails(int $page = 1, int $message_type = 1,int $user_id = 0) : array
	{

		$message_list					= MessageModel::where('message_type', $message_type)
										->where(function($query) use ($user_id) {
											$query->whereOr('user_id',$user_id);
											$query->whereOr('user_id',0);
										})->where('type',1)->where('message_type', $message_type)->order('create_time DESC')->page($page, self::$message_length)->field('id,title,content,create_time,status,message_type')->select();

		if($message_list->isEmpty())	return [];
		$message_list					= $message_list->toArray();

		foreach ($message_list as $key => &$value) {
			$value['create_time']				= date('Y-m-d H:i:s',$value['create_time']);
		}

		return $message_list;
	}
    
    // --------------------------------------------------------------------
	/**
	 * @access		public
	 * @example		设置消息已读
	 * @param		string 	variable		explain
	 * @author		LiuS
	 * @version		2021-08-19 11:34:11
	 * @return		json
	 */
	 public static function setMessage(int $message_id = 1,int $user_id = 0) : array
	{
	    $message = MessageModel::where('id',$message_id)->where('user_id',$user_id)->save(['status'=>1,'read_time'=>time()]);
	    return $message;
	}
}