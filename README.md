# 适合laravel框架使用

### Composer

Execute the following command to get the latest version of the package:

```terminal
composer require dsweixin/pay
```


Publish Configuration

```shell
php artisan vendor:publish --provider "Pay\WeChat\WeChatServiceProvider"
```
```
config目录下面会生成pay.php文件配置相关参数
```
## Methods

### 小程序支付创建订单

    use Pay\WeChat\Facades\WeChat;
    
    // 4. 组装参数，可以参考官方商户文档
    $data = [
        'body'             => '测试商品',
        'out_trade_no'     => time(),
        'total_fee'        => '1',
        'openid'           => 'o8jfi5KadnMGFAG2pBkTwnnT3kGI',
        'spbill_create_ip' => '127.0.0.1',
        'trade_type'       => 'JSAPI',
        'notify_url'       => config('pay.notify_url'),
    ];
    $result  =  WeChat::createOrder($data);
    // 创建JSAPI参数签名
    $options = WeChat::createParamsForJsApi($result['prepay_id']);

### 小程序支付回调处理

    use Pay\WeChat\Facades\Notify;

    Notify::handle(false);
    
    上面代码放回调通知地址方法即可

    需要在配置文件 notify_url_logic=>''填写业务逻辑(路由必须是get方法)
    Array
    (
        [appid] => wx05ad3d3ccbea8ce6
        [bank_type] => CFT
        [cash_fee] => 1
        [fee_type] => CNY
        [is_subscribe] => N
        [mch_id] => 1503359491
        [nonce_str] => nccypuj8s8y1ppcto60u4nwww6uvpd0d
        [openid] => o8jfi5KadnMGFAG2pBkTwnnT3kGI
        [out_trade_no] => 5990579967388781001
        [result_code] => SUCCESS
        [return_code] => SUCCESS
        [sign] => E4895BA374A65DC1599D062A60AF7882
        [time_end] => 20180518140320
        [total_fee] => 1
        [trade_type] => JSAPI
        [transaction_id] => 4200000128201805182270068135
    )
    
### 小程序校验和解密

    use Pay\WeChat\Facades\Oauth;
    
    Oauth::jsCodeSession($code);//登录凭证校验
    
    Oauth::wxBizDataCrypt($data);//用户数据的签名验证和加解密 
       
    
##### [微信API](https://developers.weixin.qq.com/miniprogram/dev/api/signature.html#wxchecksessionobject)
