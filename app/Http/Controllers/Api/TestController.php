<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Services\TestService;

/**
 * 测试控制器
 * Class TestController
 * @package App\Http\Controllers\Api
 * @Author YiYuan-Lin
 * @Date: 2020/9/15
 */
class TestController extends Controller
{
    /**
     * 测试方法
     * @return \Illuminate\Http\JsonResponse
     */
    public function test()
    {
         try {
            $user = TestService::getInstance()->getOne();

            return $this->success([
                'list' => $user
            ]);
         }catch (\Exception $e) {
             return $this->errorExp($e);
         }
    }
}
