<?php

/**
 * @Description		用户平台信息  如:微信平台  1.小程序-oenpid 2.公众号-openid  等
 * @Author			HJ
 * @Date			2019-05-21 17:33:00
 * @LastEditTime	2019-05-21 17:33:00
 */

namespace api\app\model;

use think\Model;

class UserPlatformInfoModel extends Model{

	protected $pk 			= 'id';
	protected $name 		= 'user_platform_info';

	// --------------------------------------------------------------------
	/**
	 * @access    	public
	 * @example 	通过小程序openid获取用户ID
	 * @param 	  	string 	$openid		小程序openid
	 * @author 		HJ
	 * @version 	2019-03-18 11:57:45
	 * @return    	int
	 */
	
	public static function getSprOpenidUserId($openid) : int
	{
		if(empty($openid))	return 0;

		$user_id 			=  UserPlatformInfoModel::where('spr_openid',$openid)->value('user_id');

		if(empty($user_id))	return 0;

		return $user_id;
	}
	// --------------------------------------------------------------------
	/**
	 * @access    	public
	 * @example 	通过小程序openid获取用户ID
	 * @param 	  	string 	$mobile		小程序mobile	
	 * @author 		Lius
	 * @version 	2019-11-06 11:57:45
	 * @return    	int
	 */
	
	public static function getSprMobileUserId($mobile	) : int
	{
		if(empty($openid))	return 0;

		$user_id 			=  UserPlatformInfoModel::where('mobile	',$mobile)->value('user_id');

		if(empty($user_id))	return 0;

		return $user_id;
	}
	
	
}

?>