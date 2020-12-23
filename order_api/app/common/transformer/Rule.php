<?php

namespace app\common\transformer;

class Rule extends Transformer
{
    public function transform($item, array $other = [])
    {
        if (empty($item)) return [];
        $item->status_text = $item->status_text;
        $item->value = $item->id;
        $item->label = $item->title;

        return $item->toArray();
    }
}

