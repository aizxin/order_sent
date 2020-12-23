<?php
/**
 * FileName: Transform.php
 * ==============================================
 * Copy right 2016-2018
 * ----------------------------------------------
 * This is not a free software, without any authorization is not allowed to use and spread.
 * ==============================================
 * @author: kong | <iwhero@yeah.com>
 * @date  : 2020/6/1 11:02 下午
 */
declare (strict_types = 1);

namespace app\common\transformer;


abstract class Transformer
{
    /**
     * @param $items
     *
     * @return array
     */
    public function transformPages($items)
    {
        $items = [
            'total'       => $items['total'],
            'perPage'     => $items['per_page'],
            'currentPage' => $items['current_page'],
            'lastPage'    => $items['last_page'],
            'data'        => array_map([$this, 'transform'], $items['data']),
        ];

        return $items;
    }

    /**
     * @param array $items
     * @param array $other
     *
     * @return array
     */
    public function transformCollection(array $items = [], array $other = []): array
    {
        if (empty($items)) return [];

        return array_map(function ($item) use ($other) {
            return $this->transform($item, $other);
        }, $items);
    }

    /**
     * @return mixed
     */
    public abstract function transform($item, array $other = []);

    /**
     * @param array $items
     *
     * @return array
     */
    public function arrayFilter(array $items = [])
    {
        return array_values(array_filter($items));
    }
}