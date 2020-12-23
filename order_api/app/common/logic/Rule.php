<?php
/**
 * FileName: ActionLogic.php
 * ==============================================
 * Copy right 2016-2018
 * ----------------------------------------------
 * This is not a free software, without any authorization is not allowed to use and spread.
 * ==============================================
 *@author: kong | <iwhero@yeah.com>
 * @date  : 2020-06-17 12:45
 */
declare (strict_types = 1);

namespace app\common\logic;

use app\common\model\Rule as RuleModel;
use app\common\transformer\Rule as RuleTransformer;

class Rule extends BaseLogic
{
    protected $ruleTransformer;

    public function __construct(RuleTransformer $ruleTransformer)
    {
        $this->ruleTransformer = $ruleTransformer;
        $this->makeModel();
    }

    /**
     * 绑定 数据表模型 namespace Name
     * @return string
     * @date  : 2020/6/17 11:38 上午
     */
    public function modelClassName()
    {
        return RuleModel::class;
    }

    public function lists(array $where=[],string $order = 'id asc', $withoutField = false)
    {
        $lists = $this->model->where($where)->withoutField($withoutField)->order($order)->select();
        $rows = $this->ruleTransformer->transformCollection($lists->all());

        return $rows;
    }

    public function info($id)
    {
        if ( ! $id) return [];

        $info = $this->model->find($id);

        return $this->ruleTransformer->transform($info);
    }
}