<?php
/**
 * @File: Notify.php
 * @Author: xiongjinhai
 * @Email:562740366@qq.com
 * @Date: 2018/5/18下午2:36
 * @Version:Version:1.1 2017 by www.dsweixin.com All Rights Reserver
 */

namespace Pay\WeChat;

use Pay\WeChat\Contracts\Tools;
use Pay\WeChat\Exceptions\InvalidWxPayException;

class Notify
{
    public function handle($needSign = true)
    {
        $result = self::notify(array($this, 'NotifyCallBack'), $msg);

        if ($result == false) {
            $xml["return_code"] = "FAIL";
            $xml["return_msg"] = !empty($msg) ? $msg : "ERROR";
            $arr2xml = Tools::arr2xml($xml);
            $this->ReplyNotify(false,$arr2xml);
            return;
        } else {
            //该分支在成功回调到NotifyCallBack方法，处理完成之后流程
            $xml["return_code"] = "SUCCESS";
            $xml["return_msg"]  = "OK";
            $arr2xml = Tools::arr2xml($xml);
        }
        $this->ReplyNotify($needSign,$arr2xml);
    }
    /**
     * notify回调方法，该方法中需要赋值需要输出的参数,不可重写
     * @param array $data
     * @return true回调出来完成不需要继续回调，false回调处理未完成需要继续回调
     */
    final public function NotifyCallBack($data)
    {
        $msg = "OK";

        $result = $this->NotifyProcess($data, $msg);

        if ($result == true) {
            //这里处理自己业务逻辑地方,成功返回true,失败false
            $call_back = self::postDataCurl(config('pay.notify_url_logic'),$data);
            if ($call_back == "true"){
                $xml["return_code"] = "SUCCESS";
                $xml["return_msg"] = "OK";
            }else{
                $result = false;
                $xml["return_code"] = "FAIL";
                $xml["return_msg"]  = $msg;
            }
        } else {
            $xml["return_code"] = "FAIL";
            $xml["return_msg"] = $msg;
        }

        Tools::arr2xml($xml);

        return $result;
    }
    //通过curl模拟post的请求；
    protected  function postDataCurl($url,$data=array()){
        //对空格进行转义
        $url = str_replace(' ','+',$url);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); //如果需要将结果直接返回到变量里，那加上这句。
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }
    /**
     *
     * 回调方法入口，子类可重写该方法
     * 注意：
     * 1、微信回调超时时间为2s，建议用户使用异步处理流程，确认成功之后立刻回复微信服务器
     * 2、微信服务器在调用失败或者接到回包为非确认包的时候，会发起重试，需确保你的回调是可以重入
     * @param array $data 回调解释出的参数
     * @param string $msg 如果回调处理失败，可以将错误信息输出到该方法
     * @return true回调出来完成不需要继续回调，false回调处理未完成需要继续回调
     */
    public function NotifyProcess($data, &$msg)
    {
        if (!array_key_exists("transaction_id", $data)) {
            $msg = "输入参数不正确";
            return false;
        }
        //查询订单，判断订单真实性
        if (!$this->Queryorder($data)) {
            $msg = "订单查询失败";
            return false;
        }
        return true;
    }

    //查询订单
    public function Queryorder($data)
    {
        if (empty($data["transaction_id"]) && empty($data["out_trade_no"])) {

            throw new InvalidWxPayException("订单查询接口中，out_trade_no、transaction_id至少填一个！");
        }

        $options = array(
            "out_trade_no" => $data["out_trade_no"],
            "transaction_id" => $data["transaction_id"],
        );
        $result = app(Pay::class)->queryOrder($options);

        if (array_key_exists("return_code", $result) && array_key_exists("result_code", $result) && $result["return_code"] == "SUCCESS" && $result["result_code"] == "SUCCESS") {
            return true;
        }
        return false;
    }
    public function ReplyNotify($needSign = true,$xml){
        //如果需要签名
        if($needSign == true && $xml["return_code"] == "SUCCESS") {
            //$this->SetSign();没有实现
        }
        echo $xml;
    }
    /*$xml = "<xml><appid><![CDATA[wx05ad3d3ccbea8ce6]]></appid>
    <bank_type><![CDATA[CFT]]></bank_type>
    <cash_fee><![CDATA[1]]></cash_fee>
    <fee_type><![CDATA[CNY]]></fee_type>
    <is_subscribe><![CDATA[N]]></is_subscribe>
    <mch_id><![CDATA[1503359491]]></mch_id>
    <nonce_str><![CDATA[nccypuj8s8y1ppcto60u4nwww6uvpd0d]]></nonce_str>
    <openid><![CDATA[o8jfi5KadnMGFAG2pBkTwnnT3kGI]]></openid>
    <out_trade_no><![CDATA[5990579967388781001]]></out_trade_no>
    <result_code><![CDATA[SUCCESS]]></result_code>
    <return_code><![CDATA[SUCCESS]]></return_code>
    <sign><![CDATA[E4895BA374A65DC1599D062A60AF7882]]></sign>
    <time_end><![CDATA[20180518140320]]></time_end>
    <total_fee>1</total_fee>
    <trade_type><![CDATA[JSAPI]]></trade_type>
    <transaction_id><![CDATA[4200000128201805182270068135]]></transaction_id>
    </xml>";*/
    /**
     * @param $callback
     * @param $msg
     * @return bool|mixed
     */
    final protected function notify($callback,&$msg){
        //获取通知的数据
        $xml = file_get_contents('php://input');

        //如果返回成功则验证签名
        try{

            $result = Tools::xml2arr($xml);

        }catch (InvalidWxPayException $e){

            $msg = $e->getMessage();

            return false;
        }

        return call_user_func($callback,$result);
    }
}
