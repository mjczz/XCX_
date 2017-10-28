<?php
/**
 * Created by 七月.
 * Author: 七月
 * 微信公号：小楼昨夜又秋风
 * 知乎ID: 七月在夏天
 * Date: 2017/2/19
 * Time: 2:42
 */

namespace app\api\model;


use think\Model;
use traits\model\SoftDelete;

/**
 * Class BaseModel
 * @package app\api\model
 */
class BaseModel extends Model
{
    // 软删除，设置后在查询时要特别注意whereOr
    // 使用whereOr会将设置了软删除的记录也查询出来
    // 可以对比下SQL语句，看看whereOr的SQL
    use SoftDelete;
    
    protected $hidden = ['delete_time'];
    
    /**
     * @param $value
     * @param $data
     * @return string
     */
    protected function  prefixImgUrl($value, $data){
        $finalUrl = $value;
        // from=1表示本地图片,则拼接url
        if($data['from'] == 1){
            // 获取配置文件或扩展配置目录extra下的配置文件的配置项,然后拼接文件名
            $finalUrl = config('setting.img_prefix').$value;
        }
        return $finalUrl;
    }
}