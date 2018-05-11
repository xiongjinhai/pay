<?php
/**
 * @File: InvalidArgumentException.php
 * @Author: xiongjinhai
 * @Email:562740366@qq.com
 * @Date: 2018/5/11下午5:01
 * @Version:Version:1.1 2017 by www.dsweixin.com All Rights Reserver
 */

namespace Pay\WeChat\Exceptions;

use Throwable;

class InvalidArgumentException extends \InvalidArgumentException
{

    public $previous = [];

    public function __construct(string $message = "", int $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);

        $this->previous = $previous;
    }
}