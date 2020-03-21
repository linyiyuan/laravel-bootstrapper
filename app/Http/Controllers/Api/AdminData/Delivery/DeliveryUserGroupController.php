<?php

namespace App\Http\Controllers\Api\AdminData\Delivery;

use App\Http\Controllers\Api\CommonController;
use App\Models\AdminData\Delivery\AdUserGroup;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;

class DeliveryUserGroupController extends CommonController
{
    /**
     * 请求参数
     * @var
     */
    protected $params;

    public function __construct()
    {
        $this->params = Input::all();
    }

    /**
     * 获取列表数据
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        try {
            $type = $this->params['type'] ?? '';
            switch ($type) {
                case 'getSearchList':
                    $result = $this->getSearchList();
                    break;
                default:
                    $result = $this->getList();
                    break;
            }

            return handleResult(200, '获取数据成功', $result);
        } catch (\Exception $e) {
            return $this->errorExp($e);
        }
    }

    protected function getSearchList()
    {

    }

    /**
     * 获取投放组列表数据
     *
     * @return array
     */
    protected function getList()
    {
        $adUserGroupQuery = AdUserGroup::query();
        $total = $adUserGroupQuery->count();

        $adUserGroupQuery = $this->pagingCondition($adUserGroupQuery, $this->params);

        return [
            'list' => $adUserGroupQuery->get()->toArray(),
            'total' => $total
        ];
    }

    /**
     * 添加投放组
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        try {
            $data = $request->postData ?? '';

            $params = [
                'name'  => $data['name'] ?? '',
            ];

            //配置验证
            $rules = [
                'name'  => 'required',
            ];

            //错误信息
            $message = [
                'name.required' => '[name]缺失',
            ];
            $this->verifyParams($params, $rules, $message);

            $adUserGroupQuery = new AdUserGroup();
            $adUserGroupQuery->name = $data['name'];
            $adUserGroupQuery->orderby = $data['orderby'];

            if (!$adUserGroupQuery->save()) $this->throwExp(400, '添加投放组失败');
            //正确返回信息
            return handleResult(200, '添加投放组成功');
        } catch (\Exception $e) {
            return $this->errorExp($e);
        }
    }

    /**
     * 更新投放组
     *
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        try {
            $data = $request->postData ?? '';

            $params = [
                'name'  => $data['name'] ?? '',
                'id'  => $data['id'] ?? '',
            ];

            //配置验证
            $rules = [
                'name'  => 'required',
                'id'  => 'required',
            ];

            //错误信息
            $message = [
                'name.required' => '[name]缺失',
                'id.required' => '[id]缺失',
            ];
            $this->verifyParams($params, $rules, $message);

            $adUserGroupQuery = AdUserGroup::find($id);
            $adUserGroupQuery->name = $data['name'];
            $adUserGroupQuery->orderby = $data['orderby'];

            if (!$adUserGroupQuery->save()) $this->throwExp(400, '修改投放组失败');
            //正确返回信息
            return handleResult(200, '修改投放组成功');
        } catch (\Exception $e) {
            return $this->errorExp($e);
        }
    }

    /**
     * 删除投放组
     *
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        try {
            if (!intval($id)) return handleResult(500, '参数错误');

            if (!AdUserGroup::destroy($id)) return handleResult(500, '删除失败');
            return handleResult(200, '删除成功');
        }catch (\Exception $e) {
            return $this->errorExp($e);
        }

    }
}
