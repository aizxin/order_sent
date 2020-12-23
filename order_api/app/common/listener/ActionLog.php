<?php
/**
 * FileName: ActionLog.php
 * ==============================================
 * Copy right 2016-2018
 * ----------------------------------------------
 * This is not a free software, without any authorization is not allowed to use and spread.
 * ==============================================
 * @author: kong | <iwhero@yeah.com>
 * @date  : 2020/6/17 9:54 上午
 */
declare (strict_types = 1);

namespace app\common\listener;


use app\common\model\Rule as RuleModel;
use app\common\model\ActionLog as ActionLogModel;
use think\Request;

class ActionLog
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * 日志 记录
     *
     * @param $user
     *
     * @author: kong | <iwhero@yeah.com>
     * @date  : 2020/6/17 9:18 上午
     */
    public function handle($action)
    {
        return $this->actionLog($action);
    }

    /**
     * @param string $action
     */
    protected function actionLog(string $action): void
    {
        $where = [
            ['action', '=', $action],
            ['status', '=', 1],
        ];
        $actionInfo = app(RuleModel::class)->where($where)->find();
        # 如果行为不为空,并且允许写入日志,则开始写入行为操作
        if ( ! empty($actionInfo) && $actionInfo->type == 2) {
            $log = [
                'action_id'   => $actionInfo->id,
                'action_name' => $actionInfo->action,
                'uid'         => $this->request->userInfo['id'] ?? 0,
                'username'    => $this->request->userInfo['name'] ?? '',
                'type'        => $actionInfo->type,
                'create_ip'   => $this->request->ip(),
                'url'         => $this->request->url(),
                'request'     => json_encode($this->request->except(['password', 'confirm_password'])), // 接受参数的时候,无条件排除所有密码参数
            ];

            app(ActionLogModel::class)->create($log);
        }
    }
}