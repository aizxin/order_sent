<?php
/**
 * FileName: Controller.php
 * ==============================================
 * Copy right 2016-2018
 * ----------------------------------------------
 * This is not a free software, without any authorization is not allowed to use and spread.
 * ==============================================
 * @author: kong | <iwhero@yeah.com>
 * @date  : 2020-06-17 12:32
 */
declare (strict_types = 1);

namespace app\admin\controller;

use app\common\controller\BaseController;

use app\common\logic\Admin as AdminLogic;
use app\common\validate\Admin as AdminValidate;

class Admin extends BaseController
{
    private $adminLogic;

    public function __construct(AdminLogic $adminLogic)
    {
        parent::__construct();
        $this->adminLogic = $adminLogic;
    }

    public function lists()
    {
        $page = $this->request->get('page/d', 1);
        $size = $this->request->get('size/d', 5);
        $where = $this->_getQueryWhere();
        $result = $this->adminLogic->lists($where, $page, $size);

        return $this->response($result);
    }

    public function info()
    {
        $id = $this->request->get('id/d');
        if ( ! $id) {
            return $this->error('请选择需要操作的数据');
        }
        $result = $this->adminLogic->info($id);

        return $this->response($result);
    }

    public function add()
    {
        return $this->operate('add', 'add_admin', 'add');
    }

    public function edit()
    {
        return $this->operate('edit', 'edit_admin', 'edit');
    }

    public function changePassword()
    {
        return $this->operate('update', 'change_password', 'changePassword');
    }

    public function role()
    {
        return $this->operate('role', 'role_admin', 'role');
    }

    public function status()
    {
        return $this->statusAction($this->adminLogic->getModel(), 'status_admin');
    }

    public function delete()
    {
        $id = $this->request->post('id/d');
        if ( ! $id) {
            $this->error('请选择需要操作的数据');
        }
        $result = $this->adminLogic->delete($id);

        return $this->returnSuccessOrError($result, 'delete_admin');
    }

    private function operate($method, $action, $scene = '')
    {
        $data = $this->requestPostData();
        $this->validate($data, AdminValidate::class, $scene);
        if ($method == 'update') {
            $result = $this->adminLogic->$method($this->request->userInfo['id'], $data);
        } else {
            $result = $this->adminLogic->$method($data);
        }

        return $this->returnSuccessOrError($result, $action);
    }

    private function _getQueryWhere()
    {
        $where = [];
        return $where;
    }
}
