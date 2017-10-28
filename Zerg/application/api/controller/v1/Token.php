<?php
/**
 * Created by 七月
 * Author: 七月
 * 微信公号: 小楼昨夜又秋风
 * 知乎ID: 七月在夏天
 * Date: 2017/2/21
 * Time: 12:23
 */

namespace app\api\controller\v1;


use app\api\service\AppToken;
use app\api\service\UserToken;
use app\api\service\Token as TokenService;
use app\api\validate\AppTokenGet;
use app\api\validate\TokenGet;
use app\lib\exception\ParameterException;

/**
 * 获取令牌，相当于登录
 */
class Token {
    /**
     * 用户获取令牌（登陆）
     * @url /token
     * @POST code
     * @note 虽然查询应该使用get，但为了稍微增强安全性，所以使用POST
     */
    public function getToken($code = '')
    {

        (new TokenGet())->goCheck();// 1.校验参数
        $wx = new UserToken($code);// 2.实例化(实例化，构造函数里自动配置好通过code获取openid的url)
        $token = $wx->get();// 3.得到token
        // 4.返回到客户端
        return [
            'token' => $token
        ];
    }

    /**
     * 第三方应用获取令牌
     * @url /app_token?
     * @POST ac=:ac se=:secret
     */
    public function getAppToken($ac = '', $se = '')
    {
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
        header('Access-Control-Allow-Methods: GET');
        (new AppTokenGet())->goCheck();
        $app = new AppToken();
        $token = $app->get($ac, $se);
        return [
            'token' => $token
        ];
    }

    public function verifyToken($token = '')
    {
        if(!$token){
            throw new ParameterException([
                                             'token不允许为空'
                                         ]);
        }
        $valid = TokenService::verifyToken($token);
        return [
            'isValid' => $valid
        ];
    }

}