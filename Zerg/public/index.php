<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

// [ 应用入口文件 ]

// 定义应用目录
define('APP_PATH', __DIR__ . '/../application/');
// 定义错误日志目录(当前目录下的上一级目录下的log下)
define('LOG_PATH', __DIR__. '/../log/');
// 加载框架引导文件
require __DIR__ . '/../thinkphp/start.php';

// 由于配置文件关闭了默认自动记录日志功能，所以入口文件(每一个URL都会经过index.php入口文件)初始化日志开启配置，全局记录日志()
// 只要请求过来，不管有没有异常，都执行日志初始化功能,这样才能解决关闭了config.php 的日志记录功能而无法记录sql日志的问题
\think\Log::init([
    'type'  =>  'File',
    'path'  =>  LOG_PATH,
    'level' => ['sql']// datebase.php 开启debug模式才可以记录sql语句
]);

\think\Request::instance()->header();


