<?php
// +----------------------------------------------------------------------
// | ThinkCMF [ WE CAN DO IT MORE SIMPLE ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013-2019 http://www.thinkcmf.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 小夏 < 449134904@qq.com>
// +----------------------------------------------------------------------
namespace app\admin\controller;

use cmf\controller\AdminBaseController;
use think\Db;
use app\admin\model\Menu;

class MainController extends AdminBaseController
{

    /**
     *  后台欢迎页
     */
    public function index()
    {
        $dashboardWidgets = [];
        $widgets          = cmf_get_option('admin_dashboard_widgets');
        
        //7日内逾期
        $week_time          = time() + 7 * 24 *3600;//strtotime('Y-m-d',time());
        // var_dump($week_time); var_dump($week_time+ 7 * 24 *3600);
        $order_list         = Db::name('Porder')->where('status','in','1,2')->where('com_time','<=',$week_time)->order('com_time ASC')->select();
        // var_dump($order_list);
        $this->assign('order_list',$order_list);
        //待确认订单
        $nocom_list         = Db::name('Porder')->where('status',0)->order('add_time desc')->select();
        // var_dump($order_list);
        $this->assign('nocom_list',$nocom_list);
        //总数统计
        $tatol_product      = Db::name('Porder')->where('status','<',4)->where('status','>',0)->sum('com_number');
        //完成数统计
        $tatol_com_product      = Db::name('Porder')->where('status',3)->sum('com_number');
        //待完成数统计
        $tatol_no_product      = $tatol_product - $tatol_com_product;//Db::name('Porder')->where('status',3)->sum('com_number');
        //损耗数统计
        $tatol_sh_product      = Db::name('Porder')->where('status',3)->sum('number') - $tatol_com_product;
        
        $this->assign('data',['tatol_product'=>$tatol_product,'tatol_com_product'=>$tatol_com_product,'tatol_no_product'=>$tatol_no_product,'tatol_sh_product'=>$tatol_sh_product]);
        
        //6个月内产品完成柱状图
        for($i=0;$i<6;$i++){
            if($i>0){
                $end_time                           = $str_time ?? strtotime(date('Y-m-1 00:00:00'));
                $str_time                           = strtotime('-1 month ',$str_time);
            }else{
                $str_time                                   = strtotime(date('Y-m-1 00:00:00'));//                                      =
                $end_time                                   = time();  
            }
            
            $tmp['date']                                = date('Y/m',$str_time);
            //总订单数 - 订单件数
            $tmp['total_number']                        = Db::name('porder')->alias('o')->where('o.status < 4')->where('o.add_time >= '.$str_time)->where('o.add_time <'.$end_time)->sum('o.number');
            //订单交货件数
            $tmp['total_comnumber']                     = Db::name('porder')->alias('o')->where('o.status < 4')->where('o.add_time >= '.$str_time)->where('o.add_time <'.$end_time)->sum('o.com_number');
            // //完成件数
            // $tmp['total_com']                           = Db::name('porder')->alias('o')->join('pocart c','c.poid=o.oid','left')->where('o.status < 4')->where('o.add_time >= '.$str_time)->where('o.add_time <'.$end_time)->sum('c.com_num');
            $list[]         = $tmp; 
        }
        $this->assign('list',$list);
        // $defaultDashboardWidgets = [
        //     '_SystemCmfHub'           => ['name' => 'CmfHub', 'is_system' => 1],
        //     '_SystemCmfDocuments'     => ['name' => 'CmfDocuments', 'is_system' => 1],
        //     '_SystemMainContributors' => ['name' => 'MainContributors', 'is_system' => 1],
        //     '_SystemContributors'     => ['name' => 'Contributors', 'is_system' => 1],
        //     '_SystemCustom1'          => ['name' => 'Custom1', 'is_system' => 1],
        //     '_SystemCustom2'          => ['name' => 'Custom2', 'is_system' => 1],
        //     '_SystemCustom3'          => ['name' => 'Custom3', 'is_system' => 1],
        //     '_SystemCustom4'          => ['name' => 'Custom4', 'is_system' => 1],
        //     '_SystemCustom5'          => ['name' => 'Custom5', 'is_system' => 1],
        // ];

        // if (empty($widgets)) {
        //     $dashboardWidgets = $defaultDashboardWidgets;
        // } else {
        //     foreach ($widgets as $widget) {
        //         if ($widget['is_system']) {
        //             $dashboardWidgets['_System' . $widget['name']] = ['name' => $widget['name'], 'is_system' => 1];
        //         } else {
        //             $dashboardWidgets[$widget['name']] = ['name' => $widget['name'], 'is_system' => 0];
        //         }
        //     }

        //     foreach ($defaultDashboardWidgets as $widgetName => $widget) {
        //         $dashboardWidgets[$widgetName] = $widget;
        //     }


        // }

        // $dashboardWidgetPlugins = [];

        // $hookResults = hook('admin_dashboard');

        // if (!empty($hookResults)) {
        //     foreach ($hookResults as $hookResult) {
        //         if (isset($hookResult['width']) && isset($hookResult['view']) && isset($hookResult['plugin'])) { //验证插件返回合法性
        //             $dashboardWidgetPlugins[$hookResult['plugin']] = $hookResult;
        //             if (!isset($dashboardWidgets[$hookResult['plugin']])) {
        //                 $dashboardWidgets[$hookResult['plugin']] = ['name' => $hookResult['plugin'], 'is_system' => 0];
        //             }
        //         }
        //     }
        // }

        // $smtpSetting = cmf_get_option('smtp_setting');

        // $this->assign('dashboard_widgets', $dashboardWidgets);
        // $this->assign('dashboard_widget_plugins', $dashboardWidgetPlugins);
        // $this->assign('has_smtp_setting', empty($smtpSetting) ? false : true);

        return $this->fetch();
    }

    public function dashboardWidget()
    {
        $dashboardWidgets = [];
        $widgets          = $this->request->param('widgets/a');
        if (!empty($widgets)) {
            foreach ($widgets as $widget) {
                if ($widget['is_system']) {
                    array_push($dashboardWidgets, ['name' => $widget['name'], 'is_system' => 1]);
                } else {
                    array_push($dashboardWidgets, ['name' => $widget['name'], 'is_system' => 0]);
                }
            }
        }

        cmf_set_option('admin_dashboard_widgets', $dashboardWidgets, true);

        $this->success('更新成功!');

    }

}
