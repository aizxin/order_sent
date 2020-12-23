<?php
/**
 * FileName: BaseLogic.php
 * ==============================================
 * Copy right 2016-2018
 * ----------------------------------------------
 * This is not a free software, without any authorization is not allowed to use and spread.
 * ==============================================
 * @author: kong | <iwhero@yeah.com>
 * @date  : 2020/6/17 11:30 上午
 */
declare (strict_types = 1);

namespace app\common\logic;

use think\Model;

abstract class BaseLogic
{

    abstract public function modelClassName();

    /**
     * @var Model
     */
    public $model;

    public function makeModel(): Model
    {
        $model = app()->make($this->modelClassName());

        if ( ! $model instanceof Model) {
            throw new \HttpRequestPoolException("Class {$this->modelClassName()} must be an instance of think\\Model");
        }

        return $this->model = $model;
    }

    /**
     * @return mixed
     */
    public function getModel(): Model
    {
        return $this->model;
    }

    /**
     * 新增数据
     *
     * @param array $data
     *
     * @return Admin|bool|\think\Model
     * @author: kong | <iwhero@yeah.com>
     */
    public function add(array $data)
    {
        if (empty($data)) return false;
        unset($data['id']);

        return $this->model->create($data);
    }

    /**
     * 更新数据
     *
     * @param array $data 需要更新的数据
     *                    data 里面必须包含主键的值,
     *                    通常为 ID
     *                    例如:需要更新的数据位 $data=['id'=>1,'name'=>'需要更新的 name']
     *
     * @return Admin|bool
     * @author: kong | <iwhero@yeah.com>
     */
    public function edit(array $data)
    {
        if (empty($data)) return false;
        $info = $this->model->find($data['id']);
        if (empty($info)) return false;

        return $this->model->update($data);
    }

    /**
     * 删除数据
     *
     * @param int  $id    需要删除数据的主键 ID
     * @param bool $force 是否真实删除,默认为 false,当传递 true 的时候,为真实删除
     *
     * @return bool
     * @author: kong | <iwhero@yeah.com>
     */
    public function delete(int $id, bool $force = false): bool
    {
        if ( ! $id) return false;

        return $this->model->destroy($id, $force);
    }

    /**
     * 查找单条记录
     * @access public
     *
     * @param mixed $id 查询数据
     */
    public function first($id)
    {
        $result = $this->model->find($id);

        return $result;
    }

    /**
     * 查找记录
     * @access public
     *
     * @param mixed $data 数据
     */
    public function get($data = null)
    {
        $result = $this->model->select($data);

        return $result;
    }

    /**
     * 分页查询
     * @access public
     *
     * @param int|array $listRows 每页数量 数组表示配置参数
     * @param int|bool  $simple   是否简洁模式或者总记录数
     */
    public function paginate($perPage = 10, $simple = false)
    {
        if (is_int($simple)) {
            $total = $simple;
            $simple = false;
        }

        $result = $this->model->paginate($perPage, $simple);

        return $result;
    }

    /**
     * 写入数据
     * @access public
     *
     * @param array  $data       数据数组
     * @param array  $allowField 允许字段
     * @param bool   $replace    使用Replace
     * @param string $suffix     数据表后缀
     *
     * @return static
     */
    public function create(array $data, array $allowField = [], bool $replace = false, string $suffix = '')
    {
        $result = $this->model->create($data);

        return $result;
    }

    /**
     * 保存当前数据对象
     * @access public
     *
     * @param array  $data     数据
     * @param string $sequence 自增序列名
     *
     * @return bool
     */
    public function save(array $data = [], string $sequence = null)
    {
        $this->model->save($data, $sequence);
    }

    /**
     * 更新记录
     * @access public
     *
     * @param mixed $data 数据
     *
     * @return integer
     * @throws Exception
     */
    public function update($where, array $data = [])
    {
        $where = $this->makeWhere($where);

        $data = $this->model->where($where)->update($data);

        return $data;
    }

    /**
     * 查找单条记录 如果不存在则抛出异常
     * @access public
     *
     * @param mixed $data 查询数据
     *
     * @return array|Model|null
     */
    public function find($id)
    {
        $model = $this->model->findOrFail($id);

        return $model;
    }

    /**
     * 指定AND查询条件 查找单条记录
     *
     * @param       $where
     * @param array $columns
     *
     * @return mixed
     */
    public function findWhere($where, $columns = ['*'])
    {
        $where = $this->makeWhere($where);

        $model = $this->model->field($columns)->where($where)->find();

        return $model;
    }

    /**
     * 按字段和值查找数据
     *
     * @param      $field
     * @param null $value
     *
     * @return mixed
     */
    public function findByField($field, $value = null, $columns = ['*'])
    {
        $model = $this->model->field($columns)->where($field, '=', $value)->select();
        $this->resetModel();

        return $model;
    }

    /**
     * WHEREIN查找数据
     *
     * @param       $field
     * @param array $values
     * @param array $columns
     *
     * @return mixed
     */
    public function findWhereIn($field, array $values, $columns = ['*'])
    {
        $model = $this->model->field($columns)->whereIn($field, $values)->select();

        return $model;
    }

    /**
     * WHERE NOT IN查找数据
     *
     * @param       $field
     * @param array $values
     * @param array $columns
     *
     * @return mixed
     */
    public function findWhereNotIn($field, array $values, $columns = ['*'])
    {
        $model = $this->model->field($columns)->whereNotIn($field, $values)->select();

        return $model;
    }

    /**
     * 区域查询
     *
     * @param       $field
     * @param array $values
     * @param array $columns
     *
     * @return mixed
     */
    public function findWhereBetween($field, array $values, $columns = ['*'])
    {
        $model = $this->model->field($columns)->whereBetween($field, $values)->select();

        return $model;
    }

    /**
     * 按多个字段删除存储库中的条目
     *
     * @param array $where
     *
     * @return mixed
     */
    public function deleteWhere(array $where)
    {
        $model = $this->model->where($where)->delete();

        return $model;
    }

    /**
     * 关联查询
     *
     * @param array $relations
     *
     * @return $this
     */
    public function with(array $relations)
    {
        $this->model = $this->model->with($relations);

        return $this;
    }


    /**
     * 数据搜索
     *
     * @param array $keyword
     *
     * @return $this
     */
    public function search(array $keyword)
    {
        $this->model = $this->model->withSearch(array_keys(array_filter($keyword, function ($keyword) {
            if ($keyword === '' || $keyword === null) {
                return false;
            }

            return true;
        })), array_filter($keyword, function ($keyword) {
            if ($keyword === '' || $keyword === null) {
                return false;
            }

            return true;
        }));;

        return $this;
    }

    /**
     * 排序
     *
     * @param $order
     *
     * @return $this
     */
    public function order($order)
    {
        if (is_array($order)) {
            $query = $this->model->order($order);
        } else {
            $query = $this->model->order('id', $order);
        }

        $this->model = $query;

        return $this;
    }


    /**
     * @param $where
     *
     * @return array
     */
    public function makeWhere($where)
    {
        if (is_array($where)) {
            $where = $where;
        } else {
            $where = [
                [
                    'id', '=', $where,
                ],
            ];
        }

        return $where;
    }

    /**
     * 获取 其它 模型 实列
     *
     * @param mixed $model
     *
     * @return Model
     */
    public function baseModel($modelName): Model
    {
        return app()->make($modelName);
    }
}