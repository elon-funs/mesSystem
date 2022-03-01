<?php

/**
 * @Description		公共模块
 * @Author			LiuS
 * @Date			2020-11-02 15:07:46
 * @LastEditTime	2022-02-11 15:07:46
 */


namespace api\app\controller;


use api\app\model\OptionModel;
use api\app\model\UserPlatformInfoModel;
use api\app\model\UserModel;
use wxapp\aes\WXBizDataCrypt;

use api\app\model\AssetModel;

use api\app\model\ProductModel;

use think\Db;
use think\facade\Cache;

class PublicsController extends RestBaseController
{

	 
	public function index()
    {
        
    }
    
    public function getHead(){
        $com_time                   = date("Y-m-d");
        $td_date                    = strtotime($com_time);
        $yd_date                    = $td_date - 24*3600;
        $te_date                    = $td_date + 24*3600;
        
        $td_scrap                   = Db::name('Scrap')->where('add_time >'.$td_date)->where('add_time <'.$te_date)->where('status',1)->sum('num');
        $yd_scrap                   = Db::name('Scrap')->where('add_time >'.$yd_date)->where('add_time <'.$td_date)->where('status',1)->sum('num');
        
        $completed_count            = Db::name('Pocart')->where('completion_time >'.$td_date)->where('completion_time <'.$te_date)->where('cstatus',1)->sum('com_num');
        $confirmed_count            = Db::name('Pocart')->where('add_time >'.$td_date)->where('add_time <'.$te_date)->where('cstatus',1)->sum('sum_num');
        $order_data                 = Db::name('Porder')->where('com_time >='.$td_date)->where('status','in','1,2,3')->select();
        // var_dump($order_data);
        $cf_scrap_count             = Db::name('Scrap')->where('status',0)->sum('num');
        
        
        
        $data 						= [
			'td_scrap'				=> $td_scrap??0,//今日报废
			'yd_scrap'				=> $yd_scrap??0,//昨日报废
			'completed_count'		=> $completed_count??0,//今日产量
			'confirmed_count'       => $confirmed_count ??0,//待生产
			'cf_scrap_count'        => $cf_scrap_count ??0,//报废待处理
		];

		return $this->success('success',$data);
    }
    
        /**
	 * @param          string  variable        explain
	 * @author         LiuS
	 * @version        2022-02-08 10:10:15
	 * @return         json
	 */
    public function getJoinLog(){
        $ymd_time             		= $this->request->param('ymdtime',null);
        $com_time                   = $ymd_time ?? date("Y-m-d");
        $td_date                    = strtotime($com_time);
        $te_date                    = $td_date + 24*3600;
        $pocart_data                = Db::name('pocart')->alias('a')->join('Production b','a.proid = b.pid','left')->where('a.cstatus<4')->field('a.*,b.pname')->select()->toArray();
        //->where('a.add_time > '.$td_date)->where('a.add_time < '.$te_date) 测试数据时开启显示效果，正式加上
        foreach ($pocart_data as $key => $value){
            $pocart_data[$key]['continue_pro'] = empty($value['p_pcid']) ? [] : Db::name('pocart')->alias('a')->join('Production b','a.proid = b.pid','left')->where('a.pcid',$value['p_pcid'])->field('a.*,b.pname')->find();
            
        }
        return $this->success('success',$pocart_data);
    }
    /**
	 * @param          string  variable        explain
	 * @author         LiuS
	 * @version        2022-02-02 10:10:15
	 * @return         json
	 */
    public function getProductionIndexData(){
        $ym_time             		    = $this->request->param('ymtime',null); 
        $com_time                       = $ym_time ?? date("Y-m");
        // $com_time                   = date("Y-m-0");
        $tm_date                        = strtotime($com_time);
        $me_date                        = empty($ym_time) ? time() : strtotime($ym_time) ;
        $abnormal_max                   = 0;
        $abnormal_min                   = 0;
        $detained_max                   = 0;
        $detained_min                   = 0;
        
// be detained
        $pro_list                       = Db::name('Production')->where('status',1)->order('sort desc')->field('pid,pname,is_yes,is_choice')->select()->toArray();
        foreach ($pro_list as $key => $value){
            $pro_list[$key]['sum_num']  = Db::name('pocart')->where('proid',$value['pid'])->where('add_time > '.$tm_date)->where('add_time < '.$me_date)->where('cstatus<4')->sum('sum_num');
            $pro_list[$key]['com_num']  = Db::name('pocart')->where('proid',$value['pid'])->where('add_time > '.$tm_date)->where('add_time < '.$me_date)->where('cstatus<4')->sum('com_num');
            $pro_list[$key]['def_num']  = Db::name('pocart')->where('proid',$value['pid'])->where('add_time > '.$tm_date)->where('add_time < '.$me_date)->where('cstatus<4')->sum('defective');
            $temp_detained              = $pro_list[$key]['sum_num'] - $pro_list[$key]['com_num'];
            $temp_abnormal              = $pro_list[$key]['sum_num'] - $pro_list[$key]['def_num'];
            $abnormal_max               = $abnormal_max > $temp_abnormal ? $abnormal_max : $temp_abnormal ;
            $abnormal_min               = $abnormal_min < $temp_abnormal ? $abnormal_min : $temp_abnormal ;
            $detained_max               = $abnormal_max > $temp_detained ? $detained_min : $temp_detained ;
            $detained_min               = $abnormal_min < $temp_detained ? $detained_min : $temp_detained ;
        }
        $pro_list['data']               = [
            'abnormal_max'             => $abnormal_max,//异常
            'abnormal_min'             => $abnormal_min,//
            'detained_max'             => $detained_max,//
            'detained_min'             => $detained_min,//滞留 
            ];
        return $this->success('success',$pro_list);
    }
    public function getOrderList(){
        $ym_time             		= $this->request->param('time',null);//时间
        $status                		= $this->request->param('status',4);//数据4全部3完成2加工中1待加工0待确认 
        $com_time                   = $ym_time ?? date("Y-m");

        $tm_date                    = strtotime($com_time);
        $me_date                    = empty($ym_time) ? time() : strtotime($ym_time) ;
        
        $Porder                     = Db::name('Porder')->where('add_time > '.$tm_date)->where('add_time < '.$me_date);
        
        if(!in_array($status,[0,1,2,3])) {
            $Porder                 = $Porder->where('status<4');
        }else{
            $Porder                 = $Porder->where('status',$status);
        }
        $proder_data                = $Porder->select();
        
        $data 						= [
            'date'                  => $com_time,//时间
			'data'	            	=> $proder_data,//数据
		];

		return $this->success('success',$data);
    }

    /**
	 * @param          string  variable        explain
	 * @author         LiuS
	 * @version        2022-02-08 10:10:15
	 * @return         json
	 */
    public function getWeekData(){
        $week             	        = $this->request->param('week',1);//0表示上周1本周
        $timestamp                  = time();
        $week_start                 = strtotime(date('Y-m-d', strtotime("this week Monday", $timestamp)));
        
        $week_end                   = empty($week) ? $week_start :  $week_start + 7*24*3600;
        $week_start                 = !empty($week) ? $week_start :  $week_start - 7*24*3600;
        $data                       = [];
        $i                          = 0;
        $str_time                   = $week_start;
        $end_time                   = $week_start + 24*3600;
        do {

            if($i>0){
                $str_time           = $end_time;
                $end_time           = $end_time + 24*3600;
            }
            $temp['ends']           =  $end_time;
            $temp['week']           =  date('w',$str_time);
            if($timestamp>$str_time)
                $temp['com_number'] = Db::name('Porder')->where('add_time > '.$str_time)->where('add_time < '.$end_time)->where('status',3)->sum('com_number');
            else{
                $temp['com_number'] = 0;
            }
                
            $data[$i]               =  $temp;    
            $i++;
        } while ($i<7);
        return $this->success('success',$data);
    }
    
    /**
	 * @param          string  variable        explain
	 * @author         LiuS
	 * @version        2022-02-08 10:10:15
	 * @return         json
	 */
    public function getMonthMData(){
        // $com_time                   = $ymd_time ?? date("Y-m-d");
        // $month                      = date("Y-m");
        $end_time           = time();
        $data               = [];
        $i                  = 0;
 
        do {
            $timestamp      = strtotime(date("Y-m-01"));
            // $end_time = $str_time ?? $end_time;
         
            if($i>0){
                $end_time   = $str_time;
                $temp_date  = date('Y-m-01',strtotime(" -$i month",$timestamp));
                $str_time   = strtotime($temp_date);
            }else{
                $str_time   = $timestamp;
                $end_time   = time();
            }
            $temp['month']  =  date('m',$str_time);
            $temp['year']   =  date('y',$str_time);
            $temp['com_number'] = Db::name('Porder')->where('add_time > '.$str_time)->where('add_time < '.$end_time)->where('status',3)->sum('com_number');
            $data[$i]       =  $temp;    
            $i++;
        } while ($i<12);
        return $this->success('success',$data);
        
    }

	// --------------------------------------------------------------------
	/**
	 * @access		public 
	 * @example		公共参数获取(option配置)
	 * @param		string 	variable		explain
	 * @author		LiuS
	 * @version		2020-11-02 16:16:24
	 * @return		json
	 */
	public function getCommonParameters()
	{

		$option_list					= DB::name('Option')->get(['contact_us_mobile','customer_service_mobile','complaint_suggestion_mobile','company_profile','qq','company_address','company_name','company_image','crm_name']);

		if(!empty($option_list['company_image'])){
			$option_list['company_image']				= AssetModel::getHttpImage($option_list['company_image']);

		}

		$data							= [
			'option_list'				=> $option_list,
		];

		return $this->success('success',$data);
	}


	/**
	 * @access         public
	 * @example        手机登录
	 * @param          string  variable        explain
	 * @author         LiuS
	 * @version        2021-09-20 10:55:19
	 * @return         json
	 */
	public function mobileLogin(){
		$mobile       				= $this->request->post('mobile','');      //手机号
		$password       			= $this->request->post('password','');      //密码

		if(empty($mobile))				return $this->error('请输入手机号!');
		if(empty($password))			return $this->error('请输入密码!');


		$user 						= UserModel::where('mobile',$mobile)->find();
		if(empty($user))				return $this->error(['code' => 202, 'msg' => '帐号/密码错误!']);

		$user_pass					= $user['user_pass'];

		if(cmf_password($password) != $user_pass ){
			return $this->error(['code' => 202, 'msg' => '帐号/密码错误!']);
		}

		if($user['user_status'] != 1){
			return $this->error(['code' => 202, 'msg' => '该帐号已被禁用!']);

		}



		$result['last_login_ip']					= get_client_ip(0, true);
		$result['last_login_time']					= time();
		$token										= cmf_generate_user_token($user["id"], $this->deviceType);
		
		$data 					= $user;
		unset($data['user_pass']);
		$data['token']			= $token;

		UserModel::where('id', $user["id"])->update($result);

		return $this->success('LOGIN_SUCCESS', $data);

	}


    /**
     * [forgetPassword 修改密码]
     * LiuS
     * @return [type] [description]
     */
    public function forgetPassword(){
        $mobile        			= $this->request->post('mobile','');      		//手机号
        $oldPassword            = $this->request->post('oldPassword','');       //旧密码

        $newPassword        	= $this->request->post('newPassword','');      //新密码
        $passwordCopy       	= $this->request->post('passwordCopy','');     //确认密码     



        if(empty($mobile))    		return $this->error('手机号码不能为空!');   //手机号码不能为空

        $userInfo           	= UserModel::where('mobile', $mobile)->find();

        if(empty($userInfo))        return $this->error('该手机号码未注册!');		//该手机号码未注册

        if($userInfo['user_pass'] != cmf_password($oldPassword)){
        	return $this->error('密码错误!');
        }

        if($newPassword != $passwordCopy){
			return $this->error('两次密码输入不一样!');
        }

        $user_pass								= cmf_password($newPassword);

       	UserModel::where('id', $userInfo['id'])->data(['user_pass' => $user_pass])->update();

		$token									= cmf_generate_user_token($userInfo["id"], $this->deviceType);
		
		$data 									= $userInfo;
		unset($data['user_pass']);
		$data['token']							= $token;
		return $this->success('LOGIN_SUCCESS', $data);

    }
    
  
    

}