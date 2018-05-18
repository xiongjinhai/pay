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
    $data = Array
    (
        [result] => 1
        [data] => Array
            (
                [appid] => wx05ad3d3ccbea8ce6
                [bank_type] => CFT
                [cash_fee] => 2000
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
                [total_fee] => 2000
                [trade_type] => JSAPI
                [transaction_id] => 4.2000001282018E+27
            )
    
        [return_code] => SUCCESS
        [return_msg] => OK
    )
     //在业务逻辑处理好添加下面一段代码ok,处理失败也需要输出这段
     $xml = array(
         "return_code" => $data["return_code"],
         "return_msg"  => $data["return_msg"],
     );
     Notify::ReplyNotify($xml);
     
### 小程序校验和解密

    use Pay\WeChat\Facades\Oauth;
    
    Oauth::jsCodeSession($code);//登录凭证校验
    
    Oauth::wxBizDataCrypt($data);//用户数据的签名验证和加解密 
       
    
##### [微信API](https://developers.weixin.qq.com/miniprogram/dev/api/signature.html#wxchecksessionobject)
