<?php
/**
 * FileName: Table.php
 * ==============================================
 * Copy right 2016-2018
 * ----------------------------------------------
 * This is not a free software, without any authorization is not allowed to use and spread.
 * ==============================================
 * @author: kong | <iwhero@yeah.com>
 * @date  : 2020/4/27 8:25 下午
 */
declare (strict_types = 1);

namespace websocket;


use Swoole\Table as SwooleTable;

class Table
{
    /**
     * @var SwooleTable
     */
    protected $table;

    public function __construct()
    {
        $config = app()->config->get('swoole.websocket.room.table');
        $this->table = new SwooleTable(65536);
        $this->table->column('value', SwooleTable::TYPE_STRING, $config['client_size'] ?? 2048);
        $this->table->create();
    }

    /**
     * Set value to table
     *
     * @param        $key
     * @param array  $value
     * @param string $table
     *
     */
    public function setValue($key, $value = [])
    {
        $this->checkTable();

        $this->table->set($key, ['value' => json_encode($value)]);

        return $this;
    }

    /**
     * Get value from table
     *
     * @param string $key
     * @param string $table
     *
     * @return array|mixed
     */
    public function getValue(string $key)
    {
        $this->checkTable();

        $value = $this->table->get($key);

        return $value ? json_decode($value['value'], true) : [];
    }

    /**
     * Get value from table
     *
     * @param string $key
     *
     * @return array|mixed
     */
    public function delValue(string $key)
    {
        $this->checkTable();

        return $this->table->del($key);
    }

    /**
     * Check table for exists
     *
     * @param string $table
     */
    protected function checkTable()
    {
        if ( ! $this->table instanceof SwooleTable) {
            throw new InvalidArgumentException("Invalid table");
        }
    }

}