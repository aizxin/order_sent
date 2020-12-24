<?php
/**
 * FileName: Controller.php
 * ==============================================
 * Copy right 2016-2018
 * ----------------------------------------------
 * This is not a free software, without any authorization is not allowed to use and spread.
 * ==============================================
 * @author: kong | <iwhero@yeah.com>
 * @date  : 2020-12-24 21:34
 */
declare (strict_types = 1);

namespace app\admin\controller;

use app\common\controller\BaseController;

use app\common\logic\ArticleCategory as ArticleCategoryLogic;
use app\common\validate\ArticleCategory as ArticleCategoryValidate;

class ArticleCategory extends BaseController
{
    private $articleCategoryLogic;

    public function __construct(ArticleCategoryLogic $articleCategoryLogic)
    {
        parent::__construct();
        $this->articleCategoryLogic = $articleCategoryLogic;
    }

    public function lists()
    {
        $page = $this->request->get('page/d', 1);
        $size = $this->request->get('size/d', 20);
        $where = $this->_getQueryWhere();
        $result = $this->articleCategoryLogic->lists($where, $page, $size);
        return $this->response($result);
    }

    public function info()
    {
        $id = $this->request->get('id/d');
        if ( ! $id) {
            $this->error('请选择需要操作的数据');
        }
        $result = $this->articleCategoryLogic->info($id);
        return $this->response($result);
    }

    public function add()
    {
        return $this->operate('add', 'add_articleCategory');
    }

    public function edit()
    {
        return $this->operate('edit', 'edit_articleCategory', 'edit');
    }

    public function status()
    {
        return $this->statusAction(\app\common\model\ArticleCategory::class, 'status_articleCategory');
    }

    public function delete()
    {
        $id = $this->request->post('id/d');
        if ( ! $id) {
            $this->error('请选择需要操作的数据');
        }
        $result = $this->articleCategoryLogic->delete($id);
        return $this->returnSuccessOrError($result, 'delete_articleCategory');
    }

    private function operate($method, $action, $scene = '')
    {
        $data = $this->requestPostData();
        $this->validate($data, ArticleCategoryValidate::class , $scene);
        $result = $this->articleCategoryLogic->$method($data);
        return $this->returnSuccessOrError($result, $action);
    }

    private function _getQueryWhere()
    {
        $where = [];

        return $where;
    }
}
