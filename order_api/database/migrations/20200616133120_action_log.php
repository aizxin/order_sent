<?php

use think\migration\Migrator;
use think\migration\db\Column;

class ActionLog extends Migrator
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
         * 行为日志表
         */
        $table = $this->table('action_log', ['comment' => '行为日志表']);
        $table->addColumn('action_id', 'string', ['limit' => 100, 'comment' => '行为id'])
            ->addColumn('action_name', 'string', ['limit' => 100, 'comment' => '行为操作'])
            ->addColumn('uid', 'integer', ['limit' => 11, 'comment' => '用户 ID'])
            ->addColumn('username', 'string', ['limit' => 100, 'comment' => '操作用户', 'default' => ''])
            ->addColumn('type', 'integer', ['limit' => 1, 'comment' => '行为类型,1-后台行为,2-前台行为', 'default' => 1])
            ->addColumn('create_ip', 'string', ['limit' => 32, 'comment' => '操作 IP', 'default' => ''])
            ->addColumn('url', 'string', ['limit' => 500, 'comment' => '操作 URL', 'default' => ''])
            ->addColumn('request', 'text', ['comment' => '行为操作参数'])
            ->addColumn('status', 'integer', ['limit' => 1, 'default' => 1, 'comment' => '状态,0-禁用,1-启用'])
            ->addColumn('create_time', 'integer', ['limit' => 11, 'default' => 0, 'comment' => '创建时间'])
            ->addColumn('update_time', 'integer', ['limit' => 11, 'default' => 0, 'comment' => '修改时间'])
            ->addColumn('delete_time', 'integer', ['limit' => 11, 'default' => 0, 'comment' => '删除时间'])
            ->save();
    }
}
