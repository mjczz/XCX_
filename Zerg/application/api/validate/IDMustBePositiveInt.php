<?php
/**
 * Created by 七月
 * User: 七月
 * Date: 2017/2/18
 * Time: 12:35
 */
namespace app\api\validate;

//自定义验证规则 ID必须是正整数(验证器)
class IDMustBePositiveInt extends BaseValidate
{
    protected $rule = [
        'id' => 'require|isPositiveInteger',// 自定义验证规则(isPositiveInteger)
        //'num'=>'in:1,2,3',// 加上这个参数，BaseValidate的check方法前就得加batch进行批量验证
    ];
    protected $message = [
        'id' => "id必须是正整数哦",
    ];
}
