<?php
/**
 * FileName: ActionLogic.php
 * ==============================================
 * Copy right 2016-2018
 * ----------------------------------------------
 * This is not a free software, without any authorization is not allowed to use and spread.
 * ==============================================
 *@author: kong | <iwhero@yeah.com>
 * @date  : 2020-06-17 12:44
 */
declare (strict_types = 1);

namespace app\common\logic;

use app\common\model\Cate as CateModel;
use app\common\transformer\Cate as CateTransformer;

class Cate extends BaseLogic
{
    protected $cateTransformer;

    public function __construct(CateTransformer $cateTransformer)
    {
        $this->cateTransformer = $cateTransformer;
        $this->makeModel();
    }

    /**
     * 绑定 数据表模型 namespace Name
     * @return string
     * @date  : 2020/6/17 11:38 上午
     */
    public function modelClassName()
    {
        return CateModel::class;
    }

    public function lists(array $where=[], string $order = 'id desc', $withoutField = false)
    {
        $lists = $this->model->where($where)->withoutField($withoutField)->order($order)->select();
        $lists = $this->cateTransformer->transformCollection($lists->all());

        return $lists;
    }

    public function info($id)
    {
        if ( ! $id) return [];

        $info = $this->model->find($id);

        return $this->cateTransformer->transform($info);
    }
}