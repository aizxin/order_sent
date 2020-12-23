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

use app\common\logic\PairingOrder as PairingOrderLogic;
use app\common\validate\PairingOrder as PairingOrderValidate;

class PairingOrder extends BaseController
{
    private $pairingOrderLogic;

    public function __construct(PairingOrderLogic $pairingOrderLogic)
    {
        parent::__construct();
        $this->pairingOrderLogic = $pairingOrderLogic;
    }

    public function lists()
    {
        $page = $this->request->get('page/d', 1);
        $size = $this->request->get('size/d', 20);
        $where = $this->_getQueryWhere();
        $result = $this->pairingOrderLogic->lists($where, $page, $size);
        $this->response($result);
    }

    public function info()
    {
        $id = $this->request->get('id/d');
        if ( ! $id) {
            $this->error('请选择需要操作的数据');
        }
        $result = $this->pairingOrderLogic->info($id);
        $this->response($result);
    }

    public function add()
    {
        $this->operate('add', 'add_pairingOrder');
    }

    public function edit()
    {
        $this->operate('edit', 'edit_pairingOrder', 'edit');
    }

    public function status()
    {
        $this->statusAction(\app\common\model\PairingOrder::class, 'status_pairingOrder');
    }

    public function delete()
    {
        $id = $this->request->post('id/d');
        if ( ! $id) {
            $this->error('请选择需要操作的数据');
        }
        $result = $this->pairingOrderLogic->delete($id);
        $this->returnSuccessOrError($result, 'delete_pairingOrder');
    }

    private function operate($method, $action, $scene = '')
    {
        $data = $this->requestPostData();
        $scene = $scene ? '.' . $scene : '';
        $this->validate($data, PairingOrderValidate::class . $scene);
        $result = $this->pairingOrderLogic->$method($data);
        $this->returnSuccessOrError($result, $action);
    }

    private function _getQueryWhere()
    {
        $where = [];

        return $where;
    }
}
