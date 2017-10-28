<?php
namespace app\lib\exception;
use think\Exception;

/**
 * Class BaseException
 * 自定义异常类的基类
 */
// BaseException用于定义异常返回信息(错误信息最终由render方法处理后再返回格式到用户)
class BaseException extends Exception
{
    public $code = 400; // http状态码
    public $msg = 'invalid parameters'; // 错误信息
    public $errorCode = 999;// 错误码

    public $shouldToClient = true;

    /**
     * 构造函数，接收一个关联数组
     * @param array $params 关联数组只应包含code、msg和errorCode，且不应该是空值
     */
    // 在初始化对象的时候通过构造函数对成员变量做初始化的赋值(构造函数在父类里面)
    public function __construct($params=[])
    {
        if(!is_array($params)){
            return;
        }
        // 判断code是否在数组$params中
        if($a = array_key_exists('code',$params)){
            $this->code = $params['code'];
        }
        if($b = array_key_exists('msg',$params)){
            $this->msg = $params['msg'];
        }
        if(array_key_exists('errorCode',$params)){
            $this->errorCode = $params['errorCode'];
        }
    }
}

