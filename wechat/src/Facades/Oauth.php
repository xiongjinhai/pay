<?php
/**
 * @File: Wechat.php
 * @Author: xiongjinhai
 * @Email:562740366@qq.com
 * @Date: 2018/5/11下午1:58
 * @Version:Version:1.1 2017 by www.dsweixin.com All Rights Reserver
 */
namespace Pay\WeChat\Facades;

use Illuminate\Support\Facades\Facade;

class Oauth extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'Oauth';
    }
}