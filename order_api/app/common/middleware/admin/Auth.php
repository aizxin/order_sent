<?php
/**
 * FileName: Auth.php
 * ==============================================
 * Copy right 2016-2018
 * ----------------------------------------------
 * This is not a free software, without any authorization is not allowed to use and spread.
 * ==============================================
 * @author: kong | <iwhero@yeah.com>
 * @date  : 2020/6/16 11:50 下午
 */
declare (strict_types = 1);

namespace app\common\middleware\admin;


use app\common\traits\Response;
use app\common\util\Token;
use think\facade\Log;
use think\Request;
use Closure;
use tauthz\facade\Enforcer;

class Auth
{
    use Response;

    /**
     * @param Request $request
     * @param Closure $next
     *
     * @return mixed
     * @author: kong | <iwhero@yeah.com>
     * @date  : 2020/7/8 5:26 下午
     */
    public function handle($request, Closure $next)
    {
        $this->auth($request);

        return $next($request);
    }

    /**
     * 获取 用户 信息
     * @author: kong | <iwhero@yeah.com>
     * @date  : 2020/6/16 11:38 下午
     */
    protected function auth($request)
    {
        $request->userInfo = [];
        try {
            $token = $request->header('Authorization', '..');
            $adminInfo = Token::parsingToken($token);
            if ( ! empty($adminInfo)) {
                $request->userInfo = $adminInfo;
            } else {
                return $this->error('请登录~~', 401);
            }
        } catch (\Exception $exception) {
            return $this->error('请登录~~', 401);
        }
        $this->enforce($request);
    }

    /**
     * @param Request $request
     *
     * 权限验证
     *
     * @author: kong | <iwhero@yeah.com>
     * @date  : 2020/6/16 11:56 下午
     */
    protected function enforce($request)
    {
        if ($request->userInfo['is_sup']) {
            return;
        }
        $sub = $request->userInfo['id'];
        $url = $request->pathinfo();
        if (in_array($url, ['auth/info', 'auth/menu'])) {
            return;
        }
        $hasAuth = [];
        foreach (Enforcer::getRolesForUser($sub) as $role) {
            $hasAuth[] = Enforcer::hasPermissionForUser($role, $url);
        }
        $hasAuth = array_unique($hasAuth);
        if ( ! in_array(true, $hasAuth)) { //只要所以角色木有权限,则返回 false
            Log::write('用户=>:'.$sub.'>>>权限不足url=>:' . $url);

            return $this->error('权限不足~~', 401);
        }
    }
}