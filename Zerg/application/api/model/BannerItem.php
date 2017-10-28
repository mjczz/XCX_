<?php

namespace app\api\model;

use think\Model;

class BannerItem extends BaseModel
{
    // 隐藏模型字段
    protected $hidden = ['id', 'img_id', 'banner_id', 'delete_time'];

    public function img()
    {
        // 一对一关系用belongsTo
        return $this->belongsTo('Image', 'img_id', 'id');
    }
}
