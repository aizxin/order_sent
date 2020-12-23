<?php

use think\migration\Migrator;
use think\migration\db\Column;

class AdminLoginLog extends Migrator
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
        $table = $this->table('admin_login_log', ['comment' => '管理员登陆日志表']);
        $table->addColumn('name', 'string', ['limit' => 50, 'default' => '', 'comment' => '管理员'])
            ->addColumn('admin_id', 'integer', ['limit' => 3, 'default' => 0, 'comment' => '管理员 ID'])
            ->addColumn('login_ip', 'string', ['limit' => 50, 'default' => '', 'comment' => '登陆IP'])
            ->addColumn('login_time', 'string', ['limit' => 50, 'default' => '', 'comment' => '登陆时间'])
            ->addColumn('create_time', 'integer', ['limit' => 11, 'default' => 0, 'comment' => '创建时间'])
            ->addColumn('update_time', 'integer', ['limit' => 11, 'default' => 0, 'comment' => '修改时间'])
            ->addColumn('delete_time', 'integer', ['limit' => 11, 'default' => 0, 'comment' => '删除时间'])
            ->save();
    }
}
