<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Services\TestService;

class TestController extends Controller
{
    public function test()
    {
         try {
            $test = TestService::getInstance()->getOne();
         }catch (\Exception $e) {
             return $this->errorExp($e);
         }
    }
}
