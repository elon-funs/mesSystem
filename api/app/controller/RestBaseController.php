<?php
// +----------------------------------------------------------------------
// | LiuS
// +----------------------------------------------------------------------
namespace api\app\controller;

use think\App;
use think\Container;
use think\exception\HttpResponseException;
use think\exception\ValidateException;
use think\Response; 
use think\Db;
use think\Log;

class RestBaseController
{
    //token
    protected $token = '';

    //设备类型
    protected $deviceType = '';

    protected $apiVersion;

    //用户 id
    protected $userId = 0;
    //用户
    protected $user;
    //用户名
    protected $userName;
    
    protected $staff_no;
	
    protected $role;
    
    protected $role_no;
    
    protected $app;

    //用户类型
    protected $userType;

    protected $allowedDeviceTypes 		= ['mobile', 'android', 'iphone', 'ipad', 'web', 'pc', 'mac', 'wxapp'];
    //域名
    protected $httpWapUrl               = '';





    //小程序 appId
    protected $wechatSprAppId 		    = '##########';
	//小程序 appSecret
    protected $wechatSprAppAecret 	    = '####################';
    //微信支付分配的商户号
    protected $wechatSprMchId           = '##########';
    //支付密钥
    protected $wechatSprKey             = '##########';

    //接口API URL前缀
    protected $apiUrlPrefix             = 'https://api.mch.weixin.qq.com';
    // //统一下单地址URL
    // protected $unifiedorderUrl          = "/pay/unifiedorder";
    // //查询订单URL    
    // protected $orderqieryUrl            = "/pay/orderquery";
    // //关闭订单URL    
    // protected $closeorderUrl            = "/pay/orderquery";

    // //申请退款
    // protected $refundUrl                = "/secapi/pay/refund";

    /**
     * @var \think\Request Request实例
     */
    protected $request;
    // 验证失败是否抛出异常
    protected $failException = false;
    // 是否批量验证
    protected $batchValidate = false;

    /**
     * 前置操作方法列表
     * @var array $beforeActionList
     * @access protected
     */
    protected $beforeActionList = [];

    /**
     * RestBaseController constructor.
     * @param App|null $app
     */
    public function __construct(App $app = null)
    {
        $this->app          = $app ?: Container::get('app');
        $this->request      = $this->app['request'];

        $this->request->root(cmf_get_root() . '/');



        // 控制器初始化
        $this->initialize();

        // 前置操作方法
        if ($this->beforeActionList) {
            foreach ($this->beforeActionList as $method => $options) {
                is_numeric($method) ?
                    $this->beforeAction($options) :
                    $this->beforeAction($method, $options);
            }
        }
    }

    // 初始化
    protected function initialize()
    {
        define('NOW_TIME', time());
        define('HTTP_WAP_URL', $this->httpWapUrl);
        define('IMAGE_HTTP_URL', HTTP_WAP_URL.'upload/');
        define('UIP',  $this->request->ip());

        $this->apiVersion   = $this->request->header('RY-Api-Version');
        $this->deviceType   = $this->request->header('RY-Device-Type');
        $this->token        = $this->request->header('RY-Token');
		
        // if (empty($this->deviceType)) {
        //     return $this->error(['code' => 202, 'msg' => 'request_abnormal']);
        // }

        // if (!in_array($this->deviceType, $this->allowedDeviceTypes)) {
        //     return $this->error(['code' => 202, 'msg' => 'request_abnormal']);
        // }

        // if (empty($this->apiVersion)) {
        //     return $this->error(['code' => 202, 'msg' => 'request_abnormal']);
        // }
        
    }



    protected function _initUser()
    {
        $token         = $this->token;
        $deviceType     = $this->deviceType;

        if (empty($token)) {
            return $this->error(['code' => 201, 'msg' => 'no login']);
        }

        $user = Db::name('user_token')
            ->alias('a')
            ->field('b.*')
            ->where(['token' => $token, 'device_type' => $deviceType])
            ->join('__USER__ b', 'a.user_id = b.id')
            ->find();


        if (!empty($user)) {
            $this->user         = $user;
            $this->userId       = $user['id'];
            $this->userName     = $user['user_nickname']??'';
            $this->role_no      = $user['role_no'];
            $this->role		    = $user['role'];
        }
        define('UID', $this->userId);
        define('STAFF_NO', $this->staff_no);
    }

    protected function _initEmptyToken()
    {
        $token                      = $this->token;
        $deviceType                 = $this->deviceType;

        if (empty($token)) {
            $this->userId           = 0;
        }else{
            $user                   = Db::name('user_token')
                                    ->alias('a')
                                    ->field('b.*')
                                    ->where(['token' => $token, 'device_type' => $deviceType])
                                    ->join('__USER__ b', 'a.user_id = b.id')
                                    ->find();

            if (!empty($user)) {
                $this->user         = $user;
                $this->userId       = $user['id'];
                $this->role_no      = $user['role_no'];
                $this->role		    = $user['role'];
            }else{
                return $this->error('login is invalidation');
            }
        }

        define('UID', $this->userId);
        define('STAFF_NO', $this->staff_no);
    }

    /**
     * 前置操作
     * @access protected
     * @param string $method 前置操作方法名
     * @param array $options 调用参数 ['only'=>[...]] 或者['except'=>[...]]
     */
    protected function beforeAction($method, $options = [])
    {
        if (isset($options['only'])) {
            if (is_string($options['only'])) {
                $options['only'] = explode(',', $options['only']);
            }
            if (!in_array($this->request->action(), $options['only'])) {
                return;
            }
        } elseif (isset($options['except'])) {
            if (is_string($options['except'])) {
                $options['except'] = explode(',', $options['except']);
            }
            if (in_array($this->request->action(), $options['except'])) {
                return;
            }
        }

        call_user_func([$this, $method]);
    }


    /**
     * 设置验证失败后是否抛出异常
     * @access protected
     * @param bool $fail 是否抛出异常
     * @return $this
     */
    protected function validateFailException($fail = true)
    {
        $this->failException = $fail;
        return $this;
    }

    /**
     * 验证数据
     * @access protected
     * @param array $data 数据
     * @param string|array $validate 验证器名或者验证规则数组
     * @param array $message 提示信息
     * @param bool $batch 是否批量验证
     * @param mixed $callback 回调方法（闭包）
     * @return bool
     */
    protected function validate($data, $validate, $message = [], $batch = false, $callback = null)
    {
        if (is_array($validate)) {
            $v = $this->app->validate();
            $v->rule($validate);
        } else {
            if (strpos($validate, '.')) {
                // 支持场景
                list($validate, $scene) = explode('.', $validate);
            }
            $v = $this->app->validate($validate);
            if (!empty($scene)) {
                $v->scene($scene);
            }
        }
        // 是否批量验证
        if ($batch || $this->batchValidate) {
            $v->batch(true);
        }

        if (is_array($message)) {
            $v->message($message);
        }

        if ($callback && is_callable($callback)) {
            call_user_func_array($callback, [$v, &$data]);
        }

        if (!$v->check($data)) {
            if ($this->failException) {
                throw new ValidateException($v->getError());
            } else {
                return $v->getError();
            }
        } else {
            return true;
        }
    }

    /**
     * 操作成功跳转的快捷方法
     * @access protected
     * @param mixed $msg 提示信息
     * @param mixed $data 返回的数据
     * @param array $header 发送的Header信息
     * @return void
     */
    protected function success($msg = '', $data = '', array $header = [])
    {
        $code   = 1;
        $result = [
            'code' => $code,
            'msg'  => $msg,
            'data' => $data,
        ];

        $type                                   = $this->getResponseType();
        $header['Access-Control-Allow-Origin']  = '*';
        $header['Access-Control-Allow-Headers'] = 'X-Requested-With,Content-Type,RY-Device-Type,RY-Token,RY-Api-Version,RY-Wxapp-AppId';
        $header['Access-Control-Allow-Methods'] = 'GET,POST,PATCH,PUT,DELETE,OPTIONS';
        $response                               = Response::create($result, $type)->header($header);
        throw new HttpResponseException($response);
    }
    /**
     * 操作错误跳转的快捷方法
     * @access protected
     * @param mixed $msg 提示信息,若要指定错误码,可以传数组,格式为['code'=>您的错误码,'msg'=>'您的错误消息']
     * @param mixed $data 返回的数据
     * @param array $header 发送的Header信息
     * @return void
     */
    protected function error($msg = '', $data = '', array $header = [])
    {
        $code = 0;
        if (is_array($msg)) {
            $code = $msg['code'];
            $msg  = lang($msg['msg']);
        }
        $result = [
            'code' => $code,
            'msg'  => lang($msg),
            'data' => $data,
        ];

        $type                                   = $this->getResponseType();
        $header['Access-Control-Allow-Origin']  = '*';
        $header['Access-Control-Allow-Headers'] = 'X-Requested-With,Content-Type,RY-Device-Type,RY-Token';
        $header['Access-Control-Allow-Methods'] = 'GET,POST,PATCH,PUT,DELETE,OPTIONS';
        $response                               = Response::create($result, $type)->header($header);
        throw new HttpResponseException($response);
    }

    /**
     * 获取当前的response 输出类型
     * @access protected
     * @return string
     */
    protected function getResponseType()
    {
        return 'json';
    }

    // --------------------------------------------------------------------
    /**
     * @access        public
     * @example      日记
     * @param        string     variable        explain
     * @author        HJ
     * @version    2019-05-21 18:57:54
     * @return        json
     */
    
    protected function writeLog($data,$type='signIn'){
        $log            = new Log($this->app ?:Container::get('app'));

        if($type == 'signIn'){
             $log->init(['type' => 'File', 'path' => APP_PATH . '../data/runtime/wxapp_login/' ,'time_format'   =>'c']);

            $log->record("-----------------------Start---登录---".date("Y-m-d H:i:s")."----------------------------------");

            if(is_string($data)){
                $log->record($data);
                $log->record($this->request->ip());
            }else{
                $log->record(json_encode($data));
            }
            $log->record("---------------------------------------End-------------------------------------------------\n\n\n\n\n");

        }elseif($type == 'getNotifyData' or $type == 'getNotifyDataError'){

            $log->init(['type' => 'File', 'path' => APP_PATH . '../data/runtime/wxpay_logs/' ,'time_format'   =>'c']);

            if($type == 'getNotifyData'){
                $log->record("-----------------------Start---充值接口回调---".date("Y-m-d H:i:s")."----------------------------------");
            }else{

               $log->record("-----------------------Start---回调报错---".date("Y-m-d H:i:s")."----------------------------------");
            }
            if(is_string($data)){
               $log->record($data);
               $log->record($this->request->ip());

            }else{
               $log->record(json_encode($data));
            }
           $log->record("---------------------------------------End-------------------------------------------------\n\n\n\n\n");
        }elseif($type == 'payErrorAndSql'){

            $log->init(['type' => 'File', 'path' => APP_PATH . '../data/runtime/pay_error_sql/' ,'time_format'   =>'c']);

            $log->record("-----------------------Start---支付回调报错---".date("Y-m-d H:i:s")."----------------------------------");
      
            if(is_string($data)){
               $log->record($data);
               $log->record($this->request->ip());

            }else{
               $log->record(json_encode($data));
            }
           $log->record("---------------------------------------End-------------------------------------------------\n\n\n\n\n");

        }elseif($type == 'payMemberErrorAndSql'){

            $log->init(['type' => 'File', 'path' => APP_PATH . '../data/runtime/pay_member_error_sql/' ,'time_format'   =>'c']);

            $log->record("-----------------------Start---支付回调报错---".date("Y-m-d H:i:s")."----------------------------------");
      
            if(is_string($data)){
               $log->record($data);
               $log->record($this->request->ip());

            }else{
               $log->record(json_encode($data));
            }
           $log->record("---------------------------------------End-------------------------------------------------\n\n\n\n\n");

        }elseif($type == 'retundOrder'){

            $log->init(['type' => 'File', 'path' => APP_PATH . '../data/runtime/retund_order/' ,'time_format'   =>'c']);

            $log->record("-----------------------Start---退款日志---".date("Y-m-d H:i:s")."----------------------------------");
      
            if(is_string($data)){
               $log->record($data);
               $log->record($this->request->ip());

            }else{
               $log->record(json_encode($data));
            }
           $log->record("---------------------------------------End-------------------------------------------------\n\n\n\n\n");

        }elseif($type == 'test'){

            $log->init(['type' => 'File', 'path' => APP_PATH . '../data/runtime/test/' ,'time_format'   =>'c']);

            $log->record("-----------------------Start---测试---".date("Y-m-d H:i:s")."----------------------------------");
      
            if(is_string($data)){
               $log->record($data);
               $log->record($this->request->ip());

            }else{
               $log->record(json_encode($data));
            }
           $log->record("---------------------------------------End-------------------------------------------------\n\n\n\n\n");
        }
       $log->save();

       //  print_r(APP_PATH);
       // var_dump( $log->save());
       $log->close();
    }

    // --------------------------------------------------------------------
    /**
     * @access        public
     * @example       重置 access_token
     * @param        string     variable        explain
     * @author        HJ
     * @version    2019-07-20 10:30:25
     * @return        json
     */
    protected function resetAccessToken()
    {
        $result        = Db::name('user_platform_info')->where('user_id',$this->userId)->data(['access_token' => '','access_token_expire_time' => 0])->update();
        return $this->getAccessToken();
    }

}