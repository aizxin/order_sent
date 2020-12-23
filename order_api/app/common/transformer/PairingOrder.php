<?php

namespace app\common\transformer;

class PairingOrder extends Transformer
{
   public function transform($item, array $other = [])
   {
       if (empty($item)) return [];
       $item->status_text = $item->status_text;

       return $item;
   }
}

