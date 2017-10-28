<?php
/**
 * Created by 七月
 * User: 七月
 * Date: 2017/2/18
 * Time: 12:35
 */
namespace app\api\validate;

class TokenGet extends BaseValidate
{
    // code=也是可以通过require的，所以再加一个自定义验证规则isNotEmpty
    // require 不能判断空值
    protected $rule = [
        'code' => 'require|isNotEmpty'
    ];
    
    protected $message=[
        'code' => '没有code还想拿token？做梦哦!!!!!!!!!!!!!!!!!!'
    ];
}
