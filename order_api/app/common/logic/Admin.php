<?php
/**
 * FileName: ActionLogic.php
 * ==============================================
 * Copy right 2016-2018
 * ----------------------------------------------
 * This is not a free software, without any authorization is not allowed to use and spread.
 * ==============================================
 * @author: kong | <iwhero@yeah.com>
 * @date  : 2020-06-17 12:32
 */
declare (strict_types = 1);

namespace app\common\logic;

use app\common\model\Admin as AdminModel;
use app\common\model\RoleRule as RoleRuleModel;
use app\common\transformer\Admin as AdminTransformer;
use tauthz\facade\Enforcer;
use app\common\model\Rule as RuleModel;
use think\facade\Log;
use think\model\Relation;

class Admin extends BaseLogic
{
    protected $adminTransformer;

    public function __construct(AdminTransformer $adminTransformer)
    {
        $this->adminTransformer = $adminTransformer;
        $this->makeModel();
    }

    /**
     * 绑定 数据表模型 namespace Name
     * @return string
     * @date  : 2020/6/17 11:38 上午
     */
    public function modelClassName()
    {
        return AdminModel::class;
    }

    public function lists(array $where = [], int $page = 1, int $size = 20, string $order = 'id desc', $withoutField = false)
    {
        $lists = $this->model->where($where)->withoutField($withoutField)->page($page, $size)->order($order)->select();
        $rows = $this->adminTransformer->transformCollection($lists->all());
        $total = $this->model->where($where)->count();

        return compact('rows', 'total');
    }

    public function info($id)
    {
        if ( ! $id) return [];

        $info = $this->model->find($id);
        $info['role'] = Enforcer::getRolesForUser($id);

        return $this->adminTransformer->transform($info);
    }

    public function role($data)
    {
        $uid = $data['id'] ?? '';
        Enforcer::deleteRolesForUser($uid);
        foreach ($data['role'] as $role) {
            Enforcer::addRoleForUser($uid, $role);
        }

        return true;
    }

    public function rule($user = [])
    {
        $id = $user['id'] ?? '';
        if ( ! $id) return [];

        $ruleModel = $this->baseModel(RuleModel::class);

        $actionIds = [];
        if ($user['is_sup']) {
            $where = ['status' => 1, 'type' => 1];
            $where_action = ['status' => 1, 'type' => 2];
        } else {
            $rules = Enforcer::getRolesForUser($id);
            $roleIds = [];
            foreach ($rules as $rule) {
                $roleIds[] = explode(':', $rule)[1] ?? '';
            }

            $roleIds = array_unique($roleIds);
            $roleRuleModel = $this->baseModel(RoleRuleModel::class);
            $ruleIds = $roleRuleModel->where([
                ['role_id', 'in', $roleIds],
                ['is_dir', '=', 1],
            ])->column('rule_id');

            $ruleIds = array_unique($ruleIds);
            $where = ['status' => 1, 'type' => 1, 'id' => $ruleIds];

            $actionIds = $roleRuleModel->where([
                ['role_id', 'in', $roleIds],
                ['is_dir', '=', 2],
            ])->column('rule_id');
            $actionIds = array_unique($actionIds);

            $where_action = ['status' => 1, 'type' => 2, 'id' => $actionIds];
        }

        $rule_items = $ruleModel->where($where)
            ->with(['rule' => function (Relation $query) {
                return $query->where(['status' => 1, 'type' => 2])->order('sort asc');
            }])->order('sort asc')->select()->toArray();
        if ( ! $rule_items) return [];

        $return['rule_action'] = $ruleModel->where($where_action)->column('action');

        $return['rule_menu'] = $this->adminTransformer->ruleTransform($rule_items, $actionIds);

        return $return;
    }

    public function ruleAction($user = [])
    {
        $id = $user['id'] ?? '';
        if ( ! $id) return [];

        $ruleModel = $this->baseModel(RuleModel::class);

        if ($user['is_sup']) {
            $where = ['status' => 1, 'type' => 2];
        } else {
            $rules = Enforcer::getRolesForUser($id);
            $roleIds = [];
            foreach ($rules as $rule) {
                $roleIds[] = explode(':', $rule)[1] ?? '';
            }
            $roleIds = array_unique($roleIds);
            $roleRuleModel = $this->baseModel(RoleRuleModel::class);
            $actionIds = $roleRuleModel->where([
                ['role_id', 'in', $roleIds],
                ['is_dir', '=', 2],
            ])->column('rule_id');
            $actionIds = array_unique($actionIds);
            $where = ['status' => 1, 'type' => 2, 'id' => $actionIds];
        }

        return $ruleModel->where($where)->column('action');

    }
}