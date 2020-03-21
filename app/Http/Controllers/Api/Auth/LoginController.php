<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Api\CommonController;
use App\Http\Utils\RecordOperate;
use App\Models\Auth\Users;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tymon\JWTAuth\Facades\JWTAuth;

/**
 * Class LoginController
 * @package App\Http\Controllers\Api\Auth
 * @Author YiYuan-LIn
 * @Date: 2019/5/8
 * 登录控制器
 */
class LoginController extends CommonController
{
    /**
     * @author YiYuan Lin
     * @date 18/11/21
     * @describe
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        try{
            $params = [
                'username' => $request->username ?? '',
                'password' => $request->password ?? '',
                'captcha' =>  intval($request->captcha ?? '')
            ];

            //配置验证
            $rules = [
                'username' => 'required',
                'password' => 'required|min:6|max:18',
                'captcha' => 'required|captcha_api:' . $request->input('key')
            ];
            $message = [
                'username.required' => ' username 缺失',
                'password.required' => ' password 缺失',
                'password.min' => ' password 最少6位数',
                'password.max' => ' password 最多18位数',
                'captcha.required' => 'captcha 缺失',
                'captcha.captcha_api' => '验证码不正确',
            ];
            $this->verifyParams($params, $rules, $message);

            //获取用户信息
            $user = Users::query()->where([[ 'username',$params['username'] ]])->first();

            //检查用户以及密码是否正确
            if (empty($user)) $this->throwExp(400,'登录失败，用户不存在');
            if (md5($params['password']) != $user->password) $this->throwExp(400,'登录失败，用户验证失败，密码错误');
            if (!$token = JWTAuth::fromUser($user)) $this->throwExp(400,'登录失败，用户验证失败，请检查用户名以及密码');

            //检查账户是否被停用
            if ($user['status'] != 1)  $this->throwExp(400,'该账户已经被停用，请联系管理员');

            //更新用户信息
            $user->last_login   = time();
            $user->last_ip      = getClientIp();
            $user->save();

            //记录登录操作日志
            RecordOperate::recordOperate($user->id, '登录操作', json_encode($request->all()), '登录成功');
            return $this->respondWithToken($token);
        }catch (\Exception $e){
            return $this->errorExp($e);

        }
    }

    /**
     * @author YiYuan Lin
     * @date 18/11/22
     * @describe 返回的JWT信息
     * @param $token
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        $data = [
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60,
        ];

        return handleResult(200, '登录成功', $data);
    }

    /**
     * @Author YiYuan-LIn
     * @Date: 2019/5/8
     * @param Request $request
     * @return mixed
     * @description 退出登录操作
     */
    public function logOut(Request $request)
    {
        try {
            //检查Token有效性
            if (JWTAuth::parseToken()->check()) {
                //退出操作，将Jwt拉进黑名单
                JWTAuth::parseToken()->invalidate(true);

                return handleResult(200, '退出登录成功', []);
            }else{
                $this->throwExp(200, '退出登录失败', []);
            }
        }catch (\Exception $e) {
            return $this->errorExp($e);
        }
    }

    /**
     * @Author YiYuan-LIn
     * @Date: 2019/5/9
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     * @description 获取用户信息
     */
    public function getInfo()
    {
        try {
            $user = JWTAuth::user();
            if (empty($user)) $this->throwExp(401, '获取用户信息失败');

            //处理用户角色
            $roles = $user->getRoleNames()->toArray();

            //处理用户权限
            $permissionList = $user->getAllPermissions()->toArray();
            $permissionList = array_column($permissionList, 'name');

            $permissions = array_map(function($val){
                return  str_replace('Controller', '', $val);
            }, $permissionList);

            $user                   = $user->toArray();
            $user['role']           = $roles;
            $user['permission']     = $permissions;

            return handleResult(200, 'success', $user);
        }catch (\Exception $e) {
            return $this->errorExp($e);
        }
    }

    /**
     * @Author YiYuan-LIn
     * @Date: 2019/5/7
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @description 分配权限给角色
     */
    public function givePermission(Request $request)
    {
        try {
            $postData = $request->postData;
            $params = [
                'roleId' => $postData['roleId'],
                'permissionsAllow' => $postData['permissionsAllow']
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
            $role = Role::findById($params['roleId']);

            DB::beginTransaction();

            //先清空当前用户所有权限
            DB::table('sy_role_has_permissions')
                ->where('role_id', $params['roleId'])
                ->delete();

            foreach ($params['permissionsAllow'] as $key) {
                if (!$role->givePermissionTo($key)) $this->throwExp('添加角色权限失败');
            }

            DB::commit();

            return handleResult(200, '添加角色权限成功');

        } catch (\Exception $e) {
            return $this->errorExp($e);
        }
    }

    /**
     * @Author YiYuan-LIn
     * @Date: 2019/5/7
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @description 分配权限给用户
     */
    public function giveUserPermission(Request $request)
    {
        try {
            $postData = $request->postData ?? '';
            $params = [
                'userId' => $postData['userId'],
                'permissionsAllow' => $postData['permissionsAllow']
            ];
            $rules = [
                'userId'            => 'required',
                'permissionsAllow'  => 'required',
            ];
            $message = [
                'userId.required'           => '[userId]缺失',
                'permissionsAllow.required' => '请至少选择一个权限',
            ];

            $this->verifyParams($params, $rules, $message);

            //根据用户获取相应所有权限列表
            $user = Users::query()->where('id', $params['userId'])->first();
            $permissionList = $user->getAllPermissions()->toArray();
            $permissionList = array_column($permissionList, 'name');

            DB::beginTransaction();

            //先清空当前用户所有权限
            DB::table('sy_model_has_permissions')
                    ->where('model_id', $params['userId'])
                    ->delete();

            foreach ($params['permissionsAllow'] as $key) {
                if (!in_array($key, $permissionList)) {
                    $this->checkoutPisIsFirst($user, $key);
                    if (!$user->givePermissionTo($key)) $this->throwExp('添加用户权限失败');
                }
            }

            DB::commit();
            return handleResult(200, '添加用户权限成功');
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorExp($e);
        }
    }

    /**
     * @Author YiYuan-LIn
     * @Date: 2019/10/10
     * @enumeration:
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @description 重置密码操作
     */
    public function resetPassword(Request $request)
    {
        try {
            $postData = $request->postData;

            $data = [
                'uid' => $postData['uid'],
                'new_password' => $postData['new_password'] ?? '',
                'confirm_password' => $postData['confirm_password'] ?? '',
            ];

            //配置验证
            $rules = [
                'uid' => 'required',
                'new_password' => 'required',
                'confirm_password' => 'required',
            ];
            $message = [
                'uid.required' => '[uid]缺失',
                'new_password.required' => '[new_password]缺失',
                'confirm_password.required' => '[confirm_password]缺失',
            ];

            $this->verifyParams($data, $rules, $message);

            $userInfo = Users::where('id', $data['uid'])->first();

            if (empty($userInfo)) $this->throwExp(400, '账号不存在');
            if (md5($data['new_password']) != md5($data['confirm_password'])) $this->throwExp(400, '两次密码输入不一致');

            $userInfo->password  = md5($data['new_password']);
            $updateRes = $userInfo->save();

            if (!$updateRes) $this->throwExp(400, '修改密码失败');

            return handleResult(200, '修改密码成功');
        }catch (\Exception $e) {
            return $this->errorExp($e);
        }
    }

    /**
     * 检查权限是否为最顶级权限，如果不是则递归添加
     *
     * @param Users $user
     * @param $key
     * @return bool
     */
    private function checkoutPisIsFirst(Users $user, $key)
    {
        $permission = Permission::query()->where('name', $key)->first();
        if ($permission['pid'] != 0) {
            $permissionAdd = Permission::query()->where('id', $permission['pid'])->first();
            $user->givePermissionTo($permissionAdd['name']);
            $this->checkoutPisIsFirst($user, $permissionAdd['name']);
        }else{
            return true;
        }

    }
}
