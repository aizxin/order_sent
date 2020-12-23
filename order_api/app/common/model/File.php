<?php
/**
 * FileName: File.php
 * ==============================================
 * Copy right 2016-2018
 * ----------------------------------------------
 * This is not a free software, without any authorization is not allowed to use and spread.
 * ==============================================
 * @author: 永 | <chuanshuo_yongyuan@163.com>
 * @date  : 2019/12/2 15:22
 */
declare (strict_types = 1);

namespace app\common\model;


class File extends BaseModel
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