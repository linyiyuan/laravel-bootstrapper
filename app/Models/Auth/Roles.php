<?php
namespace App\Models\Auth;

use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

class Roles extends Role
{

    /**
     * 管理数据权限模型
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function dataPermissions()
    {
        return $this->belongsToMany('App\Models\Auth\DataPermission', 'sy_role_has_data_permissions', 'role_id', 'data_permission_id');
    }

    /**
     * 获取角色所拥有的全部数据权限
     *
     * @return array
     */
    public function getAllDataPermissions()
    {
        $allDataPermissions = [];
        foreach($this->dataPermissions as $key) {
            array_push($allDataPermissions, [
                'id' => $key['id'],
                'name' => $key['name'],
                'display_name' => $key['display_name'],
                'pid' => $key['pid'],
            ]);
        }
        return $allDataPermissions;
    }

    /**
     * 给予数据权限给某个角色组
     *
     * @param string $dataPermission
     * @return bool
     */
    public function giveDataPermissionTo(string $dataPermission)
    {
        if (empty($dataPermission)) return false;

        $dataPermissionId = DataPermission::query()->where('name', $dataPermission)->value('id');

        $insertRes = DB::table('sy_role_has_data_permissions')->insert([
            'role_id' => $this->id,
            'data_permission_id' => $dataPermissionId
        ]);
        if (!$insertRes) return false;

        return true;
    }
}