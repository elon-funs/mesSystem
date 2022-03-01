<?php
// +----------------------------------------------------------------------
// | ThinkCMF [ WE CAN DO IT MORE SIMPLE ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013-2018 http://www.thinkcmf.com All rights reserved.
// +----------------------------------------------------------------------
// | Released under the MIT License.
// +----------------------------------------------------------------------
// | Author: 老猫 <thinkcmf@126.com>
// +----------------------------------------------------------------------

namespace app\demo\controller;

use cmf\controller\HomeBaseController;

class IndexController extends HomeBaseController
{
    public function index()
    {
        return $this->fetch(':index');
    }

    public function ws()
    {
        return $this->fetch(':ws');
    }
    
    public function indexa()
    {
        include "../vendor/PHPExcel/PHPExcel.php";
        $headArr = array(
        		'底部标题1'=>array('姓名','成绩'),
        		'底部标题2'=>array('姓名','成绩'),
        		'底部标题3'=>array('姓名','成绩'),
        	);
        $data = array(
            '底部标题1'=>array(
            	array('name'=>'姓名1','chengji'=>'100'),
            	array('name'=>'姓名11','chengji'=>'100')
            ),
            '底部标题2'=>array(
            	array('name'=>'姓名2','chengji'=>'100'),
            	array('name'=>'姓名22','chengji'=>'100')
            ),
            '底部标题3'=>array(
            	array('name'=>'姓名3','chengji'=>'100'),
            	array('name'=>'姓名22','chengji'=>'100')
            )
        );
        $date = date("YmdHis",time());
           $fileName = "文件名称.xls";
           $fileName = urlencode($fileName);
           
           $objPHPExcel = new PHPExcel();
           //设置表头
           $tem_key = "A";
           $i1=0;
           foreach($headArr as $key1 =>$values){
           	if($i1 !== 0) $objPHPExcel->createSheet();
            $objPHPExcel->setactivesheetindex($i1);
            $objPHPExcel->getActiveSheet($i1)->setTitle($key1);
            $tem_key = "A";
           	foreach($values as $v){
           		if (strlen($tem_key) > 1){
                    $arr_key = str_split($tem_key);
                    $colum = '';
                    foreach ($arr_key as $ke=>$va){
                        $colum .= chr(ord($va));
                    }
                }else{
                	$colum = '';
                    $key = ord($tem_key);
                    $colum = chr($key);
                }
                $objPHPExcel->getActiveSheet()->setCellValue($colum.'1', $v);
            	$tem_key++;
           	}
           	$i1++;
           }
           $border_end = 'A1'; // 边框结束位置初始化
           $i = 0;
           foreach($data as $kes => $values){ //获取一行数据
           	// if($i != 0) $objPHPExcel->createSheet();
            $objPHPExcel->setActiveSheetIndex($i);
            $objPHPExcel->getActiveSheet($i)->setTitle($kes);
            $column = 2;
            foreach($values as $k1 => $rows){
            	$tem_span = "A";
            	$j = '';
                foreach($rows as $keyName=>$value){// 写入一行数据
                    if (strlen($tem_span) > 1){
                        $arr_span = str_split($tem_span);
                        $j = '';
                        foreach ($arr_span as $ke=>$va){
                            $j .= chr(ord($va));
                        }
                    }else{
                        $span = ord($tem_span);
                        $j = chr($span);
                    }
                    $objPHPExcel->getActiveSheet()->setCellValue($j.$column, $value);
                    $border_end = $j.$column;
                    $tem_span++;
                }
                $column++;
            }
            $i++;
           }
           $fileName = iconv("utf-8", "gb2312", $fileName);
           ob_end_clean();//清除缓冲区,0     
           header('Content-Type: application/vnd.ms-excel');
           header("Content-Disposition: attachment;filename=\"$fileName\"");
           header('Cache-Control: max-age=0');
           $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
           $objWriter->save('php://output'); //文件通过浏览器下载
           exit;
        var_dump(time());
    }
}
