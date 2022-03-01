<?php



/**
 * @Description		微信消息目标
 * @Author			HJ
 * @Date			2019-08-19 14:52:19
 * @LastEditTime	2019-08-19 14:52:19
 */



namespace api\app\model;

use think\Db;

class WechatMessageModel{

	protected static $WeChantSendMessageUrl			= 'https://api.weixin.qq.com/cgi-bin/message/wxopen/template/send?access_token=';
	protected static $WeChatSprAppId				= 'wxb4f2465d6029ed12';																	//微信小程序APPID
	protected static $WeChatSprAppSecret			= 'c7756c73bd192e94f0873ae62bd4e81d';													//微信小程序APPSecret


	// --------------------------------------------------------------------
	/**
	 * @access		public
	 * @example		小程序发送消息
	 * @param		string 	variable		explain
	 * @author		HJ
	 * @version		2019-08-19 15:11:24
	 * @return		json
	 */
	public static function XCXSendNotice($openid, $form_id,$user_id, $other=array(),$type=1){


		if($type ==1 ){				//投诉回复
			$template_id			= 'R7t5Yl-pD274kO67j6q5Cy7dhbTy3PnWPOeqaJqYtVU';

			$field					= array(
				'keyword1'			=> array('value' => $other['title']),			//投诉主题
				'keyword2'			=> array('value' => $other['content']),			//反馈信息
				'keyword3'			=> array('value' => date('Y-m-d H:i')),			//时间
			);

		}

		$data = array(
			'touser'				=> $openid,
			'template_id'			=> $template_id,
			// 'page' => 'xxxxxxxxxxxxxxxx',       //转跳小程序页面
			"form_id"				=> $form_id,
			'data'					=> $field
		);//服务通知发送必要字段

		$accessToken				= self::getAccessToken($user_id);

		$params						= json_encode($data, JSON_UNESCAPED_UNICODE);
		$result						= self::send_post(self::$WeChantSendMessageUrl.$accessToken, $params);//其中$accessToken为发送服务通知的安全令牌

		$jsonResult					= json_decode($result,true);
		if($jsonResult['errcode'] == '40001'){
			$accessToken			= self::getNewAccessToken($user_id);

			$params					= json_encode($data, JSON_UNESCAPED_UNICODE);
			$result					= self::send_post(self::$WeChantSendMessageUrl.$accessToken, $params);//其中$accessToken为发送服务通知的安全令牌

		}

		// var_dump($result); 
		return $jsonResult;  //{"errcode":0,"errmsg":"ok"}
	}


	// --------------------------------------------------------------------
	/**
	 * @access		public
	 * @example		获取微信 access_token
	 * @param		string 	variable		explain
	 * @author		HJ
	 * @version		2019-08-19 14:55:47
	 * @return		json
	 */
	
	protected static function getAccessToken($user_id){

		if(!empty($user_id)){
			$access_token_data				= Db::name('user_platform_info')->where('user_id',$user_id)->field('access_token_expire_time,access_token')->find();
			if(!empty($access_token_data)){
				if($access_token_data['access_token_expire_time'] > time() ){
					return $access_token_data['access_token'];
				}
			}
		}

		$url								= "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".self::$WeChatSprAppId."&secret=".self::$WeChatSprAppSecret;
		$access_token_json					= file_get_contents($url);
		$access_token_data					= json_decode($access_token_json,true);
		$access_token						= '';
		$expires_in							= 7000 + time();

		if(!empty($access_token_data['access_token'])){
			$access_token					= $access_token_data['access_token'];
			if(!empty($user_id)){
				Db::name('user_platform_info')->where('user_id',$user_id)->data(array('access_token'=>$access_token,'access_token_expire_time'=>$expires_in))->update();
			}
			return $access_token;
		}
		return  '';
	}


	// --------------------------------------------------------------------
	/**
	 * @access		public
	 * @example		直接获取 token
	 * @param		string 	variable		explain
	 * @author		HJ
	 * @version		2019-08-19 14:59:26
	 * @return		json
	 */
	protected static function getNewAccessToken($user_id){
		$url				= "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".self::$WeChatSprAppId."&secret=".self::$WeChatSprAppSecret;
		$AccessTokenJson	= file_get_contents($url);
		$AccessTokenData	= json_decode($AccessTokenJson,true);
		$accessToken 		= '';
		$expires_in			= 7000 + time();

		if(!empty($AccessTokenData['access_token'])){
			$accessToken    = $AccessTokenData['access_token'];
			if(!empty($user_id)){
				Db::name('user_platform_info')->where('user_id',$user_id)->data(array('access_token'=>$accessToken,'access_token_expire_time'=>$expires_in))->update();
			}

			return $accessToken;
		}
	}

	// --------------------------------------------------------------------
	/**
	 * @access		public
	 * @example		post发送数据
	 * @param		string 	variable		explain
	 * @author		HJ
	 * @version		2019-08-19 15:02:10
	 * @return		json
	 */
	
	protected  static function send_post( $url, $params ) {
		$options = array(
			'http'		=> array(
				'method'	=> 'POST',
				// header 需要设置为 JSON
				'header'	=> 'Content-type:application/json',
				'content'	=> $params,
				// 超时时间
				'timeout'	=> 60
			)
		);

		$context = stream_context_create($options);
		$result = file_get_contents( $url, false, $context );
		return $result;
	}

}



?>