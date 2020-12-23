<?php

use think\migration\Migrator;
use think\migration\db\Column;

class File extends Migrator
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
        $table = $this->table('file', ['comment' => '文件上传表']);
        $table->addColumn('original_name', 'string', ['limit' => 500, 'default' => '', 'null' => true, 'comment' => '文件原名称'])
            ->addColumn('file_name', 'string', ['limit' => 500, 'default' => '', 'null' => true, 'comment' => '文件名称'])
            ->addColumn('path', 'string', ['limit' => 500, 'comment' => '存储路劲', 'default' => ''])
            ->addColumn('url', 'string', ['limit' => 500, 'comment' => '访问地址', 'default' => ''])
            ->addColumn('md5', 'string', ['limit' => 100, 'comment' => 'MD5值', 'default' => ''])
            ->addColumn('sha1', 'string', ['limit' => 100, 'comment' => 'hash 值', 'default' => ''])
            ->addColumn('status', 'integer', ['limit' => 1, 'default' => 1, 'comment' => '状态'])
            ->addColumn('create_time', 'integer', ['limit' => 11, 'default' => 0, 'comment' => '创建时间'])
            ->addColumn('update_time', 'integer', ['limit' => 11, 'default' => 0, 'comment' => '修改时间'])
            ->addColumn('delete_time', 'integer', ['limit' => 11, 'default' => 0, 'comment' => '删除时间'])
            ->addIndex(['md5', 'sha1'])
            ->save();
    }
}
