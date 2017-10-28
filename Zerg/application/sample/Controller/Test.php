<?php
/**
 * Created by PhpStorm.
 * User: LBJ
 * Date: 2017/9/19
 * Time: 23:01
 */

namespace app\sample\Controller;

use app\api\validate\TestValidate;
use think\Controller;
use think\Exception;
use think\Request;
use think\Validate;

class Test extends Controller {
    // http://zerg.com/hello/3?name=七月 $age 在postman里post方式 body里提交
    // 路由Route::rule("hello/:id",'sample/Test/hello');
    public function hello(Request $request){
        /* 使用助手函数input获取所有请求参数*/
        $res = input('param.');
        /*使用request对象获取参数变量 */
        $res = $request->param();// 获取所有请求参数
        $res = $request->get();// 获取？号后的请求参数 name
        $res = $request->post();// 获取post请求参数 age
        $res = $request->route();// 获取路由里的请求参数 id
        $name = Request::instance()->param('name');
        $age = Request::instance()->param('age');
        $id = Request::instance()->param('id');
        return '测试tp5获取请求参数';
    }

    // validate类验证参数
    public function testValidate($id){
        /* 独立验证*/
        $data = ['id'=>$id,'name' => 'veklkjljlkk', 'email' => 'vend@qq.com',];
//        $data = ['id'=>$id];
//         $validate = new Validate(['name'  => 'require|max:10',// tp5内置的验证规则
//                                          'email' => 'email',// tp5内置的验证规则
//                                         ]);
//         $result =$validate->check($data);
//         echo $validate->getError();
//         exit;
        // $result = $validate->batch()->check($data);// 批量验证
        // var_dump($validate->getError());// 输出验证错误信息

        /* 验证器*/
        $validate = new TestValidate(); // 验证规则作为该类的成员变量$rule定义
         $result =$validate->goCheck($data);
         var_dump($result);
         exit;
         var_dump($validate->getError());exit;
         echo $validate->getError();
         $result = $validate->batch()->check($data);// 批量验证
         var_dump($validate->getError());// 输出验证错误信息
    }
    /**
     * 获取指定ID的Banner信息
     * @url     /banner/:id
     * @http    get
     * @param   int $id banner id
     * @return  array of banner item , code 200
     * @throws  MissException
     */
    public function getBanner($id)
    {
        /*2行代码实现参数验证*/
        $validate = new IDMustBePositiveInt();
        $validate->goCheck();
        try{
            // 获取banner数据
            $banner = 1/0;
        }catch(Exception $ex){
            $arr = [
                'error_code'=>10001,
                'msg'=>$ex->getMessage(),
            ];
            // 因为已经异常了，根据restfulapi规范，第二个参数是http状态码,不能是200
            return json($arr,400);
        }
        return $banner;
    }

}