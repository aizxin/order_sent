<?php

namespace app\common\model;

class Rule extends BaseModel
{
    public function rule()
    {
        return $this->hasMany(Rule::class, 'pid');
    }
}

