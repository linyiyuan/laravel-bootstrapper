<?php

namespace App\Http\Controllers\Api\AdminData\Delivery;

use App\Http\Controllers\Api\CommonController;
use App\Models\AdminData\Delivery\AdGroup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

/**
 * Class DeliveryUserController
 * @package App\Http\Controllers\Api\AdminData\PutInData
 * @Author YiYuan-LIn
 * @Date: 2020/3/18
 */
class DeliveryUserController extends CommonController
{
    /**
     * 请求参数
     * @var string json
     */
    protected $params;

    public function __construct()
    {
        $this->params = Input::all();
    }

    /**
     * 获取投放人员数据集
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        try {
            $adGroupQuery = AdGroup::query();

            $adGroupQuery = $adGroupQuery->from('ad_group as a');
            $adGroupQuery = $adGroupQuery->leftJoin('ad_usergroup as b', 'a.ad_usergroup_id', 'b.id');
            $adGroupQuery = $adGroupQuery->select('a.*', 'b.name as group_name');
            $adGroupQuery = $adGroupQuery->orderBy('a.id', 'asc');

            $total = $adGroupQuery->count();
            $adGroupQuery = $this->pagingCondition($adGroupQuery, $this->params);

            return handleResult(200, '获取数据成功', [
                'list' => $adGroupQuery->get()->toArray(),
                'total' => $total
            ]);
        }catch (\Exception $e) {
            return $this->errorExp($e);
        }
    }

    /**
     * 添加投放人员
     *
     * @param Request $request
     */
    public function store(Request $request)
    {

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
