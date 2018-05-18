<?php
/**
 * @File: Notify.php
 * @Author: xiongjinhai
 * @Email:562740366@qq.com
 * @Date: 2018/5/18下午2:34
 * @Version:Version:1.1 2017 by www.dsweixin.com All Rights Reserver
 */

namespace Pay\WeChat\Facades;

use Illuminate\Support\Facades\Facade;

class Notify extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'Notify';
    }
}