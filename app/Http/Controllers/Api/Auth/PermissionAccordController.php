<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Api\CommonController;
use App\Models\Auth\Roles;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;

class PermissionAccordController extends CommonController
{
    /**
     * 请求参数
     * @var
     */
    protected $params = [];

    public function __construct()
    {
        $this->params = Input::toArray();
    }

    public function giveRoleDataPermission(Request $request)
    {
        try {
            $postData = $request->postData ?? [];
            $params = [
                'roleId' => $postData['roleId'] ?? '',
                'permissionsAllow' => $postData['permissionsAllow'] ?? []
            ];
            //配置验证
            $rules = [
                'roleId'            => 'required',
                'permissionsAllow'  => 'required',
            ];
            $message = [
                'roleId.required'           => '[roleId]缺失',
                'permissionsAllow.required' => '请至少选择一个权限',
            ];

            $this->verifyParams($params, $rules, $message);
            $role = Roles::findById($params['roleId']);

            DB::beginTransaction();

            //先清空当前用户所有权限
            DB::table('sy_role_has_data_permissions')
                ->where('role_id', $params['roleId'])
                ->delete();

            foreach ($params['permissionsAllow'] as $key) {
                if (!$role->giveDataPermissionTo($key)) $this->throwExp(400,'添加角色权限失败');
            }

            DB::commit();

            return handleResult(200, '添加角色权限成功');

        } catch (\Exception $e) {
            return $this->errorExp($e);
        }
    }
}
