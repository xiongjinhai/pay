<?php
/**
 * @File: InvalidResponseException.php
 * @Author: xiongjinhai
 * @Email:562740366@qq.com
 * @Date: 2018/5/15下午1:00
 * @Version:Version:1.1 2017 by www.dsweixin.com All Rights Reserver
 */

namespace Pay\WeChat\Exceptions;


/**
 * 返回异常
 * Class InvalidResponseException
 * @package WeChat
 */
class InvalidResponseException extends \Exception
{
    /**
     * @var array
     */
    public $raw = [];

    /**
     * InvalidResponseException constructor.
     * @param string $message
     * @param integer $code
     * @param array $raw
     */
    public function __construct($message, $code = 0, $raw = [])
    {
        parent::__construct($message, intval($code));
        $this->raw = $raw;
    }

}