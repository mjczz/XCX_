<?php

namespace app\api\controller\v1;

use app\api\model\Theme as ThemeModel;
use app\api\validate\IDCollection;
use app\api\validate\IDMustBePositiveInt;
use app\api\validate\ThemeProduct;
use app\lib\exception\SuccessMessage;
use app\lib\exception\ThemeException;
use think\Controller;
use think\Exception;

/**
 * 主题推荐,主题指首页里多个聚合在一起的商品
 * 注意同专题区分
 * 常规的REST服务在创建成功后，需要在Response的
 * header里附加成功创建资源的URL，但这通常在内部开发中
 * 并不常用，所以本项目不采用这种方式
 */
class Theme extends Controller
{
    /**
     * 获取主题列表
     * @url     /theme?ids=:id1,id2,id3...
     * @return  array of theme
     * @throws  ThemeException
     * @note 实体查询分单一和列表查询，可以只设计一个接收列表接口，
     *       单一查询也需要传入一个元素的数组
     *       对于传递多个数组的id可以选用post传递、
     *       多个id+分隔符或者将多个id序列化成json并在query中传递
     */
//    对于内部开发，应该返回清晰的数据给前端，而不是盲目的遵守rest资源全部返回
    public function getSimpleList($ids = '')
    {
        $validate = new IDCollection();
        $validate->goCheck();
        // 字符串切割成数组
        $ids = explode(',', $ids);
        // 利用关联模型,查询的是一组数组，所以用select
        $result = ThemeModel::with('topicImg,headImg')->select($ids);
//        $result = ThemeModel::getThemeList($ids);
        // $result是select方法查出的数据集，所以可以用isEmpty()方法.
        if ($result->isEmpty()) {
            throw new ThemeException();
        }
        // 框架会自动序列化数据为JSON，所以这里不要toJSON！
      /*  $result = $result->hidden(['products.imgs'])
            ->toArray();
        $result = $result->hidden([
            'products.category_id','products.stock','products.summary']);*/
        return $result;
    }
    
    /**
     * 查询专题和专题下的商品(控制器只需4步)
     * @url       /theme/:id
     * @param $id
     * @return array
     * @throws ThemeException
     */
    public function getComplexOne($id)
    {
        //1.校验参数
        (new IDMustBePositiveInt())->goCheck();
        //2.查询数据(业务写在model层)
        $theme = ThemeModel::getThemeWithProducts($id);
        //3.判断异常
        if(!$theme){// 没有用select查询，返回结果就不是数据集，所以不能用isEmpty()方法
            throw new ThemeException();
        }
        // 4.返回数据
        return $theme->hidden(['products.summary'])->toArray();
    }

//    public function getThemeSummary()

    /**
     * @url /theme/:t_id/product/:p_id
     * @Http POST
     * @return SuccessMessage or Exception
     */
    public function addThemeProduct($t_id, $p_id)
    {
        $validate = new ThemeProduct();
        $validate->goCheck();
        ThemeModel::addThemeProduct($t_id, $p_id);
        return new SuccessMessage();
    }

    /**
     * @url /theme/:t_id/product/:p_id
     * @Http DELETE
     * @return SuccessMessage or Exception
     */
    public function deleteThemeProduct($t_id, $p_id)
    {
        $validate = new ThemeProduct();
        $validate->goCheck();
        $themeID = (int)$t_id;
        $productID = (int)$p_id;
        ThemeModel::deleteThemeProduct($themeID, $productID);
        return new SuccessMessage([
            'code' => 204
        ]);
    }

    // 去除部分属性，尽量对客户端保持精简
//    private function cutThemes($themes)
//    {
//        foreach ($themes as &$theme) {
//            foreach ($theme['products'] as &$product) {
//                unset($product['stock']);
//                unset($product['summary']);
//            }
//        }
//        return $themes;
//    }
}
