<?php

use think\migration\Migrator;
use think\migration\db\Column;

class PairingOrder extends Migrator
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
         * 配单表
         */
        $table = $this->table('pairing_order', ['comment' => '配单表']);
        $table->addColumn('admin_id', 'integer', ['limit' => 5, 'default' => 1, 'comment' => '配单人id'])
            ->addColumn('user_id', 'integer', ['limit' => 5, 'default' => 1, 'comment' => '手术人id'])
            ->addColumn('hospital_id', 'integer', ['limit' => 5, 'default' => 1, 'comment' => '医院id'])
            ->addColumn('hospital_user_id', 'integer', ['limit' => 5, 'default' => 1, 'comment' => '医院接单人id'])
            ->addColumn('status', 'integer', ['limit' => 1, 'default' => 0, 'comment' => '是否被接单：-1：单已失效，0：未被接单，1：已接单，2：完成'])
            ->addColumn('remark', 'text', ['comment' => '上传重单说明'])
            ->addColumn('thumbs', 'text', ['comment' => '上传重单图片'])
            ->addColumn('failure_time', 'integer', ['limit' => 11, 'default' => 0, 'comment' => '失效时间'])
            ->addColumn('receiving_time', 'integer', ['limit' => 11, 'default' => 0, 'comment' => '接单时间'])
            ->addColumn('finish_time', 'integer', ['limit' => 11, 'default' => 0, 'comment' => '完成时间'])
            ->addColumn('create_time', 'integer', ['limit' => 11, 'default' => 0, 'comment' => '创建时间'])
            ->addColumn('update_time', 'integer', ['limit' => 11, 'default' => 0, 'comment' => '修改时间'])
            ->addColumn('delete_time', 'integer', ['limit' => 11, 'default' => 0, 'comment' => '删除时间'])
            ->save();
    }
}
