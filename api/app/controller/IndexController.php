<?php

/**
 * @Description		首页-工作台
 * @Author			LiuS
 * @Date			2021-11-02 15:07:46
 * @LastEditTime	2022-02-02 15:07:46
 */

namespace api\app\controller;

use api\app\model\UserModel;
use think\Db;
use think\facade\Cache;

class IndexController extends RestBaseController
{
	public function __construct(){
		parent::__construct();
		$this->_initUser();
	}
    /**
	 * @param          string  variable        explain
	 * @author         LiuS
	 * @version        2022-01-23 10:20:19
	 * @return         json
	 */
    public function getHead(){
        $com_time                   = date("Y-m-d");
        $td_date                    = strtotime($com_time);
        $yd_date                    = $td_date - 24*3600;
        $te_date                    = $td_date + 24*3600;
        
        $td_scrap                   = Db::name('Scrap')->where('add_time >'.$td_date)->where('add_time <'.$te_date)->where('status',1)->sum('num');
        // $yd_scrap                   = Db::name('Scrap')->where('add_time >'.$yd_date)->where('add_time <'.$td_date)->where('status',1)->sum('num');
        $yd_completed               = Db::name('Pocart')->where('completion_time >'.$yd_date)->where('completion_time <'.$te_date)->where('cstatus',1)->sum('com_num');
        $completed_count            = Db::name('Pocart')->where('completion_time >'.$td_date)->where('completion_time <'.$te_date)->where('cstatus',1)->sum('com_num');
        $confirmed_count            = Db::name('Porder')->where('status',1)->sum('com_number');
        $order_count                = Db::name('Porder')->where('status','in','1,2,3')->sum('com_number');
        $exceed_order               = Db::name('Porder')->where('com_time >='.$td_date)->where('status','in','1,2,3')->select();
        $confirmed_order            = Db::name('Porder')->where('com_time >='.$td_date)->where('status',0)->count();
        $cf_scrap_count             = Db::name('Scrap')->where('status',0)->sum('num');
        
        
        
        $data 						= [
			'td_scrap'				=> $td_scrap??0,//今日报废
			'yd_completed'			=> $yd_completed??0,//昨日完成
			'completed_count'		=> $completed_count??0,//今日完成
			'confirmed_count'		=> $confirmed_count??0,//待产量
			'cf_scrap_count'        => $cf_scrap_count ??0,//报废待处理
			'confirmed_order'       => $confirmed_order ??0,//校验待处理
			'exceed_order'          => $exceed_order ??0,//超期
			'proorder_count'        => $proorder_count ??0,//下单数量
		];

		return $this->success('success',$data);
    }
    
    /**
	 * @param          string  variable        explain
	 * @author         LiuS
	 * @version        2022-01-23 10:20:19
	 * @return         json
	 */
    public function getOrderHead(){
        $ym_time             		= $this->request->param('time',null); 
        $com_time                   = $ym_time ?? date("Y-m");

        $tm_date                    = strtotime($com_time);
        $me_date                    = empty($ym_time) ? time() : strtotime($ym_time) ;
        
        $all_order_count            = Db::name('Porder')->where('add_time > '.$tm_date)->where('add_time < '.$me_date)->where('status<4')->count();
        $completed_count            = Db::name('Porder')->where('add_time > '.$tm_date)->where('add_time < '.$me_date)->where('status',3)->count();
        $makeing_count              = Db::name('Porder')->where('add_time > '.$tm_date)->where('add_time < '.$me_date)->where('status',2)->count();
        $confirmed_count            = Db::name('Porder')->where('add_time > '.$tm_date)->where('add_time < '.$me_date)->where('status',1)->count();
        $exceed_order               = Db::name('Porder')->where('com_time >='.$tm_date)->where('status','in','1,2,3')->select();
        
        $data 						= [
            'all_order_count'       => $all_order_count??0,//总订单
			'completed_count'		=> $completed_count??0,//完成订单
			'makeing_count'		    => $makeing_count??0,//产中订单
			'confirmed_count'		=> $confirmed_count??0,//待产量订单
			'exceed_order'          => $exceed_order ??[],//超期订单
		];

		return $this->success('success',$data);
    }
    
    /**
	 * @param          string  variable        explain
	 * @author         LiuS
	 * @version        2022-01-23 10:20:19
	 * @return         json
	 */
    public function getOrderScrap(){
        $com_time                   = date("Y-m-d");
        $td_date                    = strtotime($com_time);
        //暂时不做时间处理
        $cf_scrap_count             = Db::name('Scrap')->where('status',0)->sum('num');
        $scrap_data                 = Db::name('Scrap')->alias('a')->join('Porder b','a.poid = b.oid','left')->join('Pocart c','a.pcid = c.pcid','left')->where('a.status',0)->select();
        return $this->success('success',['scrap'=>$scrap_data]);
    }
    
    /**
	 * @param          string  variable        explain
	 * @author         LiuS
	 * @version        2022-01-23 10:20:19
	 * @return         json
	 */
    public function getOrderList(){
        $ym_time             		= $this->request->param('time',null);//时间
        $status                		= $this->request->param('status',4);//数据4全部3完成2加工中1待加工0待确认 5超期
        $com_time                   = $ym_time ?? date("Y-m");

        $tm_date                    = strtotime($com_time);
        $me_date                    = empty($ym_time) ? time() : strtotime($ym_time) ;
        
        $Porder                     = Db::name('Porder')->where('add_time > '.$tm_date)->where('add_time < '.$me_date);
        // $exceed_order               = Db::name('Porder')->where('com_time >='.$tm_date)->where('status','in','1,2,3')->select();
        if(!in_array($status,[0,1,2,3])) {
            $Porder                 = $Porder->where('status<4');
        }else if($status == 5){
            $Porder                 = $Porder->where('com_time >='.$tm_date)->where('status','in','1,2,3');
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
	 * @version        2022-02-02 10:10:15
	 * @return         json
	 */
    public function getProductionData(){
        $ym_time             		    = $this->request->param('ymtime',null); 
        $com_time                       = $ym_time ?? date("Y-m");
        // $com_time                   = date("Y-m-0");
        $tm_date                        = strtotime($com_time);
        $me_date                        = empty($ym_time) ? time() : strtotime($ym_time) ;
        $abnormal_max                   = 0;
        $abnormal_min                   = 0;
        $detained_max                   = 0;
        $detained_min                   = 0;
        
        $plist = Db::name('pocart')->where('add_time > '.$tm_date)->where('add_time < '.$me_date)->where('cstatus<4')->field('proid as pid,
            remark as pname,
            sum(sum_num) as sum_num,
            sum(number) as number,
            sum(com_num) as com_num,
            sum(defective) as def_num')->order('poid desc,order asc')->group('proid')->select()->toArray();

        foreach ($plist as $key => $value){
            $plist[$key]['undo']=(int)($value['number']-$value['sum_num']-$value['def_num']);
            $temp_detained              = $plist[$key]['sum_num'] - $plist[$key]['com_num'];
            $temp_abnormal              = $plist[$key]['sum_num'] - $plist[$key]['def_num'];
            $abnormal_max               = $abnormal_max > $temp_abnormal ? $abnormal_max : $temp_abnormal ;
            $abnormal_min               = $abnormal_min < $temp_abnormal ? $abnormal_min : $temp_abnormal ;
            $detained_max               = $abnormal_max > $temp_detained ? $detained_min : $temp_detained ;
            $detained_min               = $abnormal_min < $temp_detained ? $detained_min : $temp_detained ;
        }
        $plist['data']               = [
            'abnormal_max'              => $abnormal_max,//异常
            'abnormal_min'              => $abnormal_min,//
            'detained_max'              => $detained_max,//
            'detained_min'              => $detained_min,//滞留 
        ];
        return $this->success('success',$plist);
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
        // $pocart_data                = Db::name('pocart')->alias('a')->join('Production b','a.proid = b.pid','left')->where('a.add_time > '.$td_date)->where('a.add_time < '.$te_date)->where('a.cstatus<4')->field('a.*,b.pname')->select()->toArray();
        $pocart_data                = Db::name('pocart')->alias('a')->join('Production b','a.proid = b.pid','left')->where('a.cstatus<4')->field('a.*,b.pname')
            ->select()->toArray();
        // ->where('a.add_time > '.$td_date)->where('a.add_time < '.$te_date)->select()->toArray();
        // 测试数据时开启显示效果，正式加上
        
        foreach ($pocart_data as $key => $value){
            $pocart_data[$key]['continue_pro'] = empty($value['p_pcid']) ? [] : Db::name('pocart')->alias('a')->join('Production b','a.proid = b.pid','left')->where('a.pcid',$value['p_pcid'])->field('a.*,b.pname')->find();
            
        }
        return $this->success('success',$pocart_data);
    }
    
    /**
	 * @param          string  variable        explain
	 * @author         LiuS
	 * @version        2022-02-08 10:10:15
	 * @return         json
	 */
    public function getMonthMData(){

        $end_time               = time();
        $data                   = [];
        $i                      = 0;
 
        do {
            $timestamp          = strtotime(date("Y-m-01"));
            // $end_time = $str_time ?? $end_time;
         
            if($i>0){
                $end_time       = $str_time;
                $temp_date      = date('Y-m-01',strtotime(" -$i month",$timestamp));
                $str_time       = strtotime($temp_date);
            }else{
                $str_time       = $timestamp;
                $end_time       = time();
            }
            $temp['month']      =  date('m',$str_time);
            $temp['year']       =  date('y',$str_time);
            $temp['com_number'] = Db::name('Porder')->where('add_time > '.$str_time)->where('add_time < '.$end_time)->where('status',3)->sum('com_number');
            $data[$i]           =  $temp;    
            $i++;
        } while ($i<12);
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
}