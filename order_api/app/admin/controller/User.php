<?php
/**
 * FileName: Controller.php
 * ==============================================
 * Copy right 2016-2018
 * ----------------------------------------------
 * This is not a free software, without any authorization is not allowed to use and spread.
 * ==============================================
 * @author: kong | <iwhero@yeah.com>
 * @date  : 2020-06-17 12:43
 */
declare (strict_types = 1);

namespace app\admin\controller;

use app\common\controller\BaseController;

use app\common\logic\User as UserLogic;
use app\common\validate\User as UserValidate;

class User extends BaseController
{
    private $userLogic;

    public function __construct(UserLogic $userLogic)
    {
        parent::__construct();
        $this->userLogic = $userLogic;
    }

    public function lists()
    {
        $page = $this->request->get('page/d', 1);
        $size = $this->request->get('size/d', 20);
        $where = $this->_getQueryWhere();
        $result = $this->userLogic->lists($where, $page, $size);
        $this->response($result);
    }

    public function info()
    {
        $id = $this->request->get('id/d');
        if ( ! $id) {
            $this->error('请选择需要操作的数据');
        }
        $result = $this->userLogic->info($id);
        $this->response($result);
    }

    public function add()
    {
        $this->operate('add', 'add_user');
    }

    public function edit()
    {
        $this->operate('edit', 'edit_user', 'edit');
    }

    public function status()
    {
        $this->statusAction(\app\common\model\User::class, 'status_user');
    }

    public function delete()
    {
        $id = $this->request->post('id/d');
        if ( ! $id) {
            $this->error('请选择需要操作的数据');
        }
        $result = $this->userLogic->delete($id);
        $this->returnSuccessOrError($result, 'delete_user');
    }

    private function operate($method, $action, $scene = '')
    {
        $data = $this->requestPostData();
        $this->validate($data, UserValidate::class, $scene);
        $result = $this->userLogic->$method($data);
        $this->returnSuccessOrError($result, $action);
    }

    private function _getQueryWhere()
    {
        $where = [];

        return $where;
    }
}
