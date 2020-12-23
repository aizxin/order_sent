<?php
/**
 * FileName: ActionLogic.php
 * ==============================================
 * Copy right 2016-2018
 * ----------------------------------------------
 * This is not a free software, without any authorization is not allowed to use and spread.
 * ==============================================
 *@author: kong | <iwhero@yeah.com>
 * @date  : 2020-06-17 12:36
 */
declare (strict_types = 1);

namespace app\common\logic;

use app\common\model\AdminLogin as AdminLoginModel;
use app\common\transformer\AdminLogin as AdminLoginTransformer;

class AdminLogin extends BaseLogic
{
    protected $adminLoginTransformer;

    public function __construct(AdminLoginTransformer $adminLoginTransformer)
    {
        $this->adminLoginTransformer = $adminLoginTransformer;
        $this->makeModel();
    }

    /**
     * 绑定 数据表模型 namespace Name
     * @return string
     * @date  : 2020/6/17 11:38 上午
     */
    public function modelClassName()
    {
        return AdminLoginModel::class;
    }

    public function lists(array $where=[],int $page = 1, int $size = 20, string $order = 'id desc', $withoutField = false)
    {
        $lists = $this->model->where($where)->withoutField($withoutField)->page($page, $size)->order($order)->select();
        $rows = $this->adminLoginTransformer->transformCollection($lists->all());
        $total = $this->model->where($where)->count();

        return compact('rows', 'total');
    }

    public function info($id)
    {
        if ( ! $id) return [];

        $info = $this->model->find($id);

        return $this->adminLoginTransformer->transform($info);
    }
}