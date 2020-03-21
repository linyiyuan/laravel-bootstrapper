<?php

namespace App\Models\Auth;

use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Traits\HasRoles;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Users extends Authenticatable implements JWTSubject
{
    use HasRoles; //加载角色相关信息

    protected $table = 'sy_users';

    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username', 'password'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
    ];

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    /**
     * 管理数据权限模型
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function dataPermissions()
    {
        return $this->belongsToMany('App\Models\Auth\DataPermission', 'sy_model_has_data_permissions', 'model_id', 'data_permission_id');
    }

    /**
     * 获取用户拥有的数据权限
     *
     * @return array
     */
    public function getUserDataPermission()
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
     * 获取用户所拥有的全部数据权限（包含角色数据权限）
     *
     * @return array
     */
    public function getAllDataPermissions()
    {
        //获取用户所属角色的全部权限
        $roleHasDataPermissions = [];
        foreach($this->roles as $key) {
            $roleHasDataPermission = Roles::query()->where('id', $key['id'])->first()->getAllDataPermissions();
            $roleHasDataPermissions = array_merge($roleHasDataPermissions, $roleHasDataPermission);
        }
        $roleHasDataPermissions = array_column($roleHasDataPermissions, null, 'name');

        //获取用户所拥有的全部权限
        $userHasDataPermissions = $this->getUserDataPermission();
        $userHasDataPermissions = array_column($userHasDataPermissions, null, 'name');

        return array_merge($roleHasDataPermissions, $userHasDataPermissions);

    }

    /**
     * @Author YiYuan-LIn
     * @Date: 2019/10/10
     * @enumeration:
     * @param $id
     * @return array|\Illuminate\Database\Eloquent\Builder|Model|object|null
     * @description 根据ID获取一条用户信息
     */
    public static function getOneByUid($id)
    {
        if (empty($id)) return [];

        $query = static::query();
        $query = $query->where('id', $id);

        return $query->first();
    }
}
