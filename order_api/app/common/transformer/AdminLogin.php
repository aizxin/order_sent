<?php

namespace app\common\transformer;

class AdminLogin extends Transformer
{
    public function transform($item, array $other = [])
    {
        if (empty($item)) return [];

        return $item;
    }
}

