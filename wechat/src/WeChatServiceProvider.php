<?php
/**
 * @File: WeChatServiceProvider.php
 * @Author: xiongjinhai
 * @Email:562740366@qq.com
 * @Date: 2018/5/11上午9:57
 * @Version:Version:1.1 2017 by www.dsweixin.com All Rights Reserver
 */

namespace Pay\WeChat;


use Illuminate\Support\ServiceProvider;

class WeChatServiceProvider extends ServiceProvider
{

    /**
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__."/config/config.php" => config_path('pay.php'),
        ]);
        $this->mergeConfigFrom(__DIR__."/config/config.php",'pay');
    }
    public function register()
    {
        $this->app->bind('WeChat', 'Pay\WeChat\Pay');

        $this->app->bind('Oauth', 'Pay\WeChat\Oauth');

        $this->app->bind('Notify','Pay\WeChat\Notify');
    }
    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [];
    }
}