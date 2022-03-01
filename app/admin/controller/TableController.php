<?php

/**
 * @Description		生产工艺
 * @Author			LiuS
 * @Date			2021-11-02 15:14:52
 * @LastEditTime	2022-02-01 16:14:50
 */

namespace app\admin\controller;

use cmf\controller\AdminBaseController;
use think\Db;
use PHPExcel_IOFactory;
use PHPExcel_Style_Fill;
use PHPExcel;
use PHPExcel_Style_Alignment;


class TableController extends AdminBaseController
{
    
    public function index()
    {
        return $this->fetch(':index');
    }
    
    public function aaa(){
        $excel = new \PHPExcel();
        dump($excel);
    }
    
    public function excel()
    {
        $str_time             				    = $this->request->param('str_time','');      //开始时间
        $end_time             				    = $this->request->param('end_time','');      //结束时间
        if($end_time)           $end_time       = strtotime($end_time);
        else                    $end_time       = time();
        if($str_time)           $str_time       = strtotime($str_time);
        else                    $str_time       = strtotime(date('Y-m-1 00:00:00'));//strtotime('-1 month ',$str_time);
        // 底部标题  include "../vendor/PHPExcel/PHPExcel.php";
        $user_data = Db::name('user')->alias('a')->where('a.role',0)->where('a.user_type',2)->where('a.user_status',1)->select();
// var_dump($user_data);exit;
        $date = date("Y-m-d Hi",time());
        $fileName = $date."下载.xls";// date('Y-m-d')."下载.xls";
        $fileName = urlencode($fileName);
        
        $styleThinBlackBorderOutline = array(
            'borders' => array(
                'allborders' => array( //设置全部边框
                    'style' => \PHPExcel_Style_Border::BORDER_THIN //粗的是thick
                ),
            ),
        );
        
        $objPHPExcel = new PHPExcel();
       //设置表头
        $tem_key = "A"; 
        $title =  '车间加工'.date('m').'月份明细';
        $i1 = 0;
        foreach($user_data as $key1 =>$values){
            $user_data = Db::name('porder')->alias('a')->where('a.uid',$values['id'])->where('a.status','in','1,2,3')->select();
            if(empty($user_data)) continue;
           	if($key1 !== 0) $objPHPExcel->createSheet();
            $objPHPExcel->setactivesheetindex($i1);
            
            //一二行抬头
            $objPHPExcel->getActiveSheet()->mergeCells('A1:B2');
            $objPHPExcel->getActiveSheet()->setCellValue('A1', $title);
            $objPHPExcel->getActiveSheet()->mergeCells('C1:C2');
            $objPHPExcel->getActiveSheet()->setCellValue('C1', '单号');
            $objPHPExcel->getActiveSheet()->mergeCells('D1:F2');
            $objPHPExcel->getActiveSheet()->setCellValue('D1', '规格');
            $objPHPExcel->getActiveSheet()->mergeCells('G1:K2');
            $objPHPExcel->getActiveSheet()->setCellValue('G1', '覆膜');
            // 设置垂直居中
            $objPHPExcel->getActiveSheet()->getStyle('A1:AQ4')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            // 设置水平居中
            $objPHPExcel->getActiveSheet()->getStyle('A1:AQ4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->mergeCells('L1:P1');
            $objPHPExcel->getActiveSheet()->setCellValue('L1','裱坑');
            $objPHPExcel->getActiveSheet()->mergeCells('L2:P2');//双E坑0.24最低0.2/张，开机150，三裱一0.4.最低0.3/张，开机300				
            $objPHPExcel->getActiveSheet()->setCellValue('L2', '双E坑0.24最低0.2/张，开机150，三裱一0.4.最低0.3/张，开机300');
            $objPHPExcel->getActiveSheet()->mergeCells('Q1:S2');
            $objPHPExcel->getActiveSheet()->setCellValue('Q1', '烫金');
            $objPHPExcel->getActiveSheet()->mergeCells('T1:X2');
            $objPHPExcel->getActiveSheet()->setCellValue('T1', '击凸');
            $objPHPExcel->getActiveSheet()->mergeCells('AD1:AF2');
            $objPHPExcel->getActiveSheet()->setCellValue('AD1', '打包装箱');
            $objPHPExcel->getActiveSheet()->mergeCells('AG1:AI2');
            $objPHPExcel->getActiveSheet()->setCellValue('AG1', '机器粘');
            $objPHPExcel->getActiveSheet()->mergeCells('AJ1:AK2');
            $objPHPExcel->getActiveSheet()->setCellValue('AJ1', '手工粘');
            $objPHPExcel->getActiveSheet()->mergeCells('AL1:AM2');
            $objPHPExcel->getActiveSheet()->setCellValue('AL1', '码板');
            $objPHPExcel->getActiveSheet()->getStyle('A1:K2')->getFont()->setSize(22);
            $objPHPExcel->getActiveSheet()->getStyle('Q1:AQ2')->getFont()->setSize(22);
            // $objPHPExcel->getActiveSheet()->mergeCells('AN1:AQ2');
            // $worksheet->getStyle('A1')->applyFromArray($styleArray)->getFont()->setSize(28);
            // $worksheet->getStyle('A2:E2')->applyFromArray($styleArray)->getFont()->setSize(14);
            $objPHPExcel->getActiveSheet()->getStyle('A1:AQ2')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
            $objPHPExcel->getActiveSheet()->getStyle('A1:AQ2')->getFill()->getStartColor()->setRGB('76933C');
            //三四行抬头
            $objPHPExcel->getActiveSheet()->mergeCells('A3:A4');
            $objPHPExcel->getActiveSheet()->setCellValue('A3', '序号');
            $objPHPExcel->getActiveSheet()->mergeCells('B3:B4');
            $objPHPExcel->getActiveSheet()->setCellValue('B3', '货物名称');
            $objPHPExcel->getActiveSheet()->mergeCells('C3:C4');
            $objPHPExcel->getActiveSheet()->setCellValue('C3', '内部订单号');
            $objPHPExcel->getActiveSheet()->mergeCells('D3:F3');//尺寸
            $objPHPExcel->getActiveSheet()->setCellValue('D3','尺寸');
            $objPHPExcel->getActiveSheet()->setCellValue('E3', '尺寸');
            $objPHPExcel->getActiveSheet()->setCellValue('D4', '长');
            $objPHPExcel->getActiveSheet()->setCellValue('E4', '宽');
            $objPHPExcel->getActiveSheet()->setCellValue('F4', '英寸');
            $objPHPExcel->getActiveSheet()->mergeCells('G3:K3');
            // $objPHPExcel->getActiveSheet()->setCellValue('G3','尺寸');
            $objPHPExcel->getActiveSheet()->setCellValue('G3', '过胶开机费120，单张最低价格0.13/张');
            $objPHPExcel->getActiveSheet()->setCellValue('G4', '数量');
            $objPHPExcel->getActiveSheet()->setCellValue('H4', '光胶价0.3');
            $objPHPExcel->getActiveSheet()->setCellValue('I4', '哑胶价0.4');
            $objPHPExcel->getActiveSheet()->setCellValue('J4', '单价（保留2位）');
            $objPHPExcel->getActiveSheet()->setCellValue('K4', '金额');
            $objPHPExcel->getActiveSheet()->mergeCells('L3:P3');
            $objPHPExcel->getActiveSheet()->setCellValue('L3', 'E坑0.16最低0.1/张，开机120元F坑，粗坑0.18最低0.12/张，开机150');
            $objPHPExcel->getActiveSheet()->setCellValue('L4', '数量');
            $objPHPExcel->getActiveSheet()->setCellValue('M4', '裱坑0.16');
            $objPHPExcel->getActiveSheet()->setCellValue('N4', '备注');
            $objPHPExcel->getActiveSheet()->setCellValue('O4', '单价（保留2位）');
            $objPHPExcel->getActiveSheet()->setCellValue('P4', '金额');
            $objPHPExcel->getActiveSheet()->mergeCells('Q3:S3');//
            $objPHPExcel->getActiveSheet()->setCellValue('Q3', '烫金开机费150');
            $objPHPExcel->getActiveSheet()->setCellValue('Q4', '数量');
            $objPHPExcel->getActiveSheet()->setCellValue('R4', '单价');
            $objPHPExcel->getActiveSheet()->setCellValue('S4', '金额');
            $objPHPExcel->getActiveSheet()->mergeCells('T3:U3');//模数
            $objPHPExcel->getActiveSheet()->setCellValue('T3', '模数');
            $objPHPExcel->getActiveSheet()->setCellValue('T4', '模数');
            $objPHPExcel->getActiveSheet()->setCellValue('U4', '金额');
            $objPHPExcel->getActiveSheet()->mergeCells('V3:X3');//击凸开机费150
            $objPHPExcel->getActiveSheet()->setCellValue('V3', '击凸开机费150');
            $objPHPExcel->getActiveSheet()->setCellValue('V4', '数量');
            $objPHPExcel->getActiveSheet()->setCellValue('W4', '单价');
            $objPHPExcel->getActiveSheet()->setCellValue('X4', '金额');
            $objPHPExcel->getActiveSheet()->mergeCells('Y3:Z3');//模数
            $objPHPExcel->getActiveSheet()->setCellValue('Y3', '模数');
            $objPHPExcel->getActiveSheet()->setCellValue('Y4', '模数');
            $objPHPExcel->getActiveSheet()->setCellValue('Z4', '金额');
            $objPHPExcel->getActiveSheet()->mergeCells('AA3:AC3');//啤开机费130
            $objPHPExcel->getActiveSheet()->setCellValue('AA3', '啤开机费130');
            $objPHPExcel->getActiveSheet()->setCellValue('AA4', '数量');
            $objPHPExcel->getActiveSheet()->setCellValue('AB4', '单价');
            $objPHPExcel->getActiveSheet()->setCellValue('AC4', '金额');
            $objPHPExcel->getActiveSheet()->mergeCells('AD3:AF3');//打包码板装箱
            $objPHPExcel->getActiveSheet()->setCellValue('AD3', '打包码板装箱');
            $objPHPExcel->getActiveSheet()->setCellValue('AD4', '数量');
            $objPHPExcel->getActiveSheet()->setCellValue('AE4', '单价');
            $objPHPExcel->getActiveSheet()->setCellValue('AF4', '金额');
            $objPHPExcel->getActiveSheet()->mergeCells('AG3:AN3');//机器粘开机费120手工起步50
            $objPHPExcel->getActiveSheet()->setCellValue('AG3', '机器粘开机费120手工起步50');
            $objPHPExcel->getActiveSheet()->setCellValue('AG4', '数量');
            $objPHPExcel->getActiveSheet()->setCellValue('AH4', '单价');
            $objPHPExcel->getActiveSheet()->setCellValue('AI4', '金额');
            $objPHPExcel->getActiveSheet()->setCellValue('AJ4', '数量');
            $objPHPExcel->getActiveSheet()->setCellValue('AK4', '单价');
            $objPHPExcel->getActiveSheet()->setCellValue('AL4', '金额');
            $objPHPExcel->getActiveSheet()->setCellValue('AM4', '单价');
            $objPHPExcel->getActiveSheet()->setCellValue('AN4', '金额');
            $objPHPExcel->getActiveSheet()->mergeCells('AO3:AO4');//合计金额
            $objPHPExcel->getActiveSheet()->setCellValue('AO3', '合计金额');
            $objPHPExcel->getActiveSheet()->mergeCells('AP3:AQ4');//备注
            $objPHPExcel->getActiveSheet()->setCellValue('AP3', '备注');
            $objPHPExcel->getActiveSheet()->getStyle('A3:AQ4')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
            $objPHPExcel->getActiveSheet()->getStyle('A3:AQ4')->getFill()->getStartColor()->setRGB('4BACC6');
            
            $objPHPExcel->getActiveSheet($i1)->setTitle($values['signature']);
            
            $j = 5;
            foreach ($user_data as $key =>$value){
                
                $tmp_data = Db::name('pocart')->alias('pc')->join('production p','pc.proid = p.pid','left')->where('pc.cstatus',1)->column('pc.sum_num psumber,pc.com_num pcumber,pc.proid,p.parentid,p.pname,p.pprice,pc.remark','pc.proid');
                // var_dump($tmp_data);
                // var_dump('/n/n');//exit;
                // $tmp = count($tmp_data) + 5;
                // $objPHPExcel->getActiveSheet()->getStyle('A1:AQ'.$tmp)->applyFromArray($styleThinBlackBorderOutline);
                // unset($tmp);
                $objPHPExcel->getActiveSheet()->setCellValue('A'.$j, $key+1);
                $objPHPExcel->getActiveSheet()->setCellValue('B'.$j, $value['name']);
                $objPHPExcel->getActiveSheet()->setCellValue('C'.$j, ' '.$value['order_no']);
                $objPHPExcel->getActiveSheet()->setCellValue('D'.$j, $value['length']);
                $objPHPExcel->getActiveSheet()->setCellValue('E'.$j, $value['width']);
                $objPHPExcel->getActiveSheet()->setCellValue('F'.$j, '=D'.$j.'/25.4*(E'.$j.'/25.4)/1000');
                $objPHPExcel->getActiveSheet()->setCellValue('K'.$j, '0.00');
                $objPHPExcel->getActiveSheet()->setCellValue('P'.$j, '0.00');
                $objPHPExcel->getActiveSheet()->setCellValue('S'.$j, '0.00');
                $objPHPExcel->getActiveSheet()->setCellValue('X'.$j, '0.00');
                $objPHPExcel->getActiveSheet()->setCellValue('AC'.$j, '0.00');
                $objPHPExcel->getActiveSheet()->setCellValue('AF'.$j, '0.00');
                $objPHPExcel->getActiveSheet()->setCellValue('AI'.$j, '0.00');
                $objPHPExcel->getActiveSheet()->setCellValue('AN'.$j, '0.00');
                $objPHPExcel->getActiveSheet()->setCellValue('AO'.$j, '0.00');
                // var_dump($tmp_data);
                if(!empty($tmp_data['53']))  {
                    // var_dump($tmp_data['53']);var_dump($tmp_data['53']['pcumber']);var_dump($tmp_data['53']['pprice']);
                    $objPHPExcel->getActiveSheet()->setCellValue('G'.$j, $tmp_data['53']['psumber']);
                    $objPHPExcel->getActiveSheet()->setCellValue('J'.$j, $tmp_data['53']['pprice']);
                    $objPHPExcel->getActiveSheet()->setCellValue('K'.$j, '=G'.$j.'*J'.$j);
                    
                }
                if(!empty($tmp_data['54']))  {
                    // var_dump($tmp_data['54']);var_dump($tmp_data['54']['pcumber']);exit;
                    $objPHPExcel->getActiveSheet()->setCellValue('G'.$j, $tmp_data['54']['psumber']);
                    $objPHPExcel->getActiveSheet()->setCellValue('J'.$j, $tmp_data['54']['pprice']);
                    $objPHPExcel->getActiveSheet()->setCellValue('K'.$j, '=G'.$j.'*J'.$j);
                }
                if(!empty($tmp_data['55']))  {
                    $objPHPExcel->getActiveSheet()->setCellValue('G'.$j, $tmp_data['55']['psumber']);
                    $objPHPExcel->getActiveSheet()->setCellValue('J'.$j, $tmp_data['55']['pprice']);
                    $objPHPExcel->getActiveSheet()->setCellValue('K'.$j, '=G'.$j.'*J'.$j);
                }
                if(!empty($tmp_data['55']))  {
                    $objPHPExcel->getActiveSheet()->setCellValue('G'.$j, $tmp_data['55']['psumber']);
                    $objPHPExcel->getActiveSheet()->setCellValue('J'.$j, $tmp_data['55']['pprice']);
                    $objPHPExcel->getActiveSheet()->setCellValue('K'.$j, "'G'.$j*'J'.$j");
                }
                if(!empty($tmp_data['55']))  {
                    $objPHPExcel->getActiveSheet()->setCellValue('G'.$j, $tmp_data['55']['psumber']);
                    $objPHPExcel->getActiveSheet()->setCellValue('J'.$j, $tmp_data['55']['pprice']);
                    $objPHPExcel->getActiveSheet()->setCellValue('K'.$j, '=G'.$j.'*J'.$j);
                }
                $objPHPExcel->getActiveSheet()->setCellValue('AO'.$j, '=K'.$j.'+P'.$j.'+S'.$j.'+X'.$j.'+AC'.$j.'+AF'.$j.'+AI'.$j.'+AN'.$j);
                // $objPHPExcel->getActiveSheet()->setCellValue('F'.$j, $value['inch_length'].'*'.$value['inch_width']);
                // $objPHPExcel->getActiveSheet()->setCellValue('D'.$j, $value['width']);
                // $objPHPExcel->getActiveSheet()->setCellValue('D'.$j, $value['width']);
                // $objPHPExcel->getActiveSheet()->setCellValue('D'.$j, $value['width']);
                // $objPHPExcel->getActiveSheet()->setCellValue('D'.$j, $value['width']);
                // $objPHPExcel->getActiveSheet()->setCellValue('D'.$j, $value['width']);
                // $objPHPExcel->getActiveSheet()->setCellValue('D'.$j, $value['width']);
                $j++;
            }
            if($j > 5){
                // $objPHPExcel->getActiveSheet()->mergeCells('A'.$j.':AQ'.$j);
                $j_tmp = $j - 1;
                $objPHPExcel->getActiveSheet()->setCellValue('A'.$j, '合计');//=SUM(K5:K135)
                $objPHPExcel->getActiveSheet()->setCellValue('K'.$j, '=SUM(K5:K'.$j_tmp.')');
                $objPHPExcel->getActiveSheet()->setCellValue('P'.$j, '=SUM(P5:P'.$j_tmp.')');
                $objPHPExcel->getActiveSheet()->setCellValue('S'.$j, '=SUM(S5:S'.$j_tmp.')');
                $objPHPExcel->getActiveSheet()->setCellValue('X'.$j, '=SUM(X5:X'.$j_tmp.')');
                $objPHPExcel->getActiveSheet()->setCellValue('AC'.$j, '=SUM(AC5:AC'.$j_tmp.')');
                $objPHPExcel->getActiveSheet()->setCellValue('AF'.$j, '=SUM(AF5:AF'.$j_tmp.')');
                $objPHPExcel->getActiveSheet()->setCellValue('AI'.$j, '=SUM(AI5:AI'.$j_tmp.')');
                $objPHPExcel->getActiveSheet()->setCellValue('AN'.$j, '=SUM(AN5:AN'.$j_tmp.')');
                $objPHPExcel->getActiveSheet()->setCellValue('AO'.$j, '=SUM(AO5:AO'.$j_tmp.')');
                $objPHPExcel->getActiveSheet()->getStyle('K5:K'.$j)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
                $objPHPExcel->getActiveSheet()->getStyle('K5:K'.$j)->getFill()->getStartColor()->setRGB('FFFF00');
                $objPHPExcel->getActiveSheet()->getStyle('P5:P'.$j)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
                $objPHPExcel->getActiveSheet()->getStyle('P5:P'.$j)->getFill()->getStartColor()->setRGB('FFFF00');
                $objPHPExcel->getActiveSheet()->getStyle('S5:S'.$j)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
                $objPHPExcel->getActiveSheet()->getStyle('S5:S'.$j)->getFill()->getStartColor()->setRGB('FFFF00');
                $objPHPExcel->getActiveSheet()->getStyle('X5:X'.$j)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
                $objPHPExcel->getActiveSheet()->getStyle('X5:X'.$j)->getFill()->getStartColor()->setRGB('FFFF00');
                $objPHPExcel->getActiveSheet()->getStyle('AC5:AC'.$j)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
                $objPHPExcel->getActiveSheet()->getStyle('AC5:AC'.$j)->getFill()->getStartColor()->setRGB('FFFF00');
                $objPHPExcel->getActiveSheet()->getStyle('AF5:AF'.$j)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
                $objPHPExcel->getActiveSheet()->getStyle('AF5:AF'.$j)->getFill()->getStartColor()->setRGB('FFFF00');
                $objPHPExcel->getActiveSheet()->getStyle('AI5:AI'.$j)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
                $objPHPExcel->getActiveSheet()->getStyle('AI5:AI'.$j)->getFill()->getStartColor()->setRGB('FFFF00');
                $objPHPExcel->getActiveSheet()->getStyle('AN5:AN'.$j)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
                $objPHPExcel->getActiveSheet()->getStyle('AN5:AN'.$j)->getFill()->getStartColor()->setRGB('FFFF00');
                $objPHPExcel->getActiveSheet()->getStyle('AO5:AO'.$j)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
                $objPHPExcel->getActiveSheet()->getStyle('AO5:AO'.$j)->getFill()->getStartColor()->setRGB('FFFF00');
            }
            $objPHPExcel->getActiveSheet()->getStyle('A1:AQ'.$j)->applyFromArray($styleThinBlackBorderOutline);
            // 设置水平居中
            // $objPHPExcel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            // $objPHPExcel->getActiveSheet()->getStyle('L1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            // $objPHPExcel->getActiveSheet()->getStyle('D3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $i1++;
        }
        $fileName = iconv("utf-8", "gb2312", $fileName);
        // exit();
        ob_end_clean();//清除缓冲区,0     
        header('Content-Type: application/vnd.ms-excel');
        header("Content-Disposition: attachment;filename=\"$fileName\"");
        header('Cache-Control: max-age=0');
        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output'); //文件通过浏览器下载
        exit;
           
    }
    
    public function indexa()
    {
        // 底部标题  include "../vendor/PHPExcel/PHPExcel.php";
        $user_data = Db::name('user')->alias('a')->where('a.role',0)->where('a.user_status',1)->select();
        // $user_data = Db::name('porder')->alias('o')->join('user u','o.uid = a.id','left')->where('a.role',0)->where('a.user_status',1)->select();
        // var_dump($user_data);exit;
        // $headArr = [];
        // foreach ($user_data as $value){
        //     if(!empty($value['signature'])) $headArr[$value['signature']]=[];
        //     if(!empty($value['signature'])) $headArr[$value['signature']]=[];
        // }
        // $headArr = array(
        // 		'底部标题1'=>array('姓名1aa','成绩'),
        // 		'底部标题2'=>array('姓名2a','成绩'),
        // 		'底部标题3'=>array('姓名3a','成绩'),
        // 	);
        // $data = array(
        //     '底部标题1'=>array(
        //     	array('name'=>'姓名1','chengji'=>'100'),
        //     	array('name'=>'姓名11','chengji'=>'100')
        //     ),
        //     '底部标题2'=>array(
        //     	array('name'=>'姓名2','chengji'=>'100'),
        //     	array('name'=>'姓名22','chengji'=>'100')
        //     ),
        //     '底部标题3'=>array(
        //     	array('name'=>'姓名3','chengji'=>'100'),
        //     	array('name'=>'姓名22','chengji'=>'100')
        //     )
        // );
        $date = date("YmdHis",time());
        $fileName = $date."下载.xls";// date('Y-m-d')."下载.xls";
        $fileName = urlencode($fileName);
       
        $objPHPExcel = new PHPExcel();
       //设置表头
        $tem_key = "A"; 
        $title =  '车间加工'.date('m').'月份明细';
        $i1 = 0;
        foreach($user_data as $key1 =>$values){
           	if($key1 !== 0) $objPHPExcel->createSheet();
            $objPHPExcel->setactivesheetindex($i1);
            
            //一二行抬头
            $objPHPExcel->getActiveSheet()->mergeCells('A1:B2');
            $objPHPExcel->getActiveSheet()->setCellValue('A1', $title);
            $objPHPExcel->getActiveSheet()->mergeCells('C1:C2');
            $objPHPExcel->getActiveSheet()->setCellValue('C1', '单号');
            $objPHPExcel->getActiveSheet()->mergeCells('D1:F2');
            $objPHPExcel->getActiveSheet()->setCellValue('D1', '规格');
            $objPHPExcel->getActiveSheet()->mergeCells('G1:K2');
            $objPHPExcel->getActiveSheet()->setCellValue('G1', '覆膜');
            // 设置垂直居中
            $objPHPExcel->getActiveSheet()->getStyle('A1:AQ4')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            // 设置水平居中
            $objPHPExcel->getActiveSheet()->getStyle('A1:AQ4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->mergeCells('L1:P1');
            $objPHPExcel->getActiveSheet()->setCellValue('L1','裱坑');
            $objPHPExcel->getActiveSheet()->mergeCells('L2:P2');//双E坑0.24最低0.2/张，开机150，三裱一0.4.最低0.3/张，开机300				
            $objPHPExcel->getActiveSheet()->setCellValue('L2', '双E坑0.24最低0.2/张，开机150，三裱一0.4.最低0.3/张，开机300');
            $objPHPExcel->getActiveSheet()->mergeCells('Q1:S2');
            $objPHPExcel->getActiveSheet()->setCellValue('Q1', '烫金');
            $objPHPExcel->getActiveSheet()->mergeCells('T1:X2');
            $objPHPExcel->getActiveSheet()->setCellValue('T1', '击凸');
            $objPHPExcel->getActiveSheet()->mergeCells('AD1:AF2');
            $objPHPExcel->getActiveSheet()->setCellValue('AD1', '打包装箱');
            $objPHPExcel->getActiveSheet()->mergeCells('AG1:AI2');
            $objPHPExcel->getActiveSheet()->setCellValue('AG1', '机器粘');
            $objPHPExcel->getActiveSheet()->mergeCells('AJ1:AK2');
            $objPHPExcel->getActiveSheet()->setCellValue('AJ1', '手工粘');
            $objPHPExcel->getActiveSheet()->mergeCells('AL1:AM2');
            $objPHPExcel->getActiveSheet()->setCellValue('AL1', '码板');
            // $objPHPExcel->getActiveSheet()->mergeCells('AN1:AQ2');
            
            
            //三四行抬头
            $objPHPExcel->getActiveSheet()->mergeCells('A3:A4');
            $objPHPExcel->getActiveSheet()->setCellValue('A3', '序号');
            $objPHPExcel->getActiveSheet()->mergeCells('B3:B4');
            $objPHPExcel->getActiveSheet()->setCellValue('B3', '货物名称');
            $objPHPExcel->getActiveSheet()->mergeCells('C3:C4');
            $objPHPExcel->getActiveSheet()->setCellValue('C3', '内部订单号');
            $objPHPExcel->getActiveSheet()->mergeCells('D3:F3');//尺寸
            $objPHPExcel->getActiveSheet()->setCellValue('D3','尺寸');
            $objPHPExcel->getActiveSheet()->setCellValue('E3', '尺寸');
            $objPHPExcel->getActiveSheet()->setCellValue('D4', '长');
            $objPHPExcel->getActiveSheet()->setCellValue('E4', '宽');
            $objPHPExcel->getActiveSheet()->setCellValue('F4', '英寸');
            $objPHPExcel->getActiveSheet()->mergeCells('G3:K3');
            // $objPHPExcel->getActiveSheet()->setCellValue('G3','尺寸');
            $objPHPExcel->getActiveSheet()->setCellValue('G3', '过胶开机费120，单张最低价格0.13/张');
            $objPHPExcel->getActiveSheet()->setCellValue('G4', '数量');
            $objPHPExcel->getActiveSheet()->setCellValue('H4', '光胶价0.3');
            $objPHPExcel->getActiveSheet()->setCellValue('I4', '哑胶价0.4');
            $objPHPExcel->getActiveSheet()->setCellValue('J4', '单价（保留2位）');
            $objPHPExcel->getActiveSheet()->setCellValue('K4', '金额');
            $objPHPExcel->getActiveSheet()->mergeCells('L3:P3');
            $objPHPExcel->getActiveSheet()->setCellValue('L3', 'E坑0.16最低0.1/张，开机120元F坑，粗坑0.18最低0.12/张，开机150');
            $objPHPExcel->getActiveSheet()->setCellValue('L4', '数量');
            $objPHPExcel->getActiveSheet()->setCellValue('M4', '裱坑0.16');
            $objPHPExcel->getActiveSheet()->setCellValue('N4', '备注');
            $objPHPExcel->getActiveSheet()->setCellValue('O4', '单价（保留2位）');
            $objPHPExcel->getActiveSheet()->setCellValue('P4', '金额');
            $objPHPExcel->getActiveSheet()->mergeCells('Q3:S3');//
            $objPHPExcel->getActiveSheet()->setCellValue('Q3', '烫金开机费150');
            $objPHPExcel->getActiveSheet()->setCellValue('Q4', '数量');
            $objPHPExcel->getActiveSheet()->setCellValue('R4', '单价');
            $objPHPExcel->getActiveSheet()->setCellValue('S4', '金额');
            $objPHPExcel->getActiveSheet()->mergeCells('T3:U3');//模数
            $objPHPExcel->getActiveSheet()->setCellValue('T3', '模数');
            $objPHPExcel->getActiveSheet()->setCellValue('T4', '模数');
            $objPHPExcel->getActiveSheet()->setCellValue('U4', '金额');
            $objPHPExcel->getActiveSheet()->mergeCells('V3:X3');//击凸开机费150
            $objPHPExcel->getActiveSheet()->setCellValue('V3', '击凸开机费150');
            $objPHPExcel->getActiveSheet()->setCellValue('V4', '数量');
            $objPHPExcel->getActiveSheet()->setCellValue('W4', '单价');
            $objPHPExcel->getActiveSheet()->setCellValue('X4', '金额');
            $objPHPExcel->getActiveSheet()->mergeCells('Y3:Z3');//模数
            $objPHPExcel->getActiveSheet()->setCellValue('Y3', '模数');
            $objPHPExcel->getActiveSheet()->setCellValue('Y4', '模数');
            $objPHPExcel->getActiveSheet()->setCellValue('Z4', '金额');
            $objPHPExcel->getActiveSheet()->mergeCells('AA3:AC3');//啤开机费130
            $objPHPExcel->getActiveSheet()->setCellValue('AA3', '啤开机费130');
            $objPHPExcel->getActiveSheet()->setCellValue('AA4', '数量');
            $objPHPExcel->getActiveSheet()->setCellValue('AB4', '单价');
            $objPHPExcel->getActiveSheet()->setCellValue('AC4', '金额');
            $objPHPExcel->getActiveSheet()->mergeCells('AD3:AF3');//打包码板装箱
            $objPHPExcel->getActiveSheet()->setCellValue('AD3', '打包码板装箱');
            $objPHPExcel->getActiveSheet()->setCellValue('AD4', '数量');
            $objPHPExcel->getActiveSheet()->setCellValue('AE4', '单价');
            $objPHPExcel->getActiveSheet()->setCellValue('AF4', '金额');
            $objPHPExcel->getActiveSheet()->mergeCells('AG3:AN3');//机器粘开机费120手工起步50
            $objPHPExcel->getActiveSheet()->setCellValue('AG3', '机器粘开机费120手工起步50');
            $objPHPExcel->getActiveSheet()->setCellValue('AG4', '数量');
            $objPHPExcel->getActiveSheet()->setCellValue('AH4', '单价');
            $objPHPExcel->getActiveSheet()->setCellValue('AI4', '金额');
            $objPHPExcel->getActiveSheet()->setCellValue('AJ4', '数量');
            $objPHPExcel->getActiveSheet()->setCellValue('AK4', '单价');
            $objPHPExcel->getActiveSheet()->setCellValue('AL4', '金额');
            $objPHPExcel->getActiveSheet()->setCellValue('AM4', '单价');
            $objPHPExcel->getActiveSheet()->setCellValue('AN4', '金额');
            $objPHPExcel->getActiveSheet()->mergeCells('AO3:AO4');//合计金额
            $objPHPExcel->getActiveSheet()->setCellValue('AO3', '合计金额');
            $objPHPExcel->getActiveSheet()->mergeCells('AP3:AQ4');//备注
            $objPHPExcel->getActiveSheet()->setCellValue('AP3', '备注');
            $objPHPExcel->getActiveSheet($i1)->setTitle($values['signature']);
            $user_data = Db::name('porder')->alias('a')->where('a.uid',$values['id'])->where('a.status','in','1,2,3')->select();
            $j = 5;
            foreach ($user_data as $key =>$value){
                $tmp_data = Db::name('pocart')->alias('pc')->join('production p','pc.proid = p.pid','left')->where('pc.cstatus',1)->column('pc.sum_num psumber,pc.com_num pcumber,pc.proid,p.parentid,p.pname,p.pprice','pc.proid');
                var_dump($tmp_data);
                // var_dump('/n/n');//
                exit;
                $objPHPExcel->getActiveSheet()->setCellValue('A'.$j, $key+1);
                $objPHPExcel->getActiveSheet()->setCellValue('B'.$j, $value['name']);
                $objPHPExcel->getActiveSheet()->setCellValue('C'.$j, ' '.$value['order_no']);
                $objPHPExcel->getActiveSheet()->setCellValue('D'.$j, $value['length']);
                $objPHPExcel->getActiveSheet()->setCellValue('E'.$j, $value['width']);
                $objPHPExcel->getActiveSheet()->setCellValue('F'.$j, ($value['inch_length']/24.5) * ($value['inch_width']/24.5)/1000);
                // var_dump($tmp_data);
                if(!empty($tmp_data['53']))  {
                    // var_dump($tmp_data['53']);var_dump($tmp_data['53']['pcumber']);var_dump($tmp_data['53']['pprice']);
                    $objPHPExcel->getActiveSheet()->setCellValue('G'.$j, $tmp_data['53']['pcumber']);
                    $objPHPExcel->getActiveSheet()->setCellValue('J'.$j, $tmp_data['53']['pprice']);
                    $objPHPExcel->getActiveSheet()->setCellValue('K'.$j, "'G'.$j*'J'.$j");
                    
                }
                if(!empty($tmp_data['54']))  {
                    // var_dump($tmp_data['54']);var_dump($tmp_data['54']['pcumber']);exit;
                    $objPHPExcel->getActiveSheet()->setCellValue('G'.$j, $tmp_data['54']['pcumber']);
                    $objPHPExcel->getActiveSheet()->setCellValue('J'.$j, $tmp_data['54']['pprice']);
                    $objPHPExcel->getActiveSheet()->setCellValue('K'.$j, "'G'.$j*'J'.$j");
                }
                if(!empty($tmp_data['55']))  {
                    $objPHPExcel->getActiveSheet()->setCellValue('G'.$j, $tmp_data['55']['pcumber']);
                    $objPHPExcel->getActiveSheet()->setCellValue('J'.$j, $tmp_data['55']['pprice']);
                    $objPHPExcel->getActiveSheet()->setCellValue('K'.$j, "'G'.$j*'J'.$j");
                }
                if(!empty($tmp_data['55']))  {
                    $objPHPExcel->getActiveSheet()->setCellValue('G'.$j, $tmp_data['55']['pcumber']);
                    $objPHPExcel->getActiveSheet()->setCellValue('J'.$j, $tmp_data['55']['pprice']);
                    $objPHPExcel->getActiveSheet()->setCellValue('K'.$j, "'G'.$j*'J'.$j");
                }
                if(!empty($tmp_data['55']))  {
                    $objPHPExcel->getActiveSheet()->setCellValue('G'.$j, $tmp_data['55']['pcumber']);
                    $objPHPExcel->getActiveSheet()->setCellValue('J'.$j, $tmp_data['55']['pprice']);
                    $objPHPExcel->getActiveSheet()->setCellValue('K'.$j, "'G'.$j*'J'.$j");
                }
                
                // $objPHPExcel->getActiveSheet()->setCellValue('F'.$j, $value['inch_length'].'*'.$value['inch_width']);
                // $objPHPExcel->getActiveSheet()->setCellValue('D'.$j, $value['width']);
                // $objPHPExcel->getActiveSheet()->setCellValue('D'.$j, $value['width']);
                // $objPHPExcel->getActiveSheet()->setCellValue('D'.$j, $value['width']);
                // $objPHPExcel->getActiveSheet()->setCellValue('D'.$j, $value['width']);
                // $objPHPExcel->getActiveSheet()->setCellValue('D'.$j, $value['width']);
                // $objPHPExcel->getActiveSheet()->setCellValue('D'.$j, $value['width']);
                $j++;
            }
            // 设置水平居中
            // $objPHPExcel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            // $objPHPExcel->getActiveSheet()->getStyle('L1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            // $objPHPExcel->getActiveSheet()->getStyle('D3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $i1++;
        }
        $fileName = iconv("utf-8", "gb2312", $fileName);
        exit();
        ob_end_clean();//清除缓冲区,0     
        header('Content-Type: application/vnd.ms-excel');
        header("Content-Disposition: attachment;filename=\"$fileName\"");
        header('Cache-Control: max-age=0');
        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output'); //文件通过浏览器下载
        exit;
            // $objPHPExcel->getActiveSheet()->getStyle('A3:G5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            // $objPHPExcel->getActiveSheet()->getStyle('A7:G10')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            // $objPHPExcel->getActiveSheet()->getStyle('A12:B15')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        //     $tem_key = "A";
        //   	foreach($values as $v){
        //   		if (strlen($tem_key) > 1){
        //             $arr_key = str_split($tem_key);
        //             $colum = '';
        //             foreach ($arr_key as $ke=>$va){
        //                 $colum .= chr(ord($va));
        //             }
        //         }else{
        //         	$colum = '';
        //             $key = ord($tem_key);
        //             $colum = chr($key);
        //         }
        //         $objPHPExcel->getActiveSheet()->setCellValue($colum.'5', $v);
        //     	$tem_key++;
        //   	}
        //   	$i1++;
        //   }
        //   $border_end = 'A1'; // 边框结束位置初始化
        //   $i = 0;
        //   foreach($data as $kes => $values){ //获取一行数据
        //   	// if($i != 0) $objPHPExcel->createSheet();
        //     $objPHPExcel->setActiveSheetIndex($i);
        //     $objPHPExcel->getActiveSheet($i)->setTitle($kes);
        //     $column = 4;
        //     foreach($values as $k1 => $rows){
        //     	$tem_span = "A";
        //     	$j = '';
        //         foreach($rows as $keyName=>$value){// 写入一行数据
        //             if (strlen($tem_span) > 1){
        //                 $arr_span = str_split($tem_span);
        //                 $j = '';
        //                 foreach ($arr_span as $ke=>$va){
        //                     $j .= chr(ord($va));
        //                 }
        //             }else{
        //                 $span = ord($tem_span);
        //                 $j = chr($span);
        //             }
        //             $objPHPExcel->getActiveSheet()->setCellValue($j.$column, $value);
        //             $border_end = $j.$column;
        //             $tem_span++;
        //         }
        //         $column++;
        //     }
        //     $i++;
        // }
        //   $fileName = iconv("utf-8", "gb2312", $fileName);
        //   ob_end_clean();//清除缓冲区,0     
        //   header('Content-Type: application/vnd.ms-excel');
        //   header("Content-Disposition: attachment;filename=\"$fileName\"");
        //   header('Cache-Control: max-age=0');
        //   $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        //   $objWriter->save('php://output'); //文件通过浏览器下载
        //   exit;
        // var_dump(time());
    }
    
}    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
