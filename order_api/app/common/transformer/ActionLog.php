<?php

namespace app\common\transformer;

class ActionLog extends Transformer
{
   public function transform($item, array $other = [])
   {
       if (empty($item)) return [];
       $item->type_text = $item->type_text;

       return $item;
   }
}

