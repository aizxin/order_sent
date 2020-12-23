<?php

namespace app\common\model;

class Admin extends BaseModel
{
    protected $name = 'admin_user';

    protected $hidden = ['delete_time', 'password'];

    public function setPasswordAttr($value)
    {
        return \Crypt::encrypt($value, config('user.user_key'));
    }
}

