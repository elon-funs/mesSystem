<?php


/**
 * @Description		资源模型 (文件模型)
 * @Author			HJ
 * @Date			2019-08-08 15:49:59
 * @LastEditTime	2019-08-08 15:49:59
 */

namespace api\app\model;

use think\Model;
use think\Image;
use think\Db;

use think\facade\Env;

class AssetModel extends Model{

	protected $pk 					= 'id';
	protected $name  				= 'asset';


	// --------------------------------------------------------------------
	/**
	 * @access		public
	 * @example		获取资源 http 全拼
	 * @param		string 	variable		explain
	 * @author		HJ
	 * @version		2019-08-08 17:19:09
	 * @return		json
	 */
	
	public static function getHttpImage($image_src = '') : string
	{	
		$return_src 					= '';
		if(!empty($image_src)){

			// $before 					= 

			if(substr($image_src,0,25) == 'lingyi.erp.geekcnet.com/upload/'){
				$image_src 				= substr($image_src,25);

				// print_r($image_src);
			}

			$return_src					= str_replace('\\', '/', IMAGE_HTTP_URL.$image_src);
		}

		return $return_src;
	}


	// --------------------------------------------------------------------
	/**
	 * @access		public
	 * @example		获取所有资源图片列表
	 * @param		int 	origin_id		资源ID
	 * @param		int 	type			类型:1:房楼图片列表
	 * @author		HJ
	 * @version		2019-08-08 18:31:03
	 * @return		json
	 */
	public static function getImageList(int $origin_id) : array
	{
		if(empty($origin_id))				return [];

		$where								= [];
		$where[] 							= ['status', '=', 1];
		$where[]							= ['origin_id', '=', $origin_id];

		//图片列表
		$image_list							= AssetModel::where($where)->field('id,file_path')->select();

		if($image_list->isEmpty())			return [];

		$image_list							= $image_list->toArray();

		$data								= [];
		foreach ($image_list as $key => $value) {
			$data[]							= AssetModel::getHttpImage($value['file_path']);
		}

		return $data;
	}

	// --------------------------------------------------------------------
	/**
	 * @access		public
	 * @example		获取缩略图
	 * @param		string 	variable		explain
	 * @author		HJ
	 * @version	2019-07-03 15:24:31
	 * @return		json
	 */
	public static function getImageThum($image_src = '', $thum_folder = 'product'):array
	{

		$result 					= ['image' => '', 'image_thum' => ''];
		if(empty($image_src)){
			return $result;
		}
		if(substr($image_src,0,25) == 'lingyi.erp.geekcnet.com/upload/'){
			$image_src 				= substr($image_src,25);
		}


		$test_iamge_src 							= $image_src;
		$test_image_thum_src						= $thum_folder.'/'.$test_iamge_src;

		//缩略图文件 路径
		$image_thum_src 							= 'upload/'.$thum_folder.'/'.$test_iamge_src;
		//upload/ banner /admin/20190703/1549f9bd0983345a9aebe182c1456c8a.jpg
		//upload 		 /admin/20190703/1549f9bd0983345a9aebe182c1456c8a.jpg
		$image_src 									= 'upload/'.$image_src;



		//判断原图是否存在
		if(file_exists($image_src)){

			//原图路径
			$image_path_src 							= substr($test_iamge_src,0, strrpos($test_iamge_src,"/"));
			//缩略图路径
			$image_thum_path_src 						= 'upload/'.$thum_folder.'/'.$image_path_src;
			//admin/20190703
			//upload/banner/admin/20190703

			//判断缩略图目录是否存在
			if (!is_dir($image_thum_path_src) ){
				@mkdir($image_thum_path_src, 0777); 		//创建
			}

			//判断缩略图 文件是否存在 不存在则创建 缩略图
			if(!file_exists($image_thum_src)){
				ini_set('memory_limit','512M');
				// 获取 php 配置文件中设置的脚本执行时间
				$max_execution_time = ini_get('max_execution_time');
				// 表示 php 脚本执行时间无限制
				set_time_limit(0);
				image_png_size_add($image_src ,$image_thum_src);
				// 任务执行完后，还原
				set_time_limit($max_execution_time);
			}

			$result['image_thum']						= str_replace('\\', '/', IMAGE_HTTP_URL.$test_image_thum_src);
			$result['image']							= str_replace('\\', '/', IMAGE_HTTP_URL.$test_iamge_src);
		
		}
		return $result;
	}
	
// 	public static function base64($base64_str = '',$name = '')
// 	{
// 	    $result = ['image'=>''];
// 	    if (empty($base64_str)) return $result;
// 	    $image = time().mt_rand(1000,99999);
	    
// 	    if (preg_match('/^(data:\s*image\/(\w+);base64,)/', $base64_str, $result)){
//             $type = $result[2];
//             $file_name = "base64_$image.{$type}";
//             $file_path = date('Y-m-d');
//             if(!is_dir("./upload/$file_path/")){
//                 mk_dir("./upload/$file_path/");
//             }
//             $new_file = "./upload/base64_$image.{$type}";
//             if (file_put_contents($new_file, base64_decode(str_replace($result[1], '', $base64_str)))){
//                 return $result;
//             }
        
//         }
// 	}


		// --------------------------------------------------------------------
	/**
	 * @access    	public
	 * @example 	文件上传
	 * @param 	  	string 	variable		explain
	 * @author 		HJ
	 * @version 	2019-03-22 12:45:38
	 * @return    	array,float,boolean,int,string,void
	 */
	
	public static function uploader($file, $origin_id = 0) : array
	{

		if(empty($file))			return ['error'=> '请上传文件', 'code' => 0];

		$user_id 					= 0;
		if(defined('UID')){
			$user_id 					= UID;
		}

        // 移动到框架应用根目录/public/upload/ 目录下
        $info     					= $file->validate([
           					 		/* 'size' => 15678,*/
            						'ext' => 'jpg,png,gif'
        							]);

        $fileMd5  					= $info->md5();
        $fileSha1 					= $info->sha1();

        $findFile 					= self::where('file_md5', $fileMd5)->where('file_sha1', $fileSha1)->find();

     	
        $originalName 				= $info->getInfo('name');//name,type,size
        $fileSize     				= $info->getInfo('size');
        $suffix      				= $info->getExtension();

        if (!empty($findFile)){

        	$file_info 			 	 = self::create([
					'user_id'    	 => $user_id,
					'file_key'   	 => $findFile['file_key'],
					'filename'    	 => $originalName,
					'file_size'   	 => $fileSize,
					'file_path'   	 => $findFile['file_path'],
					'file_md5'    	 => $fileMd5,
					'file_sha1'   	 => $fileSha1,
					'create_time' 	 => NOW_TIME,
					'suffix'      	 => $suffix,
					'origin_id'		 => $origin_id,
	            ]);


        	return ['file_id' => $file_info->id, 'url' => $findFile['file_path'],'themb'=> $findFile['file_themb'],  'filename' => $findFile['filename'], 'code' => 1];
        }


        $info 						= $info->move(Env::get('root_path') . 'public' . DIRECTORY_SEPARATOR . 'upload');
        if ($info) {
        	$saveName     			= $info->getSaveName();
            // $image = \think\Image::open('./image.jpg');
            $image                  = Image::open(Env::get('root_path').'public/upload/'.$saveName); 
            $file_themb             = str_replace('.','thumb.',$saveName);
            $image->thumb(300, 300)->save(Env::get('root_path').'public/upload/'.$file_themb);
            $fileKey 				= $fileMd5 . md5($fileSha1);

            $file_info 				= self::create([
						                'user_id'    	 	=> $user_id,
						                'file_key'   	 	=> $fileKey,
						                'filename'    		=> $originalName,
						                'file_size'   		=> $fileSize,
						                'file_path'   		=> $saveName,
						                'file_md5'    		=> $fileMd5,
						                'file_sha1'   		=> $fileSha1,
						                'create_time' 		=> time(),
						                'suffix'      		=> $suffix,
						                'origin_id'			=> $origin_id,
						                'file_themb'        => $file_themb,
						            ]);
            return ['file_id' => $file_info->id, 'url' => $saveName,'themb'=>$file_themb, 'filename' => $originalName, 'code' => 1];
        } else {
            // 上传失败获取错误信息
            return ['error'=> $file->getError(), 'code' => 0];
        }
	}

	/**
	 * @access    	public
	 * @example 	更新 mysql产品
	 * @param 	  	string 	variable		explain
	 * @author 		HJ
	 * @version 	2019-03-22 12:45:38
	 * @return    	array,float,boolean,int,string,void
	 */	
	public static function updateMysqlData($product_assort = []) : bool
	{
		if(empty($product_assort))			return true;


		$localhost_asset_list 					= AssetModel::where('status','=',1)->select();

		//删除数据
		$delete_data_id					= [];
		//更新数据
		$update_data					= [];
		//插入数据
		$insert_data					= [];


		$asset_list_id				= [];

		foreach ($product_assort as $key => $value) {
			$asset_list_id[]			= $value['id'];
		}

		$localhost_asset_list 						= $localhost_asset_list->toArray();

		foreach ($product_assort as $key => $value) {

			$is_exit 					= 0;

			foreach ($localhost_asset_list as $k => $v) {
				if($value['id'] == $v['sql_id']){
					$select_value 					= $v;
					$select_value['create_time']	= $value['create_time'];
					// $select_value['file_path']		= $value['file_path'];
					$select_value['status']			= $value['status'];
				// 	$select_value['type']			= 1;
					if(substr($value['file_path'],0,25) == 'lingyi.erp.geekcnet.com/upload/'){
						$select_value['file_path']	= substr($value['file_path'],25);
					}else{
						$select_value['file_path']	= $value['file_path'];
					}

					$update_data[]					= $select_value;
					$is_exit 						= 1;
				}
			}

			if($is_exit == 0){
				$select_value					= [];
				
				$select_value['sql_id']			= $value['id'];
				$select_value['create_time']	= $value['create_time'];
				// $select_value['type']			= 1;
				$select_value['status']			= $value['status'];
				if(substr($value['file_path'],0,25) == 'lingyi.erp.geekcnet.com/upload/'){
					$select_value['file_path']	= substr($value['file_path'],25);
				}else{
					$select_value['file_path']	= $value['file_path'];
				}
				$insert_data[]					= $select_value;

			}
		}


		if(!empty($insert_data)){
			Db::name('asset')->insertAll($insert_data);
		}
		if(!empty($update_data)){
			$asset_model 						= new AssetModel;
			$asset_model->saveAll($update_data);
		}

		return false;

	}
}

?>