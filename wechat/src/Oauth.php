<?php
/**
 * @File: Oauth.php
 * @Author: xiongjinhai
 * @Email:562740366@qq.com
 * @Date: 2018/5/15下午12:48
 * @Version:Version:1.1 2017 by www.dsweixin.com All Rights Reserver
 */

namespace Pay\WeChat;


use Illuminate\Support\Facades\Config;
use Pay\WeChat\Contracts\DataArray;
use Pay\WeChat\Contracts\Tools;
use Pay\WeChat\Exceptions\InvalidResponseException;
use Pay\WeChat\WxBizDataCrypt\WxBizDataCrypt;

class Oauth
{
    public function __construct()
    {
        $config  = Config::get('pay');

        if (empty($config["appid"])){

            throw new InvalidArgumentException("Missing Config -- [appid]");
        }
        if (empty($config["appsecret"])){

            throw new InvalidArgumentException("Missing Config -- [appid]");
        }
        $this->config = new DataArray($config);
    }

    /**
     * 登录凭证校验
     */
    public function jsCodeSession($code){

        if (empty($code)) return false;

        $appid = $this->config->get('appid');

        $appsecret = $this->config->get('appsecret');

        $parameters = "appid=".$appid."&secret=".$appsecret."&js_code=".$code."&grant_type=authorization_code";

        $url = "https://api.weixin.qq.com/sns/jscode2session?".$parameters;

         try{
            return Tools::json2arr(Tools::get($url));
         }catch (InvalidResponseException $e){

             throw new InvalidResponseException($e->getMessage(), $e->getCode());
         }
    }
    /**
     * 用户数据的签名验证和加解密
     */
    public function wxBizDataCrypt(array $data){

        if (empty($data)) return false;

        $appid = $this->config->get('appid');

        $session_key = $data["session_key"];

        $pc = new WxBizDataCrypt($appid,$session_key);

        $errCode  = $pc->decryptData($data["encryptedData"],$data["iv"],$result);

        if ($errCode == 0) {
            return Tools::json2arr($result);
        } else {
            return $errCode;
        }
    }
}