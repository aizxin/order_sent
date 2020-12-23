<?php

use think\migration\Migrator;
use think\migration\db\Column;

class Rule extends Migrator
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
        $table = $this->table('rule', ['comment' => '权限节点表']);
        $table->addColumn('path', 'string', ['limit' => 200, 'default' => '', 'comment' => '路由 URL,例如: /path/xxx/xxx'])
            ->addColumn('pid', 'integer', ['limit' => 11, 'default' => 0, 'comment' => '上一级 ID'])
            ->addColumn('name', 'string', ['limit' => 100, 'default' => '', 'comment' => '路由名称,必须为英文,便于翻译多语言和多语言缓存'])
            ->addColumn('component', 'string', ['limit' => 200, 'default' => '', 'comment' => '路由组件,views 目录下对应的页面组件'])
            ->addColumn('redirect', 'string', ['limit' => 200, 'default' => 'noRedirect', 'comment' => '需要重定向的地址'])
            ->addColumn('type', 'integer', ['limit' => 3, 'default' => 1, 'comment' => '1：菜单，2：按钮'])
            ->addColumn('action', 'string', ['limit' => 200, 'default' => '', 'comment' => '对应可操作节点'])
            ->addColumn('title', 'string', ['limit' => 200, 'default' => '', 'comment' => '路由显示名称'])
            ->addColumn('icon', 'string', ['limit' => 200, 'default' => '', 'comment' => '路由显示icon'])
            ->addColumn('route', 'text', ['comment' => '接口路由'])
            ->addColumn('sort', 'integer', ['limit' => 3, 'default' => 0, 'comment' => '排序'])
            ->addColumn('status', 'integer', ['limit' => 1, 'default' => 1, 'comment' => '状态（0 - 禁用，1 - 启用，-1 - 删除）'])
            ->addColumn('create_time', 'integer', ['limit' => 11, 'default' => 0, 'comment' => '创建时间'])
            ->addColumn('update_time', 'integer', ['limit' => 11, 'default' => 0, 'comment' => '修改时间'])
            ->addColumn('delete_time', 'integer', ['limit' => 11, 'default' => 0, 'comment' => '删除时间'])
            ->save();
    }
}
