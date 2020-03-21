<?php

namespace App\Models\AdminData\Delivery;

use Illuminate\Database\Eloquent\Model;

class AdUserGroup extends Model
{
    /**
     * 数据库表
     * @var
     */
    protected $table = 'ad_usergroup';

    /**
     * 时间戳自动更新开关
     * @var
     */
    public $timestamps = false;
}
