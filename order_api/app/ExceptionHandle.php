<?php

namespace app;

use think\db\exception\DataNotFoundException;
use think\db\exception\ModelNotFoundException;
use think\exception\Handle;
use think\exception\HttpException;
use think\exception\HttpResponseException;
use think\exception\ValidateException;
use think\facade\Log;
use think\Response;
use Throwable;

/**
 * 应用异常处理类
 */
class ExceptionHandle extends Handle
{
    /**
     * 不需要记录信息（日志）的异常类列表
     * @var array
     */
    protected $ignoreReport = [
        HttpException::class,
        HttpResponseException::class,
        ModelNotFoundException::class,
        DataNotFoundException::class,
        ValidateException::class,
    ];

    /**
     * 记录异常信息（包括日志或者其它方式记录）
     *
     * @access public
     *
     * @param Throwable $exception
     *
     * @return void
     */
    public function report(Throwable $exception): void
    {
        // 使用内置的方式记录异常日志
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @access public
     *
     * @param \think\Request $request
     * @param Throwable      $e
     *
     * @return Response
     */
    public function render($request, Throwable $e): Response
    {
        // 添加自定义异常处理机制
        $this->runWhoops($request, $e);

        // 其他错误交给系统处理
        return parent::render($request, $e);
    }

    /**
     * @param $exception
     *
     * @return string
     * @author: kong | <iwhero@yeah.com>
     * @date  : 2020/6/3 5:02 下午
     */
    protected function runWhoops($request, $exception)
    {
        if ($exception instanceof HttpResponseException) {
            return parent::render($request, $exception);
        }
        Log::write($exception->getFile() . ':第>>' . $exception->getLine() . '行错误,内容为:' . $exception->getMessage(), 'error');
        $whoops = new \Whoops\Run;

        if ($exception instanceof HttpException && $request->isAjax()) { //如果是ajax请求，就返回json数据
            $whoops->pushHandler(new \Whoops\Handler\JsonResponseHandler());

            return $whoops->handleException($exception);
        } else {
            //否则返回html数据
            $whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler());

            return $whoops->handleException($exception);
        }
    }
}
