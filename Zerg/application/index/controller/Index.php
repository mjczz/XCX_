<?php
/**
 * Created by PhpStorm.
 * User: LBJ
 * Date: 2017/10/4
 * Time: 11:17
 */

namespace app\index\controller;


class Index {
    public function index()
    {
        $type = "json";
        $boolen = strpos($type,'\\');
        $t = ucfirst($type);
         $class = false !== strpos($type, '\\') ? $type : '\\think\\response\\' . ucfirst($type);
                echo "默认访问的方法";
    }

}