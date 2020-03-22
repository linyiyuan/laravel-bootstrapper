<?php
namespace App\Http\Services;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Redis;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Controllers\Api\ServerController;

/**
 * Class BaseService
 * @package App\Http\Services
 * @Author YiYuan-LIn
 * @Date: 2019/4/25
 * 服务基础类
 */
class  TestService extends BaseService
{
    public function getOne(User $user)
    {
    }
}