<?php
/**
 * Created by 七月.
 * User: 七月
 * Date: 2017/2/16
 * Time: 1:59
 */

namespace app\api\model;


use app\lib\exception\ProductException;
use app\lib\exception\ThemeException;
use think\Model;

class Theme extends BaseModel {
    protected $hidden = ['delete_time', 'topic_img_id', 'head_img_id'];
    
    /**
     * 关联Image
     * 要注意belongsTo和hasOne的区别
     * 带外键的表一般定义belongsTo，另外一方定义hasOne
     */
    public function topicImg() {
        // 一对一关系(hasOne(),belongsTo())
        return $this->belongsTo('Image', 'topic_img_id', 'id');
    }
    
    public function headImg() {
        return $this->belongsTo('Image', 'head_img_id', 'id');
    }
    
    /**
     * 关联product，多对多关系
     */
    public function products() {
        // 表名1，关联表，关联id(来源于中间表,注意顺序)
        return $this->belongsToMany('Product', 'theme_product', 'product_id', 'theme_id');
    }
    
    
    /**
     * 查询专题和专题下的商品
     */
    // rest是基于资源类型的，一般返回的是全部的模型里面的属性(不包括没有用的update_time),不用考虑过多的业务，但是我们做内部开发还是要考虑一下业务
    // 最大的好处是业务变更了之后,接口不需要调整,客户端根据自己的需要选取他需要的属性就可以了
    public static function getThemeWithProducts($id) {
        // 一个专题对应的数据，所以用find
        // 关联商品，头图，顶部图片
        $themes = self::with('products,topicImg,headImg')
            ->find($id);
        return $themes;
    }
    
    /**
     * 获取主题列表
     * @param $ids array
     * @return array
     */
    public static function getThemeList($ids) {
        if (empty($ids)) {
            return [];
        }
        // 讲解with的用法和如何预加载关联属性的关联属性
        // 不要在这里就toArray或者toJSON
        $themes = self::with('products,img')
//            ->with('products.imgs')
            ->select($ids);
        return $themes;
        //        foreach ($themes as $theme) {
        //            foreach($theme->products as $product){
        //                $url = $product->img;
        //            }
        //        }
        // 讲解collection的用法，可以在Model中配置默认返回数据集，而非数组
        //        $themes = User::with(['orders'=>function($query){
        //            $query->where('order_no', '=', 7);
        //        }])->select();
        //        return collection($themes)->toArray();
    }
    
    public static function addThemeProduct($themeID, $productID) {
        $models = self::checkRelationExist($themeID, $productID);
        
        // 写入中间表，这里要注意，即使中间表已存在相同themeID和itemID的
        // 数据，写入不成功，但TP并不会报错
        // 最好是在插入前先做一边查询检查
        
        $models['theme']->products()
            ->attach($productID);
        return true;
    }
    
    public static function deleteThemeProduct($themeID, $productID) {
        $models = self::checkRelationExist($themeID, $productID);
        $models['theme']->products() ->detach($productID);
        return true;
    }
    
    private static function checkRelationExist($themeID, $productID) {
        $theme = self::get($themeID);
        if (!$theme) {
            throw new ThemeException();
        }
        $product = Product::get($productID);
        if (!$product) {
            throw new ProductException();
        }
        return [
            'theme' => $theme,
            'product' => $product
        ];
        
    }
}