<?Php
/*
|--------------------------------------------------------------------------
| API Permission
|--------------------------------------------------------------------------
*/
return [
    // ================== 后台管理 ==================
    [  'id' => 1, 'name' => 'Api:setting', 'display_name' => '设置模块', 'is_display' => 1, 'pid' => 0],

    [  'id' => 2, 'name' => 'Api:setting/auth', 'display_name' => '用户管理', 'is_display' => 1, 'pid' => 1],

    //角色模块
    [  'id' => 3, 'name' => 'Api:setting/auth/role', 'display_name' => '角色管理', 'is_display' => 1, 'pid' => 2],
    [  'id' => 4, 'name' => 'Api:setting/auth/role-index', 'display_name' => '角色列表', 'is_display' => 0, 'pid' => 3],
    [  'id' => 5, 'name' => 'Api:setting/auth/role-store', 'display_name' => '增加角色', 'is_display' => 0, 'pid' => 3],
    [  'id' => 6, 'name' => 'Api:setting/auth/role-update', 'display_name' => '修改角色', 'is_display' => 0, 'pid' => 3],
    [  'id' => 7, 'name' => 'Api:setting/auth/role-destroy', 'display_name' => '删除角色', 'is_display' => 0, 'pid' => 3],

    //权限模块
    [  'id' => 8, 'name' => 'Api:setting/auth/permission', 'display_name' => '权限管理', 'is_display' => 1, 'pid' => 2],
    [  'id' => 9, 'name' => 'Api:setting/auth/permission-index', 'display_name' => '权限列表', 'is_display' => 0, 'pid' => 8],
    [  'id' => 10, 'name' => 'Api:setting/auth/permission-store', 'display_name' => '增加权限', 'is_display' => 0, 'pid' => 8],
    [  'id' => 11, 'name' => 'Api:setting/auth/permission-update', 'display_name' => '修改权限', 'is_display' => 0, 'pid' => 8],
    [  'id' => 12, 'name' => 'Api:setting/auth/permission-destroy', 'display_name' => '删除权限', 'is_display' => 0, 'pid' => 8],

    //用户模块
    [  'id' => 13, 'name' => 'Api:setting/auth/user', 'display_name' => '用户管理', 'is_display' => 1, 'pid' => 2],
    [  'id' => 14, 'name' => 'Api:setting/auth/user-index', 'display_name' => '用户列表', 'is_display' => 0, 'pid' => 13],
    [  'id' => 15, 'name' => 'Api:setting/auth/user-store', 'display_name' => '添加用户', 'is_display' => 0, 'pid' => 13],
    [  'id' => 16, 'name' => 'Api:setting/auth/user-update', 'display_name' => '修改用户', 'is_display' => 0, 'pid' => 13],
    [  'id' => 17, 'name' => 'Api:setting/auth/user-destroy', 'display_name' => '删除用户', 'is_display' => 0, 'pid' => 13],
];


