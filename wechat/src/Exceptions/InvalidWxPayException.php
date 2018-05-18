<?php
/**
 * @File: InvalidWxPayException.php
 * @Author: xiongjinhai
 * @Email:562740366@qq.com
 * @Date: 2018/5/18下午3:17
 * @Version:Version:1.1 2017 by www.dsweixin.com All Rights Reserver
 */

namespace Pay\WeChat\Exceptions;


class InvalidWxPayException extends \Exception
{
    public function errorMessage(){

        return $this->getMessage();
    }
}