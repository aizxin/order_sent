<?php
declare (strict_types = 1);

namespace app\common\model;

class Picture extends BaseModel
{
    public static function infoByMd5(string $md5)
    {
        return self::where('md5', $md5)->find();
    }

    public static function infoByHash(string $hash)
    {
        return self::where('sha1', $hash)->find();
    }
}
