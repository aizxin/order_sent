<?php

namespace app\common\transformer;

class Admin extends Transformer
{
    public function transform($item, array $other = [])
    {
        if (empty($item)) return [];
        $item->status_text = $item->status_text;

        return $item;
    }

    public function ruleTransform($items = [], $ids = [])
    {
        if ( ! $items) return [];

        $items = array_map(function ($item) use ($ids) {

            $rule = array_map(function ($ite) use ($ids) {
                $ite = [
                    'id'     => $ite['id'] ?? '',
                    'title'  => $ite['title'] ?? '',
                    'action' => $ite['action'] ?? '',
                ];
                if ( ! $ids) return $ite;
                if (in_array($ite['id'], $ids)) return $ite;

                return [];
            }, $item['rule']);

           $return = [
                'id'      => $item['id'],
                'name'      => $item['name'],
                'pid'       => $item['pid'],
                'path'      => $item['path'],
                'component' => $item['component'],
                'meta'      => [
                    'title' => $item['title'],
                    'icon'  => $item['icon'],
                    'rule'  => $this->arrayFilter($rule),
                ],
            ];

           if ($item['redirect'] != 'noRedirect'){
               $return['redirect'] = $item['redirect'];
           }

            return $return;
        }, $items);

        return $this->arrayFilter($items);
    }
}

