<?php
declare (strict_types = 1);

namespace app\common\controller;

use app\common\traits\Response;
use think\App;

/**
 * 控制器基础类
 */
abstract class BaseController
{
    use Response;
    /**
     * Request实例
     * @var \think\Request
     */
    protected $request;

    /**
     * 应用实例
     * @var \think\App
     */
    protected $app;

    /**
     * 控制器中间件
     * @var array
     */
    protected $middleware = [];

    /**
     * 构造方法
     * @access public
     *
     * @param App $app 应用对象
     */
    public function __construct()
    {
        $this->app = app();
        $this->request = $this->app->request;

        // 控制器初始化
        $this->initialize();
    }

    // 初始化
    protected function initialize()
    {
    }

    /**
     * 验证数据
     * @access protected
     */
    protected function validate(array $data, $class, $scene = '')
    {
        $validate = $this->app->make($class);
        if ( ! $validate->scene($scene)->check($data)) {
            // 验证失败 输出错误信息
            return $this->error($validate->getError());
        }
    }

    /**
     * @param        $model
     * @param string $action_log
     */
    protected function statusAction($model, string $action_log = ''): void
    {
        $id = input('id/d');
        $action = input('action');
        if ( ! $id) {
            $this->error('请选择需要操作的数据');
        }
        switch ($action) {
            case 'disabled':
                $this->disableStatus($id, $model, $action_log);
                break;
            case 'enabled':
                $this->enabledStatus($id, $model, $action_log);
                break;
            default:
                $this->error('错误的请求');
        }
    }

    /**
     * @param int    $id
     * @param string $model
     * @param string $action_log
     */
    protected function disableStatus(int $id, $model, string $action_log = ''): void
    {
        $this->changeStatus($id, $model, 0, $action_log);
    }

    /**
     * @param int    $id
     * @param string $model
     * @param string $action_log
     */
    protected function enabledStatus(int $id, $model, string $action_log = ''): void
    {
        $this->changeStatus($id, $model, 1, $action_log);
    }

    /**
     * @param int    $id
     * @param        $model
     * @param int    $status
     * @param string $action_log
     */
    protected function changeStatus(int $id, $model, int $status, string $action_log = ''): void
    {
        $model->where(['id' => $id])->update(['status' => $status]);

        $this->returnSuccessOrError(1, $action_log);
    }

    /**
     * @param        $result
     * @param string $action
     */
    protected function returnSuccessOrError($result, string $action = ''): void
    {
        if ($result) {
            if ($action) { // 操作
                $this->app->event->trigger('ActionLog', $action);
            }

            $this->success('操作成功');
        }
        $this->error('服务器忙,稍后再试!', 402);
    }

    /**
     * 接收过滤过的请求参数
     * 默认过滤了 ['create_time', 'update_time', 'delete_time']
     *
     * @param array  $name 需要过滤的请求参数
     * @param string $type 接受参数的形式 默认全部接收
     *
     * @return array
     */
    protected function requestData(array $name = [], string $type = 'param'): array
    {
        $exceptParam = array_merge($name, ['create_time', 'update_time', 'delete_time']);

        return $this->request->except($exceptParam, $type);
    }

    /**
     * 接收并且过滤 post 请求的参数
     *
     * @param array $name
     *
     * @return array
     */
    protected function requestPostData(array $name = []): array
    {
        return $this->requestData($name, 'post');
    }

    /**
     * 接收并且过滤 get 请求的参数
     *
     * @param array $name 需要过滤的参数
     *
     * @return array
     */
    protected function requestGetData(array $name = []): array
    {
        return $this->requestData($name, 'get');
    }
}
