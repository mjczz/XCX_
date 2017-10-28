<?php
/**
 * Created by PhpStorm.
 * User: 七月
 * Date: 2017/2/12
 * Time: 19:44
 */

namespace app\lib\exception;

use think\Config;
use think\exception\Handle; // Handle是tp封装好的异常处理类
use think\Log;// 日志处理类
use think\Request;
use Exception;

/*
 * 原理:因为tp5所有抛出的异常都得经过Handle类下的render方法处理后才返回特定格式的错误信息
 * 重写Handle的render方法，实现自定义异常消息,达到全局层面控制异常的返回格式
 */
// 异常处理handle类 留空使用 \think\exception\Handle
// 使用子类作为全局异常处理类
// 'exception_handle'       => '\app\lib\exception\ExceptionHandler',
class ExceptionHandler extends Handle {
    private $code;// http 状态码
    private $msg;// 错误信息
    private $errorCode;// 错误码

    /*所有抛出的异常都得经过render方法来处理，我们才能在全局的层面上来控制异常的返回格式*/
    // 所有抛出的异常返回的信息都要通过render方法渲染,最后决定返回到客户端的错误信息及形式
    public function render(Exception $e)
    {
        // 相当于定义了一个BaseException类代表异常分类的第一种情况(由用户行为导致异常，不需要记录日志，需要向用户返回具体信息)
        if($e instanceof BaseException){
            //如果是自定义异常，则控制http状态码，不需要记录日志
            //因为这些通常是因为客户端传递参数错误或者是用户请求造成的异常
            //不应当记录日志

            $this->code = $e->code;
            $this->msg = $e->msg;
            $this->errorCode = $e->errorCode;
            // $this->recordErrorLog($e); // 记录日志
        } else{
            // 如果是服务器未处理的异常，将http状态码设置为500，并记录日志
            /*服务器端开发人员希望看到具体指明了错误信息的html页面，客户端开发者需要我们给出简化的json结构体,解决方式是类似于开关的东西config('app_debug')*/
            //Config::get('app_debug');
            if(config('app_debug')){// 如果为真，则为调试模式打开的状态
                // 调试状态下需要显示TP默认的异常页面，因为TP的默认页面,很容易看出问题，便于后台开发调试
                return parent::render($e);// 即相当于不重写原来的render方法
            }

            $this->code = 500; // Http状态码
            $this->msg = 'sorry，we make a mistake. (^o^)Y';
            $this->errorCode = 999; // 自定义错误码
            // 后台程序错误，自定义记录日志(已经在config.php中关闭了默认自动记录日志功能)
            $this->recordErrorLog($e); // 记录日志
        }

        $request = Request::instance();// 实例化Request对象
        /*根据restful api风格返回给用的信息包括下面的数组加上http状态码*/
        $result = ['msg' => $this->msg, 'error_code' => $this->errorCode, 'request_url' => $request->url()// 当前请求的url
        ];

        // return json($e->getMessage(), $this->code);
        // 返回错误信息数组和http状态码
        return json($result, $this->code);
    }

    /*
     * 将异常写入日志
     */
    private function recordErrorLog(Exception $e)
    {
        // 日志初始化(因为在config.php中关闭了默认的日志初始化,所以这里要日志初始化一下)
        // 只有错误级别达到error，才记录日志
        Log::init(['type' => 'File', 'path' => LOG_PATH, 'level' => ['error']
                  ]);
        // Log::record($e->getTraceAsString());
        // 第一个参数异常的信息，第二个参数定义错误的级别(只有这个级别大于等于init里设定的level，才能被记录到日志中去)
        Log::record($e->getMessage(), 'error');
    }
}