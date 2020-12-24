<?php
/**
 * FileName: Controller.php
 * ==============================================
 * Copy right 2016-2018
 * ----------------------------------------------
 * This is not a free software, without any authorization is not allowed to use and spread.
 * ==============================================
 * @author: kong | <iwhero@yeah.com>
 * @date  : 2020-12-24 21:26
 */
declare (strict_types = 1);

namespace app\admin\controller;

use app\common\controller\BaseController;

use app\common\logic\Article as ArticleLogic;
use app\common\validate\Article as ArticleValidate;

class Article extends BaseController
{
    private $articleLogic;

    public function __construct(ArticleLogic $articleLogic)
    {
        parent::__construct();
        $this->articleLogic = $articleLogic;
    }

    public function lists()
    {
        $page = $this->request->get('page/d', 1);
        $size = $this->request->get('size/d', 20);
        $where = $this->_getQueryWhere();
        $result = $this->articleLogic->lists($where, $page, $size);
        return $this->response($result);
    }

    public function info()
    {
        $id = $this->request->get('id/d');
        if ( ! $id) {
            $this->error('请选择需要操作的数据');
        }
        $result = $this->articleLogic->info($id);
        return $this->response($result);
    }

    public function add()
    {
        return $this->operate('add', 'add_article');
    }

    public function edit()
    {
        return $this->operate('edit', 'edit_article', 'edit');
    }

    public function status()
    {
        return $this->statusAction(\app\common\model\Article::class, 'status_article');
    }

    public function delete()
    {
        $id = $this->request->post('id/d');
        if ( ! $id) {
            $this->error('请选择需要操作的数据');
        }
        $result = $this->articleLogic->delete($id);
        return $this->returnSuccessOrError($result, 'delete_article');
    }

    private function operate($method, $action, $scene = '')
    {
        $data = $this->requestPostData();
        $this->validate($data, ArticleValidate::class , $scene);
        $result = $this->articleLogic->$method($data);
        return $this->returnSuccessOrError($result, $action);
    }

    private function _getQueryWhere()
    {
        $where = [];

        return $where;
    }
}
