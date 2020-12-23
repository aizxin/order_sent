<?php
/**
 * FileName: AdminLoginLog.php
 * ==============================================
 * Copy right 2016-2018
 * ----------------------------------------------
 * This is not a free software, without any authorization is not allowed to use and spread.
 * ==============================================
 * @author: kong | <iwhero@yeah.com>
 * @date  : 2020/6/17 9:15 上午
 */
declare (strict_types = 1);

namespace app\admin\listener;

use app\common\model\Admin as AdminModel;
use think\Request;
use app\common\logic\AdminLogin as AdminLoginlogic;

/**
 * 登录 日志 记录
 * Class AdminLogin
 * @package app\admin\listener
 */
class AdminLoginLog
{
    protected $request;

    protected $adminLoginLogic;

    public function __construct(Request $request, AdminLoginLogic $adminLoginLogic)
    {
        $this->request = $request;
        $this->adminLoginLogic = $adminLoginLogic;
    }

    /**
     * 日志 记录
     *
     * @param $user
     *
     * @author: kong | <iwhero@yeah.com>
     * @date  : 2020/6/17 9:18 上午
     */
    public function handle($user)
    {
        return $this->loginLog($user);
    }

    /**
     * @param string $user
     */
    protected function loginLog($user): void
    {
        // 更新 登录 信息
        app(AdminModel::class)->update([
            'id'              => $user->id,
            'last_login_time' => time(),
            'last_login_ip'   => $this->request->ip(),
        ]);

        // 添加 登录 日志
        $this->adminLoginLogic->add([
            'name'       => $user->name,
            'admin_id'   => $user->id,
            'login_ip'   => $this->request->ip(),
            'login_time' => time(),
        ]);
    }
}