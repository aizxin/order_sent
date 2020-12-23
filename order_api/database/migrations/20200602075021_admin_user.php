<?php

use think\migration\Migrator;
use think\migration\db\Column;

class AdminUser extends Migrator
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
         * 管理员表
         */
        $table = $this->table('admin_user', ['comment' => '管理员表']);
        $table->addColumn('username', 'string', ['limit' => 100, 'comment' => '用户名'])
            ->addColumn('name', 'string', ['limit' => 100, 'comment' => '昵称', 'default' => ''])
            ->addColumn('password', 'string', ['limit' => 500, 'comment' => '用户密码', 'default' => ''])
            ->addColumn('email', 'string', ['limit' => 100, 'comment' => '邮箱', 'default' => ''])
            ->addColumn('mobile', 'string', ['limit' => 32, 'comment' => '手机', 'default' => ''])
            ->addColumn('avatar', 'string', ['limit' => 32, 'comment' => '头像', 'default' => ''])
            ->addColumn('create_ip', 'string', ['limit' => 32, 'comment' => '创建 IP', 'default' => ''])
            ->addColumn('last_login_ip', 'string', ['limit' => 31, 'comment' => '上次登录 IP', 'default' => ''])
            ->addColumn('status', 'integer', ['limit' => 1, 'default' => 1, 'comment' => '状态'])
            ->addColumn('sort', 'integer', ['limit' => 3, 'default' => 0, 'comment' => '排序'])
            ->addColumn('create_time', 'integer', ['limit' => 11, 'default' => 0, 'comment' => '创建时间'])
            ->addColumn('update_time', 'integer', ['limit' => 11, 'default' => 0, 'comment' => '修改时间'])
            ->addColumn('delete_time', 'integer', ['limit' => 11, 'default' => 0, 'comment' => '删除时间'])
            ->save();
    }

    public function down()
    {
    }

    public function up()
    {
    }
}
