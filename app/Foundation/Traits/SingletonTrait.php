<?php

namespace App\Foundation\Traits;
/**
 * 单例模式基类
 * Class SingletonTrait
 * @package App\Foundation\Traits
 * @Author YiYuan-LIn
 * @Date: 2020/9/14
 */
class SingletonTrait
{
    /**
     * 私有属性，用于保存实例
     * @var  object
     */
    private static $instance;

    /**
     * 构造方法私有化，防止外部创建实例
     * SingletonTrait constructor.
     */
    private function __construct(){}

    /**
     * 公有方法，用于获取实例
     * @Author YiYuan-LIn
     * @Date: 2020/9/14
     * @enumeration:
     * @return SingletonTrait|object
     */
    public static function getInstance()
    {
        //判断实例有无创建，没有的话创建实例并返回，有的话直接返回
        if(!(self::$instance instanceof self)){
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * 克隆方法私有化，防止复制实例
     * @Author YiYuan-LIn
     * @Date: 2020/9/14
     * @enumeration:
     */
    private function __clone(){}

}