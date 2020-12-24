<?php
/**
 * FileName: Controller.php
 * ==============================================
 * Copy right 2016-2018
 * ----------------------------------------------
 * This is not a free software, without any authorization is not allowed to use and spread.
 * ==============================================
 * @author: kong | <iwhero@yeah.com>
 * @date  : 2020-06-17 12:47
 */
declare (strict_types = 1);

namespace app\admin\controller;

use app\common\controller\BaseController;

use app\common\logic\Admin as AdminLogic;
use app\common\util\Token;
use app\common\validate\Auth as AuthValidate;
use think\facade\Log;

class Auth extends BaseController
{
    private $adminLogic;

    public function __construct(AdminLogic $adminLogic)
    {
        parent::__construct();
        $this->adminLogic = $adminLogic;
    }

    public function login()
    {
        $data = $this->request->param();
        $this->validate($data, AuthValidate::class);

        $user = $this->adminLogic->findWhere([
            'username|mobile' => $data['username'],
            'status'          => 1,
        ]);

        if ( ! $user) {
            return $this->error('账号或密码错误:>');
        }

        if ($data['password'] != \Crypt::decrypt($user['password'], config('user.user_key'))) {
            return $this->error('账号或密码错误:)');
        }

        $token = Token::getToken([
            'id'     => $user->id,
            'name'   => $user->name,
            'avatar' => $user->avatar ?? '',
            'time'   => time(),
            'is_sup' => in_array($user->id, [1, 2, 3]),
        ]);

        // 添加 日志
        $this->app->event->trigger('AdminLoginLog', $user);

        return $this->response(['token' => $token]);
    }

    public function info()
    {
        $result = [
            'avatar'      => $this->request->userInfo['avatar'] ?: 'https://wpimg.wallstcn.com/f778738c-e4f8-4870-b634-56703b4acafe.gif?imageView2/1/w/80/h/80',
            'name'        => $this->request->userInfo['name'],
        ];

        return $this->response($result);
    }

    public function menu()
    {
        $result = $this->adminLogic->rule($this->request->userInfo);

        return $this->response($result);

    }
}
