<?php

namespace App\Http\Controllers\Api\Common;

use App\Http\Controllers\Api\CommonController;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;

/**
 * Class ConfigController
 * @package App\Http\Controllers\Api\Common
 * @Author YiYuan-LIn
 * @Date: 2019/6/11
 * 获取一些相应配置表
 */
class ConfigController extends CommonController
{
    /**
     * 请求参数
     * @var
     */
    protected $params;

    /**
     * ConfigController constructor.
     */
    public function __construct()
    {
        $this->params = Input::all();
    }

    /**
     * 获取图形验证码
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getVerificationCode()
    {
        try {
            $res = app('captcha')->create('math', true);

            return handleResult(200, '获取验证码成功', $res);
        }catch (\Exception $e) {
            return $this->errorExp($e);
        }
    }
}
