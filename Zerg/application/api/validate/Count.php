<?php
/**
 * Created by 七月
 * User: 七月
 * Date: 2017/2/18
 * Time: 12:35
 */

namespace app\api\validate;

class Count extends BaseValidate {
    // 整型，1到15之间
    // 没有定义require,所以可以不传count,可以取默认值
    protected $rule = [
        'count' => 'isPositiveInteger|between:1,15',
    ];
    // 如果屏蔽，则返回框架的错误提示
   /* protected $message = [
        'count' =>'count必须是正整数且在1到15之间',
    ];*/
}
