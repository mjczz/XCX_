<?php

namespace app\api\model;

use think\Model;

class Product extends BaseModel {
    protected $autoWriteTimestamp = 'datetime';
    // 隐藏字段
    protected $hidden = [// pivot是多对多关系中间表的id
                         'delete_time', 'main_img_id', 'pivot', 'from', 'category_id', 'create_time', 'update_time'
    ];

    /**
     * 图片属性
     */
    public function imgs(){
        return $this->hasMany('ProductImage', 'product_id', 'id');
    }

    /**
     * 读取器
     * @param $value
     * @param $data
     * @return string
     */
    // 为冗余字段main_img_url拼接全URL
    // 该字段冗余就不需要再和image表联查，这样可以增加查询性能
    public function getMainImgUrlAttr($value, $data){
        return $this->prefixImgUrl($value, $data);
    }


    public function properties(){
        return $this->hasMany('ProductProperty', 'product_id', 'id');
    }

    /**
     * 获取某分类下商品
     * @param $categoryID
     * @param int $page
     * @param int $size
     * @param bool $paginate
     * @return \think\Paginator
     */
    public static function getProductsByCategoryID($categoryID, $paginate = true, $page = 1, $size = 30){
        $query = self::
        where('category_id', '=', $categoryID);
        if(!$paginate){
            return $query->select();
        } else{
            // paginate 第二参数true表示采用简洁模式，简洁模式不需要查询记录总数
            return $query->paginate($size, true, ['page' => $page]);
        }
    }

    /**
     * 获取商品详情
     * @param $id
     * @return null | Product
     */
    public static function getProductDetail($id){
        //千万不能在with中加空格,否则你会崩溃的
        //        $product = self::with(['imgs' => function($query){
        //               $query->order('index','asc');
        //            }])
        //            ->with('properties,imgs.imgUrl')
        //            ->find($id);
        //        return $product;

        $product = self::with([
                                  'imgs' => function($query){
                                      $query->with(['imgUrl'])->order('order', 'asc');
                                  }
                              ])->with('properties')->find($id);
        return $product;
    }

    // 获取最新商品
    public static function getMostRecent($count){
        // create_time要用模型生成才会有值，用sql导入是没有值的
        $products = self::limit($count)->order('create_time desc')->select();
        return $products;
    }

}
