<?php

use think\migration\Migrator;
use think\migration\db\Column;

class User extends Migrator
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-abstractmigration-class
     *
     * The following commands can be used in this method and Phinx will
     * automatically reverse them when rolling back:
     *
     *    createTable
     *    renameTable
     *    addColumn
     *    renameColumn
     *    addIndex
     *    addForeignKey
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function change()
    {
        /**
         * 会员表
         */
        $table = $this->table('user', ['comment' => '会员表']);
        $table->addColumn('username', 'string', ['limit' => 100, 'comment' => '用户名'])
            ->addColumn('sex', 'integer', ['limit' => 1, 'default' => 1, 'comment' => '性别：1男，2女'])
            ->addColumn('age', 'integer', ['limit' => 2, 'default' => 0, 'comment' => '年龄'])
            ->addColumn('name', 'string', ['limit' => 100, 'comment' => '昵称', 'default' => ''])
            ->addColumn('address', 'string', ['limit' => 255, 'comment' => '地址', 'default' => ''])
            ->addColumn('mobile', 'string', ['limit' => 32, 'comment' => '手机', 'default' => ''])
            ->addColumn('mobile_other', 'string', ['limit' => 255, 'comment' => '手机', 'default' => ''])
            ->addColumn('operation', 'text', ['comment' => '手术内容'])
            ->addColumn('remark', 'text', ['comment' => '备注'])
            ->addColumn('status', 'integer', ['limit' => 1, 'default' => 1, 'comment' => '状态'])
            ->addColumn('admin_id', 'integer', ['limit' => 5, 'default' => 1, 'comment' => '条件用户id'])
            ->addColumn('create_time', 'integer', ['limit' => 11, 'default' => 0, 'comment' => '创建时间'])
            ->addColumn('update_time', 'integer', ['limit' => 11, 'default' => 0, 'comment' => '修改时间'])
            ->addColumn('delete_time', 'integer', ['limit' => 11, 'default' => 0, 'comment' => '删除时间'])
            ->save();
    }
}
