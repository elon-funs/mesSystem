<?php
// +----------------------------------------------------------------------
// |
// +----------------------------------------------------------------------

namespace api\demo\controller;

use cmf\controller\RestBaseController;

class IndexController extends RestBaseController
{
    public function index()
    {
        $this->success('请求成功!', ['test'=>'test']);
    }
}
