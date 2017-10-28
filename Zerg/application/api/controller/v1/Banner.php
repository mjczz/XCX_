<?php
/**
 * Created by 七月
 * User: 七月
 * Date: 2017/2/15
 * Time: 13:40
 */

namespace app\api\controller\v1;


use app\api\controller\BaseController;
use app\api\validate\IDMustBePositiveInt;
use app\api\model\Banner as BannerModel;
use app\lib\exception\MissException;
use think\Exception;

/**
 * Banner资源
 */ 
class Banner extends BaseController
{
//    protected $beforeActionList = [
//        'checkPrimaryScope' => ['only' => 'getBanner']
//    ];

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
        /*1.实现参数验证,如果验证失败，抛出异常*/
        //(new IDMustBePositiveInt())->goCheck();
        $validate = new IDMustBePositiveInt();
        $validate->goCheck();

        // 2获取banner数据
        $banner = BannerModel::getBannerById($id);
        //$banner->hidden(['delete_time']);// 隐藏字段
        //$data = $banner->toArray();// 把返回的对象结果转成数组形式
        // 3.获取不到数据,抛出异常
        if (!$banner ) {
            // 抛出异常，并且写好异常信息和错误码，基础BaseException覆盖错误信息,最终都由Handle类或其子类的render方法返回处理后的格式的错误信息给客户端
            // throw new Exception('内部错误');// 在ExceptionHandler中判断用Exception类抛出的异常才会记录日志;
            throw new MissException([
                'msg' => '请求banner不存在',
                'errorCode' => 40000
            ]);
        }
        //(config.php设置输出类型json)
        // 使用orm模型查询数据，返回的结果是对象，tp5会自动序列化该对象，返回给客户端json结果，无需使用json函数
        // 4.返回结果
        return $banner;// config.php 文件以及设置了默认输出类型是json格式
        //return json($banner,200);
    }
}