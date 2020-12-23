<?php
/**
 * FileName: Controller.php
 * ==============================================
 * Copy right 2016-2018
 * ----------------------------------------------
 * This is not a free software, without any authorization is not allowed to use and spread.
 * ==============================================
 * @author: kong | <iwhero@yeah.com>
 * @date  : 2020-06-17 12:44
 */
declare (strict_types = 1);

namespace app\admin\controller;

use app\common\controller\BaseController;

use app\common\logic\Cate as CateLogic;
use app\common\validate\Cate as CateValidate;

class Cate extends BaseController
{
    private $cateLogic;

    public function __construct(CateLogic $cateLogic)
    {
        parent::__construct();
        $this->cateLogic = $cateLogic;
    }

    public function lists()
    {
        $where = $this->_getQueryWhere();
        $result = $this->cateLogic->lists($where);
        $this->response(list_to_tree($result));
    }

    public function info()
    {
        $id = $this->request->get('id/d');
        if ( ! $id) {
            $this->error('请选择需要操作的数据');
        }
        $result = $this->cateLogic->info($id);
        $this->response($result);
    }

    public function add()
    {
        $this->operate('add', 'add_cate');
    }

    public function edit()
    {
        $this->operate('edit', 'edit_cate', 'edit');
    }

    public function status()
    {
        $this->statusAction($this->cateLogic->getModel(), 'status_cate');
    }

    public function delete()
    {
        $id = $this->request->post('id/d');
        if ( ! $id) {
            $this->error('请选择需要操作的数据');
        }
        $result = $this->cateLogic->delete($id);
        $this->returnSuccessOrError($result, 'delete_cate');
    }

    private function operate($method, $action, $scene = '')
    {
        $data = $this->requestPostData();
        $this->validate($data, CateValidate::class, $scene);
        $result = $this->cateLogic->$method($data);
        $this->returnSuccessOrError($result, $action);
    }

    private function _getQueryWhere()
    {
        $where = [];

        return $where;
    }
}
