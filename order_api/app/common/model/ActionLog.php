<?php

namespace app\common\model;

class ActionLog extends BaseModel
{
    public function getRequestAttr($value)
    {
        return json_decode($value);
    }

    public function getTypeTextAttr($value, $data)
    {
        // 1-后台行为,2-前台行为
        return $data['type'] == 2 ? '后台操作' : '前台操作';
    }
}

