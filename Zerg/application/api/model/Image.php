<?php

namespace app\api\model;

use think\Model;

class Image extends BaseModel
{
    // 设置隐藏字段(隐藏模型字段)
    protected $hidden = ['delete_time', 'id', 'from'];

    /**
     * 读取器(get开头中间是属性名)
     * @param $value Url字段的值
     * @param $data 改模型所有数据
     * @return string 返回结果是字符串
     */
    public function getUrlAttr($value, $data)
    {
        // 返回url前缀和url拼接后的值
        /*$finalUrl = $value;
        if($data['from'] == 1){// form为1时代表本地图片需要拼接url
            $finalUrl = config('setting.img_prefix').$value;
        }
        return $finalUrl;*/
        // 这样写即使是其他表里的url字段也可以实现拼接url
        return $this->prefixImgUrl($value, $data);
    }
}

