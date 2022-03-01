<?php


/**
 * @Description		工艺订单模型
 * @Author			LiuS PorderModel 
 * @Date			2019-08-08 15:49:59
 * @LastEditTime	2019-08-08 15:49:59
 */

namespace api\app\model;

use think\Model;

class PorderModel extends Model{

	protected $pk 					= 'id';
	protected $name  				= 'porder';
    
    // --------------------------------------------------------------------
	/**
	 * @access		public
	 * @example		获取订单列表
	 * @param		int 	page		页面
	 * @param		int 	status		0:未发货 1：已发货 2:催发货
	 * @author		LiuS
	 * @version		2020-11-03 10:29:42
	 * @return		json
	 */
	
	public static function getOrderList(int $page = 1,int $status  = 0,$where = [],$order = 'id desc') : array
	{
	    
	}

    
}