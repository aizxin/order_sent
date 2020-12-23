<?php
/**
 * FileName: Controller.php
 * ==============================================
 * Copy right 2016-2018
 * ----------------------------------------------
 * This is not a free software, without any authorization is not allowed to use and spread.
 * ==============================================
 * @author: kong | <iwhero@yeah.com>
 * @date  : 2020-06-17 12:46
 */
declare (strict_types = 1);

namespace app\admin\controller;

use app\common\controller\BaseController;

use app\common\logic\HospitalUser as HospitalUserLogic;
use app\common\validate\HospitalUser as HospitalUserValidate;

class HospitalUser extends BaseController
{
    private $hospitalUserLogic;

    public function __construct(HospitalUserLogic $hospitalUserLogic)
    {
        parent::__construct();
        $this->hospitalUserLogic = $hospitalUserLogic;
    }

    public function lists()
    {
        $page = $this->request->get('page/d', 1);
        $size = $this->request->get('size/d', 20);
        $where = $this->_getQueryWhere();
        $result = $this->hospitalUserLogic->lists($where, $page, $size);
        $this->response($result);
    }

    public function info()
    {
        $id = $this->request->get('id/d');
        if ( ! $id) {
            $this->error('请选择需要操作的数据');
        }
        $result = $this->hospitalUserLogic->info($id);
        $this->response($result);
    }

    public function add()
    {
        $this->operate('add', 'add_hospitalUser');
    }

    public function edit()
    {
        $this->operate('edit', 'edit_hospitalUser', 'edit');
    }

    public function status()
    {
        $this->statusAction($this->hospitalUserLogic->getModel(), 'status_hospitalUser');
    }

    public function delete()
    {
        $id = $this->request->post('id/d');
        if ( ! $id) {
            $this->error('请选择需要操作的数据');
        }
        $result = $this->hospitalUserLogic->delete($id);
        $this->returnSuccessOrError($result, 'delete_hospitalUser');
    }

    private function operate($method, $action, $scene = '')
    {
        $data = $this->requestPostData();
        $this->validate($data, HospitalUserValidate::class, $scene);
        $result = $this->hospitalUserLogic->$method($data);
        $this->returnSuccessOrError($result, $action);
    }

    private function _getQueryWhere()
    {
        $where = [];

        return $where;
    }
}
