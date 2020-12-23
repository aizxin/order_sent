<?php
/**
 * FileName: Controller.php
 * ==============================================
 * Copy right 2016-2018
 * ----------------------------------------------
 * This is not a free software, without any authorization is not allowed to use and spread.
 * ==============================================
 * @author: kong | <iwhero@yeah.com>
 * @date  : 2020-06-17 15:20
 */
declare (strict_types = 1);

namespace app\admin\controller;

use app\common\controller\BaseController;

use app\common\logic\ActionLog as ActionLogLogic;
use app\common\validate\ActionLog as ActionLogValidate;

class ActionLog extends BaseController
{
    private $actionLogLogic;

    public function __construct(ActionLogLogic $actionLogLogic)
    {
        parent::__construct();
        $this->actionLogLogic = $actionLogLogic;
    }

    public function lists()
    {
        $page = $this->request->get('page/d', 1);
        $size = $this->request->get('size/d', 20);
        $where = $this->_getQueryWhere();
        $result = $this->actionLogLogic->lists($where, $page, $size);
        $this->response($result);
    }

    public function info()
    {
        $id = $this->request->get('id/d');
        if ( ! $id) {
            $this->error('请选择需要操作的数据');
        }
        $result = $this->actionLogLogic->info($id);
        $this->response($result);
    }

    public function add()
    {
        $this->operate('add', 'add_actionLog');
    }

    public function edit()
    {
        $this->operate('edit', 'edit_actionLog', 'edit');
    }

    public function status()
    {
        $this->statusAction(\app\common\model\ActionLog::class, 'status_actionLog');
    }

    public function delete()
    {
        $id = $this->request->post('id/d');
        if ( ! $id) {
            $this->error('请选择需要操作的数据');
        }
        $result = $this->actionLogLogic->delete($id);
        $this->returnSuccessOrError($result, 'delete_actionLog');
    }

    private function operate($method, $action, $scene = '')
    {
        $data = $this->requestPostData();
        $scene = $scene ? '.' . $scene : '';
        $this->validate($data, ActionLogValidate::class . $scene);
        $result = $this->actionLogLogic->$method($data);
        $this->returnSuccessOrError($result, $action);
    }

    private function _getQueryWhere()
    {
        $where = [];

        return $where;
    }
}
