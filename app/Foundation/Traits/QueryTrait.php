<?php
namespace App\Foundation\Traits;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

/**
 * 数据库查询基类
 * Author linyiyuan
 * Trait ApiTrait
 * @package App\Foundation\Traits
 */
trait QueryTrait
{
    /**
     * @Author YiYuan-LIn
     * @Date: 2019/5/11
     * @param $query
     * @param $params
     * @return mixed
     * @description 处理分页条件
     */
    public function pagingCondition($query, $params)
    {
        $cur_page   = $params['cur_page'] ?? 1;
        $page_size  = $params['page_size'] ?? 15;

        $offset = ($cur_page- 1) * $page_size;
        $limit  = $page_size;
        $query = $query->offset($offset)->limit($limit);

        return $query;
    }
}
