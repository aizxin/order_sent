<?php
/**
 * FileName: ActionLogic.php
 * ==============================================
 * Copy right 2016-2018
 * ----------------------------------------------
 * This is not a free software, without any authorization is not allowed to use and spread.
 * ==============================================
 * @author: kong | <iwhero@yeah.com>
 * @date  : 2020-06-17 12:45
 */
declare (strict_types = 1);

namespace app\common\logic;

use app\common\model\Role as RoleModel;
use app\common\transformer\Role as RoleTransformer;
use tauthz\facade\Enforcer;
use app\common\model\RoleRule as RoleRuleModel;

class Role extends BaseLogic
{
    protected $roleTransformer;

    public function __construct(RoleTransformer $roleTransformer)
    {
        $this->roleTransformer = $roleTransformer;
        $this->makeModel();
    }

    /**
     * 绑定 数据表模型 namespace Name
     * @return string
     * @date  : 2020/6/17 11:38 上午
     */
    public function modelClassName()
    {
        return RoleModel::class;
    }

    public function lists(array $where = [], string $order = 'id desc', $withoutField = false)
    {
        $lists = $this->model->where($where)->withoutField($withoutField)->order($order)->select();
        $rows = $this->roleTransformer->transformCollection($lists->all());

        return $rows;
    }

    public function info($id)
    {
        if ( ! $id) return [];

        $info = $this->model->find($id);


        $roleRuleModel = $this->baseModel(RoleRuleModel::class);

        $info->rules = $roleRuleModel->where(['role_id' => $id, 'is_tree_half' => 0])->column('rule_id');

        return $this->roleTransformer->transform($info);
    }


    public function rule($data)
    {
        $id = $data['id'] ?? '';
        if ( ! $id) return [];

        $roleRuleModel = $this->baseModel(RoleRuleModel::class);
        $roleRuleModel->where('role_id', $id)->delete(); // 删除 角色 节点
        Enforcer::deletePermissionsForUser('role:' . $id); // 删除 角色 权限

        $rule = [];
        // 开启 事务
        $this->model->startTrans();
        try {
            foreach ($data['tree']['checkedNodes'] as $node) {
                $rule[] = ['role_id' => $id, 'rule_id' => $node['id'], 'is_dir' => $node['type']];
                Enforcer::addPermissionForUser('role:' . $id, $node['route']);
            }
            foreach ($data['tree']['halfCheckedNodes'] as $node) {
                $rule[] = ['role_id' => $id, 'rule_id' => $node['id'], 'is_dir' => $node['type'], 'is_tree_half' => 1];
                Enforcer::addPermissionForUser('role:' . $id, $node['route']);
            }
            $roleRuleModel->saveAll($rule);
            $this->model->commit();
        } catch (\Exception $exception) {
            $this->model->rollback();

            return false;
        }


        return $data;
    }
}