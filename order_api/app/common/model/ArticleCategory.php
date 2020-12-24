<?php

namespace app\common\model;

use Overtrue\Pinyin\Pinyin;

class ArticleCategory extends BaseModel
{
    public function setNamePinyinAttr($value)
    {
        $pinyin = new Pinyin();
        $name_pinying = $pinyin->convert($value, PINYIN_NO_TONE);

        return arr2str($name_pinying, ' ');
    }
}

