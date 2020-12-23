<?php
/**
 * FileName: Controller.php
 * ==============================================
 * Copy right 2016-2018
 * ----------------------------------------------
 * This is not a free software, without any authorization is not allowed to use and spread.
 * ==============================================
 * @author: kong | <iwhero@yeah.com>
 * @date  : 2020-06-17 12:45
 */
declare (strict_types = 1);

namespace app\admin\controller;

use app\common\controller\BaseController;

use app\common\logic\Rule as RuleLogic;
use app\common\validate\Rule as RuleValidate;

class Rule extends BaseController
{
    private $ruleLogic;

    public function __construct(RuleLogic $ruleLogic)
    {
        parent::__construct();
        $this->ruleLogic = $ruleLogic;
    }

    public function lists()
    {
        $where = $this->_getQueryWhere();
        $result = $this->ruleLogic->lists($where);
        return $this->response(list_to_tree($result));
    }

    public function info()
    {
        $id = $this->request->get('id/d');
        if ( ! $id) {
            $this->error('请选择需要操作的数据');
        }
        $result = $this->ruleLogic->info($id);

        return $this->response($result);
    }

    public function add()
    {
        return $this->operate('add', 'add_rule');
    }

    public function edit()
    {
        return $this->operate('edit', 'edit_rule', 'edit');
    }

    public function status()
    {
        return $this->statusAction($this->ruleLogic->getModel(), 'status_rule');
    }

    public function delete()
    {
        $id = $this->request->post('id/d');
        if ( ! $id) {
            $this->error('请选择需要操作的数据');
        }
        $result = $this->ruleLogic->delete($id);

        return $this->returnSuccessOrError($result, 'delete_rule');
    }

    private function operate($method, $action, $scene = '')
    {
        $data = $this->requestPostData();
        $this->validate($data, RuleValidate::class, $scene);
        $result = $this->ruleLogic->$method($data);

        return $this->returnSuccessOrError($result, $action);
    }

    private function _getQueryWhere()
    {
        $where = [];

        $data = $this->requestData();
        if (isset($data['pid'])) {
            $where[] = ['pid', '=', 0];
        }

        return $where;
    }
}
