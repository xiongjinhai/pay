<?php
/**
 * @File: ErrorCode.php
 * @Author: xiongjinhai
 * @Email:562740366@qq.com
 * @Date: 2018/5/15下午2:26
 * @Version:Version:1.1 2017 by www.dsweixin.com All Rights Reserver
 */

namespace Pay\WeChat\WxBizDataCrypt;


class ErrorCode
{

    public static $OK = 0;
    public static $IllegalAesKey = -41001;
    public static $IllegalIv = -41002;
    public static $IllegalBuffer = -41003;
    public static $DecodeBase64Error = -41004;
}