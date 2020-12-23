<?php

namespace app\common\model;

class AdminLogin extends BaseModel
{
    protected $name = 'admin_login_log';

    public function getLoginTimeAttr($value)
    {
        return $this->formatDateTime('Y-m-d H:i',$value,true);
    }
}

