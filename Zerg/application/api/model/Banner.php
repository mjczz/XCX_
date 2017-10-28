<?php

namespace app\api\model;

use app\api\model\Banner as BannerModel;

use think\Db;
use think\Model;

class Banner extends BaseModel {
    //protected $table = 'banner_item';// 设置当前模型对应的完整数据表名称
    // 一对多，banner表对多个banneritem,外键banner_id
    // 定义一个关联
    public function items() {
        // Banner下有多个BannerItem,通过外键'banner_id'关联,id是当前模型的主键
        return $this->hasMany('BannerItem', 'banner_id', 'id');
        //return $this->hasMany('BannerItem', 'banner_id', 'id')->field('id') // 可以指定查询的字段;
    }
    
    /**
     * @param $id int banner所在位置
     * @return Banner
     */
    public static function getBannerById($id) {
        /*原生sql查询方式*/
        // $result = Db::query("select * from  banner_item where banner_id=?",[$id]);// 原生查询方式
        /*查询构造器*/
        //where('字段名','表达式','查询条件');
        // 表达式、数组发、闭包
        // 当使用了fetchSql()方法后，返回的是sql语句
        //$result = Db::table("banner_item")->where("banner_id","=",$id)->fetchSql()->select();// 查询构造器select()查所有记录二维数组find只能查一条记录一位数组
//       $result = Db::table("banner_item")->where(function($query) use ($id){
//            $query->where("banner_id","=",$id);
//        })->select();
        //$result = BannerModel::get($id);// 使用orm model时，查询结果是对象,返回该对象就不需要再用json($result);了，tp5会自动的将该模型对象序列化，从而得到json结果(config.php设置输出类型json)
        
        /*查询动词总结*/
        //使用Db时不能使用get和all，使用模型则都可以使用
        /*get,find,all,select
         get,all是模型特有的方法，find和select是Db特有的方法*/


        // items是第一层关联关系，items.img是嵌套关联
        $banner = self::with(['items', 'items.img'])->find($id);
        //$banner = BannerModel::with(['items', 'items.img'])->find($id);
//                 $banner = BannerModel::relation('items,items.img')
//                     ->find($id);
        return $banner;
    }
}
