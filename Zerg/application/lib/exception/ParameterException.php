<?php
/**
 * Created by PhpStorm.
 * User: 七月
 * Date: 2017/2/12
 * Time: 18:29
 */

namespace app\lib\exception;

/**
 * Class ParameterException
 * 通用参数类异常错误
 */
class ParameterException extends BaseException
{
    /*覆盖父类BaseException里的3个成员变量*/
    public $code = 400;
    public $errorCode = 10000;
    public $msg = "invalid parameters";
}