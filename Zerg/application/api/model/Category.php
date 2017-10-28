<?php

namespace app\api\model;

use think\Model;

class Category extends BaseModel
{
    protected  $hidden = [
        'update_time',
        'delete_time',
    ];
    // 一对多
    public function products()
    {
        return $this->hasMany('Product', 'category_id', 'id');
    }

    // 定义关联关系一对一
    public function img()
    {
        return $this->belongsTo('Image', 'topic_img_id', 'id');
    }

    public static function getCategories($ids)
    {
        $categories = self::with('products')
            ->with('products.img')
            ->select($ids);
        return $categories;
    }
    
    /**
     * @ur
     * @param $id
     * @return array|false|\PDOStatement|string|Model
     */
    public static function getCategory($id)
    {
        $category = self::with('products')
            ->with('products.img')
            ->find($id);
        return $category;
    }
}
