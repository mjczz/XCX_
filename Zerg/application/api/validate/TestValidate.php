<?php
/**
 * Created by PhpStorm.
 * User: LBJ
 * Date: 2017/9/20
 * Time: 1:31
 */

namespace app\api\validate;

use app\api\controller\BaseController;
use think\Validate;

// 验证器
class TestValidate extends BaseValidate {
    // 验证规则作为该类的成员变量
    protected $rule = ['name' => 'require|max:10',// tp5内置的验证规则
                       'email' => 'email',// tp5内置的验证规则
                       'id' => 'require|isPositiveInteger',// 自定义验证规则
    ];

    protected $message = [
        'name.require'=>'小伙子,name不允许为空',
    ];

    // 自定义验证规则($value是参数的值，）
    protected function isPositiveInteger($value, $rule = '', $data = '', $field = ''){
        // 是否是数字，是否是整型，是否大于0
        if(is_numeric($value) && is_int($value + 0) && ($value + 0) > 0){
            return true;
        }
        // $field是验证的参数
        return $field. '必须是正整数哦';
    }


}