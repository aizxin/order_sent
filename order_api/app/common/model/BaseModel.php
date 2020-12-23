<?php
/**
 * FileName: BaseModel.php
 * ==============================================
 * Copy right 2016-2018
 * ----------------------------------------------
 * This is not a free software, without any authorization is not allowed to use and spread.
 * ==============================================
 * @author: kong | <iwhero@yeah.com>
 * @date  : 2020/6/1 11:33 下午
 */
declare (strict_types = 1);

namespace app\common\model;


use think\Model;
use think\model\concern\SoftDelete;

class BaseModel extends Model
{
    use SoftDelete;
    protected $deleteTime = 'delete_time';
    protected $defaultSoftDelete = 0;
    protected $hidden = ['delete_time'];

    public function getStatusTextAttr($value, $data)
    {
        $status = [-1 => '删除', 0 => '禁用', 1 => '正常', 2 => '待审核'];

        return $status[ $data['status'] ];
    }
}