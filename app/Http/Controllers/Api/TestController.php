<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Services\TestService;
use App\Models\User;

class TestController extends Controller
{
    public function test()
    {
     try {
        echo 1;
     }catch (\Exception $e) {
         return $this->errorExp($e);
     }


    }
}
