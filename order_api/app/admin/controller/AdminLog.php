<?php
/**
 * FileName: Controller.php
 * ==============================================
 * Copy right 2016-2018
 * ----------------------------------------------
 * This is not a free software, without any authorization is not allowed to use and spread.
 * ==============================================
 * @author: kong | <iwhero@yeah.com>
 * @date  : 2020-06-17 12:36
 */
declare (strict_types = 1);

namespace app\admin\controller;

use app\common\controller\BaseController;

use app\common\logic\AdminLogin as AdminLoginLogic;
use app\common\validate\AdminLogin as AdminLoginValidate;

class AdminLog extends BaseController
{
    private $adminLoginLogic;

    public function __construct(AdminLoginLogic $adminLoginLogic)
    {
        parent::__construct();
        $this->adminLoginLogic = $adminLoginLogic;
    }

    public function lists()
    {
        $page = $this->request->get('page/d', 1);
        $size = $this->request->get('size/d', 20);
        $where = $this->_getQueryWhere();
        $result = $this->adminLoginLogic->lists($where, $page, $size);
        $this->response($result);
    }

    public function info()
    {
        $id = $this->request->get('id/d');
        if ( ! $id) {
            $this->error('请选择需要操作的数据');
        }
        $result = $this->adminLoginLogic->info($id);
        $this->response($result);
    }

    public function add()
    {
        $this->operate('add', 'add_adminLogin');
    }

    public function edit()
    {
        $this->operate('edit', 'edit_adminLogin', 'edit');
    }

    public function status()
    {
        $this->statusAction($this->adminLoginLogic->getModel(), 'status_adminLogin');
    }

    public function delete()
    {
        $id = $this->request->post('id/d');
        if ( ! $id) {
            $this->error('请选择需要操作的数据');
        }
        $result = $this->adminLoginLogic->delete($id);
        $this->returnSuccessOrError($result, 'delete_adminLogin');
    }

    private function operate($method, $action, $scene = '')
    {
        $data = $this->requestPostData();
        $this->validate($data, AdminLoginValidate::class . $scene);
        $result = $this->adminLoginLogic->$method($data);
        $this->returnSuccessOrError($result, $action);
    }

    private function _getQueryWhere()
    {
        $where = [];

        return $where;
    }
}
