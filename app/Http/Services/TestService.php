<?php
namespace App\Http\Services;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Redis;
use Tymon\JWTAuth\Facades\JWTAuth;

/**
 * Class BaseService
 * @package App\Http\Services
 * @Author YiYuan-LIn
 * @Date: 2019/4/25
 * 服务基础类
 */
class TestService extends BaseService
{
    protected $params = [];

    public function __construct(User $user)
    {
        parent::__construct();
        $this->params = [
            'data' => []
        ];
    }

    public function getOne(User $user)
    {
    }
}