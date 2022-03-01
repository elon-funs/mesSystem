<?php


/**
 * @Description		用户模型
 * @Author			HJ
 * @Date			2019-08-08 15:49:59
 * @LastEditTime	2019-08-08 15:49:59
 */

namespace api\app\model;

use think\Model;

class UserModel extends Model{

	protected $pk 					= 'id';
	protected $name  				= 'user';


	// --------------------------------------------------------------------
	/**
	 * @access    	public
	 * @example 	通过小程序openid获取用户ID
	 * @param 	  	string 	$openid		小程序openid
	 * @author 		HJ
	 * @version 	2019-03-18 11:57:45
	 * @return    	int
	 */
	
	public static function getSprOpenidUserId(string $openid) : int
	{
		if(empty($openid))	return 0;
		return UserPlatformInfoModel::where('spr_openid',$openid)->value('user_id');
	}

	// --------------------------------------------------------------------
	/**
	 * @access		public
	 * @example		获取用户信息
	 * @param		string 	variable		explain
	 * @author		HJ
	 * @version	2019-05-21 18:00:48
	 * @return		json
	 */
	
	public static function getUserInfo(int $user_id = 0, string $field = 'id,sex,last_login_time,balance,user_status,user_login,user_nickname,avatar,mobile,signature') : array
	{

		$where 									= [];
		$where['user_type']						= 2;

		if(empty($user_id)){
			$where['id']							= UID;
		}else{
			$where['id'] 							= $user_id;
		}

		//有就登录
		$user_info								= UserModel::where($where)->field($field)->find();

		if(!$user_info)							return [];

		$user_info 								= $user_info->toArray();

		$user_info['balance']					= empty( $user_info['balance'] )? '0.00': sprintf("%.2f",$user_info['balance'] / 100 ) ;

		return $user_info;
	}


	// --------------------------------------------------------------------
	/**
	 * @access		public
	 * @example		更新用户信息
	 * @param		string 	variable		explain
	 * @author		HJ
	 * @version	2019-05-21 18:47:20
	 * @return		bool
	 */
	
	public static function userUpdate(int $user_id = 0, array $data) : bool
	{
		if(!$user_id)							return false;
		$result 								= UserModel::where('id',$user_id)->update($data);

		if(!empty($result)){
			return true;
		}else{
			return false;
		}

	}


	// --------------------------------------------------------------------
	/**
	 * @access		public
	 * @example		生成 用户分享二维码
	 * @param		string 	variable		explain
	 * @author		HJ
	 * @version		2019-07-19 23:09:29
	 * @return		json
	 */
	public static function createUserQRcode($user_id, $accessToken){
		$getWXACodeUnlimitUrl 				= 'https://api.weixin.qq.com/wxa/getwxacodeunlimit?access_token='.$accessToken;
		$page 								= 'pages/customize/customize';


		$data 								= [
			'scene'			=> "agent_id=".$user_id,
			'page'			=> $page,
		];
		$data 								= json_encode($data);


		$imageFile 							= request_curl($getWXACodeUnlimitUrl,$data,'post');

		$data 								= array(
			'imageFile'						=>$imageFile,
			'agent_id' 						=>$user_id,
		);

		return $data;
	}

}