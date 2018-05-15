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


### 小程序校验和解密

    use Pay\WeChat\Facades\Oauth;
    
    Oauth::jsCodeSession($code);//登录凭证校验
    
    Oauth::wxBizDataCrypt($data);//用户数据的签名验证和加解密 
    
##### [微信API](https://developers.weixin.qq.com/miniprogram/dev/api/signature.html#wxchecksessionobject)
