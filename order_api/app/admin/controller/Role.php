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

use app\common\logic\Role as RoleLogic;
use app\common\validate\Role as RoleValidate;

class Role extends BaseController
{
    private $roleLogic;

    public function __construct(RoleLogic $roleLogic)
    {
        parent::__construct();
        $this->roleLogic = $roleLogic;
    }

    public function lists()
    {
        $where = $this->_getQueryWhere();
        $result = $this->roleLogic->lists($where);
        $this->response($result);
    }

    public function info()
    {
        $id = $this->request->get('id/d');
        if ( ! $id) {
            $this->error('请选择需要操作的数据');
        }
        $result = $this->roleLogic->info($id);
        $this->response($result);
    }

    public function add()
    {
        $this->operate('add', 'add_role');
    }

    public function edit()
    {
        $this->operate('edit', 'edit_role', 'edit');
    }

    public function status()
    {
        $this->statusAction(\app\common\model\Role::class, 'status_role');
    }

    public function delete()
    {
        $id = $this->request->post('id/d');
        if ( ! $id) {
            $this->error('请选择需要操作的数据');
        }
        $result = $this->roleLogic->delete($id);
        $this->returnSuccessOrError($result, 'delete_role');
    }

    public function rule()
    {
        $id = $this->request->post('id/d');
        if ( ! $id) {
            $this->error('请选择需要操作的数据');
        }
        $result = $this->roleLogic->rule($this->requestData());
        $this->returnSuccessOrError($result, 'rule_role');
    }

    private function operate($method, $action, $scene = '')
    {
        $data = $this->requestPostData();
        $this->validate($data, RoleValidate::class , $scene);
        $result = $this->roleLogic->$method($data);
        $this->returnSuccessOrError($result, $action);
    }

    private function _getQueryWhere()
    {
        $where = [];

        return $where;
    }
}
