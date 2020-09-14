<?php
namespace App\Http\Services;

use App\Foundation\Traits\SingletonTrait;
use App\Models\User;

/**
 * Class BaseService
 * @package App\Http\Services
 * @Author YiYuan-LIn
 * @Date: 2019/4/25
 * 服务基础类
 */
class TestService extends BaseService
{
    use SingletonTrait;

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