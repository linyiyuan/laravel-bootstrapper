<?php

namespace App\Http\Controllers\Api;

use App\Http\Services\TestService;
use App\Models\User;

class TestController extends CommonController
{
    public function test(User $user)
    {
        $new = new TestService(User::class);





    }
}
