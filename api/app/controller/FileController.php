<?php
/**
 * @Description		
 * @Author			LiuS

 */
namespace api\app\controller;


use api\app\model\AssetModel;
use think\Db;
use think\facade\Cache;

class FileController extends RestBaseController
{
    public function __construct(){
		parent::__construct();
		$this->_initUser();
	}
	
	// --------------------------------------------------------------------
	/**
	 * @access    	public
	 * @example 	文件上传
	 * @param 	  	string 	variable		explain
	 * @author 		Lius
	 * @version 	2019-03-22 12:29:08
	 * @return    	array,float,boolean,int,string,void
	 */
	public function uploader(){

		$origin_id			= $this->request->post('origin_id', 0, 'intval');	//传0
		$type				= $this->request->post('type',3, 'intval');			// 1:mobile  2: mobile_module 3.mobile_background_url
		$file				= $this->request->file('file');						//文件

		$validate_data            = [
            'origin_id'           => $origin_id,
            'type'                => $type,
            'file'                => $file,
        ];


        // $result = $this->validate($validate_data, 'File.uploader');
        // if ($result !== true) {
        //     // 验证失败 输出错误信息
        //     $this->error($result);
        // }


		$result 			= AssetModel::uploader($file, $origin_id, $type);


 		if ($result['code'] == 0) {
 			$this->error(($result['error']));
        } else {

			$result['url'] 	= IMAGE_HTTP_URL.$result['url'];

        	$this->success('上传成功',$result);
        }
	}
	
	// --------------------------------------------------------------------
	/**
	 * @access		public
	 * @example		使用 文件 上传
	 * @param		string 	variable		explain
	 * @author		Lius
	 * @version	2020-11-10 15:47:31
	 * @return		json
	 */
//     public function base64file(){
//         $origin_id			= $this->request->post('origin_id', 0, 'intval');	//传0
// 		$type				= $this->request->post('type',3, 'intval');			// 1:mobile  2: mobile_module 3.mobile_background_url
// 		$base64_image		= $this->request->post('file');						//文件
		
// 		if (preg_match('/^(data:\s*image\/(\w+);base64,)/', $base64_image, $result)){
//             $type = $result[2];
//             $new_file = "./test.{$type}";
//             if (file_put_contents($new_file, base64_decode(str_replace($result[1], '', $base64_image_content)))){
//                 echo '新文件保存成功：', $new_file;
//             }
        
//         }
// 	 }
	// --------------------------------------------------------------------
	/**
	 * @access		public
	 * @example		使用 文件 上传
	 * @param		string 	variable		explain
	 * @author		HJ
	 * @version	2019-06-27 15:47:31
	 * @return		json
	 */
	public function userUploader(){
		

		$origin_id			= $this->request->post('origin_id', 0, 'intval');	//传0 1:订单上传  2: mobile_module 3.mobile_background_url
// 		$type				= $this->request->post('type',3, 'intval');			// 1:订单上传  2: mobile_module 3.mobile_background_url
		$file				= $this->request->file('file');						//文件

		$validate_data            = [
            'origin_id'           => $origin_id,
            // 'type'                => $type,
            'file'                => $file,
        ];


        $result = $this->validate($validate_data, 'File.uploader');
        if ($result !== true) {
            // 验证失败 输出错误信息
            $this->error($result);
        }


		$result 			= AssetModel::uploader($file, $origin_id, $this->userId);


 		if ($result['code'] == 0) {
 			$this->error(($result['error']));
        } else {

			$result['url'] 	= IMAGE_HTTP_URL.$result['url'];

        	$this->success('上传成功',$result);
        }
	}
}