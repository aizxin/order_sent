<?php

use think\migration\Migrator;
use think\migration\db\Column;

class RoleRule extends Migrator
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
        $table = $this->table('role_rule', ['comment' => '角色权限表']);
        $table->addColumn('role_id', 'integer', ['limit' => 11, 'comment' => '角色id'])
            ->addColumn('rule_id', 'integer', ['limit' => 11, 'comment' => '权限id'])
            ->addColumn('is_tree_half', 'integer', ['limit' => 2, 'default' => 0, 'comment' => '是否half节点'])
            ->addColumn('is_dir', 'integer', ['limit' => 2, 'default' => 0, 'comment' => '是否目录'])
            ->addColumn('create_time', 'integer', ['limit' => 11, 'default' => 0, 'comment' => '创建时间'])
            ->addColumn('update_time', 'integer', ['limit' => 11, 'default' => 0, 'comment' => '修改时间'])
            ->save();

    }
}
