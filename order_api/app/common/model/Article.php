<?php

namespace app\common\model;

use Overtrue\Pinyin\Pinyin;

class Article extends BaseModel
{
    /**
     * 自动生成文章标题的拼音
     * 不要问为什么需要这样,鬼知道后面所谓的大数据检索需要什么骚操作,就当是预留出来好了
     *
     * @param $value
     * @param $data
     *
     * @author: 永 | <chuanshuo_yongyuan@163.com>
     */
    public function setNamePinyinAttr($value, $data)
    {
        $pinyin = new Pinyin();
        $name_pinying = $pinyin->convert($value, PINYIN_NO_TONE);

        return arr2str($name_pinying, ' ');
    }

    /**
     * 设置发布时间
     *
     * @param $value
     *
     * @return false|int
     * @author: 永 | <chuanshuo_yongyuan@163.com>
     */
    public function setPublishTimeAttr($value)
    {
        return strtotime($value);
    }

    /**
     * 获取格式化以后的发布时间
     *
     * @param $value
     * @param $data
     *
     * @return string
     * @author: 永 | <chuanshuo_yongyuan@163.com>
     */
    public function getPublishTimeTextAttr($value, $data)
    {
        return time_format($data['publish_time']);
    }

    /**
     * @param $value
     * @param $data
     *
     * @return mixed
     * @author: 永 | <chuanshuo_yongyuan@163.com>
     */
    public function getResurlAttr($value, $data)
    {
        if ($data['article_type'] == 'picture') {
            return json_decode($data['resurl']);
        }

        return $data['resurl'];
    }

    /**
     * @param $value
     * @param $data
     *
     * @return false|string
     * @author: 永 | <chuanshuo_yongyuan@163.com>
     */
    public function setResurlAttr($value, $data)
    {
        if ($data['article_type'] == 'picture') {
            if (is_array($value)) {
                return json_encode($value);
            }
        }

        return $value;
    }

    /**
     * 获取文章类型说明
     *
     * @param $value
     * @param $data
     *
     * @return mixed
     * @author: 永 | <chuanshuo_yongyuan@163.com>
     */
    public function getArticleTypeTextAttr($value, $data)
    {
        $article_type = $data['article_type'];
        $type = [
            'normal'  => '普通文章',
            'video'   => '视频',
            'voice'   => '音频',
            'picture' => '图片',
        ];

        return $type[ $article_type ];
    }

    /**
     * 设置文章内容属性
     *
     * @param $value
     *
     * @return string
     * @author: 永 | <chuanshuo_yongyuan@163.com>
     */
    public function setContentAttr($value)
    {
        return htmlspecialchars_decode($value);
    }

    /**
     * 关联文章分类
     * @return \think\model\relation\BelongsTo
     * @author: 永 | <chuanshuo_yongyuan@163.com>
     */
    public function category()
    {
        return $this->belongsTo(ArticleCategory::class)->field('id,name,img,name_pinyin');
    }
}

