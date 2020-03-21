<?php

namespace App\Models\AdminData\Delivery;

use Illuminate\Database\Eloquent\Model;

/**
 * Class AdGroup
 * @package App\Models\AdminData\Delivery
 * @Author YiYuan-LIn
 * @Date: 2020/3/18
 */
class AdGroup extends Model
{
    /**
     * 数据库表
     * @var
     */
    protected $table = 'ad_group';

    /**
     * 时间戳自动更新开关
     * @var
     */
    public $timestamps = false;
}
