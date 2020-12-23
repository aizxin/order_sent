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

use app\common\logic\Hospital as HospitalLogic;
use app\common\validate\Hospital as HospitalValidate;

class Hospital extends BaseController
{
    private $hospitalLogic;

    public function __construct(HospitalLogic $hospitalLogic)
    {
        parent::__construct();
        $this->hospitalLogic = $hospitalLogic;
    }

    public function lists()
    {
        $page = $this->request->get('page/d', 1);
        $size = $this->request->get('size/d', 20);
        $where = $this->_getQueryWhere();
        $result = $this->hospitalLogic->lists($where, $page, $size);
        $this->response($result);
    }

    public function info()
    {
        $id = $this->request->get('id/d');
        if ( ! $id) {
            $this->error('请选择需要操作的数据');
        }
        $result = $this->hospitalLogic->info($id);
        $this->response($result);
    }

    public function add()
    {
        $this->operate('add', 'add_hospital');
    }

    public function edit()
    {
        $this->operate('edit', 'edit_hospital', 'edit');
    }

    public function status()
    {
        $this->statusAction($this->hospitalLogic->getModel(), 'status_hospital');
    }

    public function delete()
    {
        $id = $this->request->post('id/d');
        if ( ! $id) {
            $this->error('请选择需要操作的数据');
        }
        $result = $this->hospitalLogic->delete($id);
        $this->returnSuccessOrError($result, 'delete_hospital');
    }

    private function operate($method, $action, $scene = '')
    {
        $data = $this->requestPostData();
        $this->validate($data, HospitalValidate::class, $scene);
        $result = $this->hospitalLogic->$method($data);
        $this->returnSuccessOrError($result, $action);
    }

    private function _getQueryWhere()
    {
        $where = [];

        return $where;
    }
}
