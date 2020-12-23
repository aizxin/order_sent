<?php

use think\migration\Migrator;
use think\migration\db\Column;

class Role extends Migrator
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
        $table = $this->table('role', ['comment' => '角色表']);
        $table->addColumn('name', 'string', ['limit' => 100, 'default' => '', 'comment' => '角色名称'])
            ->addColumn('key', 'string', ['limit' => 200, 'default' => '', 'comment' => 'key'])
            ->addColumn('remark', 'string', ['limit' => 200, 'default' => '', 'comment' => '描述'])
            ->addColumn('sort', 'integer', ['limit' => 3, 'default' => 0, 'comment' => '排序'])
            ->addColumn('status', 'integer', ['limit' => 1, 'default' => 1, 'comment' => '状态（0 - 禁用，1 - 启用，-1 - 删除）'])
            ->addColumn('create_time', 'integer', ['limit' => 11, 'default' => 0, 'comment' => '创建时间'])
            ->addColumn('update_time', 'integer', ['limit' => 11, 'default' => 0, 'comment' => '修改时间'])
            ->addColumn('delete_time', 'integer', ['limit' => 11, 'default' => 0, 'comment' => '删除时间'])
            ->save();
    }
}
