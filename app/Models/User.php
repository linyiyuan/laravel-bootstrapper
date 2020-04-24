<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class User
 * @package App\Models
 * @Author YiYuan-LIn
 * @Date: 2020/4/24
 */
class User extends Model
{
    protected $table = 'user';

    public $timestamps = false;

    public static function getOneById($id)
    {
        if (empty($id)) return [];
        return static::query()->where('id', $id)->first()->toArray();
    }

    public static function setUser($data, $id = '')
    {
        $result = static::query();
        if (!empty($id)) {
            $result = $result->where('id', $result)->first();
        }
        foreach ($data as $key => $val) {
            $result->$key = $val;
        }
        return $result->save();
    }
}
