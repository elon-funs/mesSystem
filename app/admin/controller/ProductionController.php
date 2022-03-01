<?php

/**
 * @Description		生产工艺
 * @Author			LiuS
 * @Date			2021-11-02 15:14:52
 * @LastEditTime	2021-12-12 15:14:52
 */

namespace app\admin\controller;

use cmf\controller\AdminBaseController;
use think\Db;



class ProductionController extends AdminBaseController
{
    // --------------------------------------------------------------------
    /**
     * @access		public
     * @example		工艺列表页
     * @param		string 	variable		explain
     * @author		LiuS
     * @version		2021-12-21 17:37:25
     * @return		json
     */
    public function index(){
        $search                          	= $this->request->param('search','');		///工艺名称
        
        $production_list = Db::name('production')->where('status < 4')->where('pname|synopsis','like','%'.$search.'%')->order('sort DESC')->select();
        
        $this->assign("production_list", $production_list);

        $this->assign("page", $page??0);
        // $this->assign("list", $list??0);
        return $this->fetch();
    }
    // --------------------------------------------------------------------
    /**
     * @access		public
     * @example		工艺列表页
     * @param		string 	variable		explain
     * @author		LiuS
     * @version		2021-11-12 17:37:25
     * @return		json
     */
    public function save(){
        $product_id                         = $this->request->param('production_id',0);			//工艺ID
        $sort                           	= $this->request->param('sort',100);		//生产排序
        $status                            	= $this->request->param('status',1);		//状态
        $pname                          	= $this->request->param('pname','');		///工艺名称
        $synopsis                        	= $this->request->param('synopsis','');		//工艺简介
        $parentid                        	= $this->request->param('parentid',0);		//工艺父级ID parentid
	    $pprice                             = $this->request->post('pprice','');//单价
	    $is_yes                             = $this->request->post('is_yes',0);//工艺是否可选 1可选 0 不可选
		$is_choice                          = $this->request->post('is_choice',0);      //材料id 
        if(empty($pname))    return ['data'=> [],'status'=> 0,'msg' => '参数有误'];
        if(empty($product_id)){
            $data                           = [
                'pname'                     => $pname,
                'synopsis'                  => $synopsis,
                'sort'                      => $sort,
                'add_time'                  => time(),
                'status'                    => $status,
                'is_yes'                    => $is_yes,
                'parentid'                  => $parentid,
                'pprice'                    => $pprice,
                'is_choice'                 => $is_choice
                ];
            $res = Db::name('production')->insert($data);
            
        }else{
            $data                           = [
                'pname'                     => $pname,
                'synopsis'                  => $synopsis,
                'sort'                      => $sort,
                'edit_time'                 => time(),
                'status'                    => $status,
                'is_yes'                    => $is_yes,
                'parentid'                  => $parentid,
                'pprice'                    => $pprice,
                'is_choice'                 => $is_choice
                ];
            $res = Db::name('production')->where('pid',$product_id)->update($data);
        }
        if($res)    return ['data'=> [],'code'=> 1,'msg' => '操作成功'];
        return ['data'=> [],'code'=> 202,'msg' => '操作失败'];
    }
    // --------------------------------------------------------------------
    /**
     * @access		public
     * @example		工艺流程添加
     * @param		string 	variable		explain
     * @author		LiuS
     * @version		2021-10-02 17:37:25
     * @return		json
     */
    public function add(){
        $pro_list = Db::name('production')->where('status < 4')->where('parentid',0)->order('sort DESC')->select()->toArray();
        foreach ($pro_list as $key => $value){
	        $tmp        	            = Db::name('Production')->where('status',1)->where('parentid',$value['pid'])->order('sort desc')->field('pid,pname')->select()->toArray();
	        if(!empty($tmp))        
	            $pro_list[$key]['next_level']    = $tmp;
	        else 
	            $pro_list[$key]['next_level']    = [];
	    }
        $this->assign("production_list", $pro_list);
        return $this->fetch();
    }
    // --------------------------------------------------------------------
    /**
     * @access		public
     * @example		工艺流程修改
     * @param		string 	variable		explain
     * @author		LiuS
     * @version		2022-01-02 17:37:25
     * @return		json
     */
    public function edit(){
        $product_id                         = $this->request->param('production_id',0);			//工艺ID
        if(empty($product_id))              $this->error("参数错误！");
        
        $pro_data                           = $pro_list = Db::name('production')->where('status < 4')->where('pid',$product_id)->find();
        if(empty($pro_data))  $this->error("生产工艺存在或已删除！");
        
        $pro_list = Db::name('production')->where('status < 4')->where('parentid',0)->order('sort DESC')->select()->toArray();
        
        foreach ($pro_list as $key => $value){
	        $tmp        	            = Db::name('Production')->where('status',1)->where('parentid',$value['pid'])->order('sort desc')->field('pid,pname')->select()->toArray();
	        if(!empty($tmp))        
	            $pro_list[$key]['next_level']    = $tmp;
	        else 
	            $pro_list[$key]['next_level']    = [];
	    }
	    
        $this->assign("production_list", $pro_list);
        $this->assign("data", $pro_data);
        return $this->fetch();
    }
    
    // --------------------------------------------------------------------
    /**
     * @access		public
     * @example		工艺流程删除
     * @param		string 	variable		explain
     * @author		LiuS
     * @version		2021-11-02 17:37:25
     * @return		json
     */
    public function delete()
    {
        $product_id = $this->request->param('id', 0, 'intval');
        if(empty($product_id))              $this->error("参数错误！");
        if (Db::name('production')->where('pid', $product_id)->data(['status' => 4,'edit_time'=> time()])->update() !== false) {
            $this->success("删除成功！");
        } else {
            $this->error("删除失败！");
        }
    }
}