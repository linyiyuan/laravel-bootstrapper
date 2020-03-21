<?php

namespace App\Http\Controllers\Api\Common;

use App\Http\Controllers\Api\CommonController;
use Illuminate\Support\Facades\Input;

/**
 * Class QueryCriteriaController
 * @package App\Http\Controllers\Api\Common
 * @Author YiYuan-LIn
 * @Date: 2019/6/4
 * 公共查询条件项
 */
class QueryCriteriaController extends CommonController
{
    /**
     * 请求参数
     * @var
     */
    protected $params;

    /**
     * QueryCriteriaController constructor.
     */
    public function __construct()
    {
        $this->params = Input::all();
    }


}
