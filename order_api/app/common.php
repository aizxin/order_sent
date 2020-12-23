<?php
// 应用公共文件

// 除了 E_NOTICE，报告其他所有错误
error_reporting(E_ALL ^ E_NOTICE);

if ( ! function_exists('mix')) {
    /**
     * @param        $path
     * @param string $manifestDirectory
     *
     * @return mixed
     * @author: 永 | <chuanshuo_yongyuan@163.com>
     */
    function mix($path, $manifestDirectory = '')
    {
        return app(\Mix\Mix::class)(...func_get_args());
    }
}
if ( ! function_exists('get_html_attr_by_tag')) {
    /**
     * 获取一段 html 富文本信息中指定标签的指定属性的值,默认获取 img
     *
     * @param string $content
     * @param string $attr
     * @param string $tag
     *
     * @return array
     * @author: 永 | <chuanshuo_yongyuan@163.com>
     */
    function get_html_attr_by_tag($content = "", $attr = "src", $tag = "img")
    {
        $arr = [];
        $attr = explode(',', $attr);
        $tag = explode(',', $tag);
        foreach ($tag as $i => $t) {
            foreach ($attr as $a) {
                preg_match_all("/<\s*" . $t . "\s+[^>]*?" . $a . "\s*=\s*(\'|\")(.*?)\\1[^>]*?\/?\s*>/i", $content, $match);
                foreach ($match[2] as $n => $m) {
                    $arr[ $t ][ $n ][ $a ] = $m;
                }
            }
        }

        return $arr;//array
    }
}


if ( ! function_exists('str2arr')) {
    /**
     * 字符串转换为数组，主要用于把分隔符调整到第二个参数
     *
     * @param string $str  要分割的字符串
     * @param string $glue 分割符
     *
     * @return array
     * @author: 永 | <chuanshuo_yongyuan@163.com>
     */
    function str2arr($str, $glue = ',')
    {
        if (empty($str) || $str == '') return [];

        return explode($glue, $str);
    }
}
if ( ! function_exists('arr2str')) {
    /**
     * 数组转换为字符串，主要用于把分隔符调整到第二个参数
     *
     * @param array  $arr  要连接的数组
     * @param string $glue 分割符
     *
     * @return string
     * @author: 永 | <chuanshuo_yongyuan@163.com>
     */
    function arr2str($arr, $glue = ',')
    {
        if (empty($arr) || $arr == '') return '';

        return implode($glue, $arr);
    }
}
if ( ! function_exists('msubstr')) {
    /**
     * 字符串截取，支持中文和其他编码
     * @static
     * @access public
     *
     * @param string $str     需要转换的字符串
     * @param string $start   开始位置
     * @param string $length  截取长度
     * @param string $charset 编码格式
     * @param string $suffix  截断显示字符
     *
     * @return string
     * @author : 永 | <chuanshuo_yongyuan@163.com>
     */
    function msubstr($str, $start = 0, $length, $charset = "utf-8", $suffix = true)
    {
        if (function_exists("mb_substr")) {
            $slice = mb_substr($str, $start, $length, $charset);
        } elseif (function_exists('iconv_substr')) {
            $slice = iconv_substr($str, $start, $length, $charset);
            if (false === $slice) {
                $slice = '';
            }
        } else {
            $re['utf-8'] = "/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|[\xe0-\xef][\x80-\xbf]{2}|[\xf0-\xff][\x80-\xbf]{3}/";
            $re['gb2312'] = "/[\x01-\x7f]|[\xb0-\xf7][\xa0-\xfe]/";
            $re['gbk'] = "/[\x01-\x7f]|[\x81-\xfe][\x40-\xfe]/";
            $re['big5'] = "/[\x01-\x7f]|[\x81-\xfe]([\x40-\x7e]|\xa1-\xfe])/";
            preg_match_all($re[ $charset ], $str, $match);
            $slice = join("", array_slice($match[0], $start, $length));
        }

        return $suffix ? $slice . '...' : $slice;
    }
}

if ( ! function_exists('list_sort_by')) {
    /**
     * 对查询结果集进行排序
     * @access public
     *
     * @param array  $list   查询结果
     * @param string $field  排序的字段名
     * @param array  $sortby 排序类型
     *                       asc正向排序 desc逆向排序 nat自然排序
     *
     * @return array
     * @author 永 | chuanshuo_yongyuan@163.com
     */
    function list_sort_by($list, $field, $sortby = 'asc')
    {
        if (is_array($list)) {
            $refer = $resultSet = array();
            foreach ($list as $i => $data)
                $refer[ $i ] = &$data[ $field ];
            switch ($sortby) {
                case 'asc': // 正向排序
                    asort($refer);
                    break;
                case 'desc':// 逆向排序
                    arsort($refer);
                    break;
                case 'nat': // 自然排序
                    natcasesort($refer);
                    break;
            }
            foreach ($refer as $key => $val)
                $resultSet[] = &$list[ $key ];

            return $resultSet;
        }

        return false;
    }
}
if ( ! function_exists('list_to_tree')) {
    /**
     * 把返回的数据集转换成Tree
     *
     * @param array  $list  要转换的数据集
     * @param string $pid   parent标记字段
     * @param string $level level标记字段
     *
     * @return array
     * @author: 永 | <chuanshuo_yongyuan@163.com>
     */
    function list_to_tree($list, $pk = 'id', $pid = 'pid', $child = 'children', $root = 0)
    {
        // 创建Tree
        $tree = array();
        if (is_array($list)) {
            // 创建基于主键的数组引用
            $refer = array();
            foreach ($list as $key => $data) {
                $refer[ $data[ $pk ] ] =& $list[ $key ];
            }
            foreach ($list as $key => $data) {
                // 判断是否存在parent
                $parentId = $data[ $pid ];
                if ($root == $parentId) {
                    $tree[] =& $list[ $key ];
                } else {
                    if (isset($refer[ $parentId ])) {
                        $parent =& $refer[ $parentId ];
                        $parent[ $child ][] =& $list[ $key ];
                    }
                }
            }
        }

        return $tree;
    }
}
if ( ! function_exists('tree_to_list')) {
    /**
     * 将list_to_tree的树还原成列表
     *
     * @param array  $tree  原来的树
     * @param string $child 孩子节点的键
     * @param string $order 排序显示的键，一般是主键 升序排列
     * @param array  $list  过渡用的中间数组，
     *
     * @return array        返回排过序的列表数组
     * @author yangweijie <yangweijiester@gmail.com>
     */
    function tree_to_list($tree, $child = '_child', $order = 'id', &$list = array())
    {
        if (is_array($tree)) {
            foreach ($tree as $key => $value) {
                $reffer = $value;
                if (isset($reffer[ $child ])) {
                    unset($reffer[ $child ]);
                    tree_to_list($value[ $child ], $child, $order, $list);
                }
                $list[] = $reffer;
            }
            $list = list_sort_by($list, $order, $sortby = 'asc');
        }

        return $list;
    }
}
if ( ! function_exists('format_bytes')) {
    /**
     * 格式化字节大小
     *
     * @param number $size      字节数
     * @param string $delimiter 数字和单位分隔符
     *
     * @return string            格式化后的带单位的大小
     * @author: 永 | <chuanshuo_yongyuan@163.com>
     */
    function format_bytes($size, $delimiter = '')
    {
        $units = array('B', 'KB', 'MB', 'GB', 'TB', 'PB');
        for ($i = 0; $size >= 1024 && $i < 5; $i++) $size /= 1024;

        return round($size, 2) . $delimiter . $units[ $i ];
    }
}
if ( ! function_exists('format_number')) {
    /**
     * 格式化数字大小
     *
     * @param        $number
     * @param string $delimiter
     *
     * @return string
     * @author: 永 | <chuanshuo_yongyuan@163.com>
     */
    function format_number($number, $delimiter = '')
    {
        $units = array('', 'k', 'w');
        for ($i = 0; $number >= 1000 && $i < count($units) - 1; $i++) $number /= 1000;

        return round($number, 2) . $delimiter . $units[ $i ];
    }
}
if ( ! function_exists('time_format')) {
    /**
     * 时间戳格式化
     *
     * @param int $time
     *
     * @return string 完整的时间显示
     * @author huajie <banhuajie@163.com>
     */
    function time_format($time = null, $format = 'Y-m-d H:i:s')
    {
        $time = $time === null ? time() : intval($time);

        return $time ? date($format, $time) : '无';
    }
}
if ( ! function_exists('create_dir_or_files')) {
    /**
     * 基于数组创建目录和文件
     *
     * @param $files
     *
     * @author: 永 | <chuanshuo_yongyuan@163.com>
     */
    function create_dir_or_files($files)
    {
        foreach ($files as $key => $value) {
            if (substr($value, -1) == '/') {
                mkdir($value);
            } else {
                @file_put_contents($value, '');
            }
        }
    }
}
if ( ! function_exists('array_under_reset')) {
    /**
     * 返回以原数组某个值为下标的新数据
     *
     * @param array  $array
     * @param string $key
     * @param int    $type 1一维数组2二维数组
     *
     * @return array
     * @author 永 | chuanshuo_yongyuan@163.com
     */
    function array_under_reset($array, $key, $type = 1)
    {
        if (is_array($array)) {
            $tmp = array();
            foreach ($array as $v) {
                if ($type === 1) {
                    $tmp[ $v[ $key ] ] = $v;
                } elseif ($type === 2) {
                    $tmp[ $v[ $key ] ][] = $v;
                }
            }

            return $tmp;
        } else {
            return $array;
        }
    }
}
if ( ! function_exists('unique_array2d')) {
    /**
     * 二维数组去重
     *
     * @param      $array2D  需要去重的二维数组
     * @param bool $stkeep   是否保留一级数组的键名
     * @param bool $ndformat 是否保留二级数组的键名
     *
     * @return mixed
     * @author: 永 | <chuanshuo_yongyuan@163.com>
     */
    function unique_array2d($array2D, $stkeep = false, $ndformat = true)
    {
        // 判断是否保留一级数组键 (一级数组键可以为非数字)
        if ($stkeep) $stArr = array_keys($array2D);
        // 判断是否保留二级数组键 (所有二级数组键必须相同)
        if ($ndformat) $ndArr = array_keys(end($array2D));
        //降维,也可以用implode,将一维数组转换为用逗号连接的字符串
        foreach ($array2D as $v) {
            $v = join(",", $v);
            $temp[] = $v;
        }
        // 去掉重复的字符串,也就是重复的一维数组
        $temp = array_unique($temp);
        // 再将拆开的数组重新组装
        foreach ($temp as $k => $v) {
            if ($stkeep) $k = $stArr[ $k ];
            if ($ndformat) {
                $tempArr = explode(",", $v);
                foreach ($tempArr as $ndkey => $ndval) $output[ $k ][ $ndArr[ $ndkey ] ] = $ndval;
            } else $output[ $k ] = explode(",", $v);
        }

        return $output;
    }
}
if ( ! function_exists('priceFormat')) {
    /**
     * 价格格式化
     *
     * @param int $price
     *
     * @return string    $price_format
     */
    function priceFormat($price)
    {
        $price_format = number_format($price, 2, '.', '');

        return $price_format;
    }
}
if ( ! function_exists('getSystemYearArr')) {
    /**
     * 获得系统年份数组
     * @return array
     * @author: 永 | <chuanshuo_yongyuan@163.com>
     */
    function getSystemYearArr()
    {
        $year_arr = array('2019' => '2019', '2020' => '2020', '2021' => '2021', '2022' => '2022', '2023' => '2023', '2024' => '2024', '2025' => '2025', '2026' => '2026', '2027' => '2027', '2028' => '2028');

        return $year_arr;
    }
}
if ( ! function_exists('getSystemMonthArr')) {
    /**
     * 获得系统月份数组
     * @return array
     * @author: 永 | <chuanshuo_yongyuan@163.com>
     */
    function getSystemMonthArr()
    {
        $month_arr = array('1' => '01', '2' => '02', '3' => '03', '4' => '04', '5' => '05', '6' => '06', '7' => '07', '8' => '08', '9' => '09', '10' => '10', '11' => '11', '12' => '12');

        return $month_arr;
    }
}
if ( ! function_exists('getSystemWeekArr')) {
    /**
     * 获得系统周数组
     * @return array
     * @author: 永 | <chuanshuo_yongyuan@163.com>
     */
    function getSystemWeekArr()
    {
        $week_arr = array('1' => '周一', '2' => '周二', '3' => '周三', '4' => '周四', '5' => '周五', '6' => '周六', '7' => '周日');

        return $week_arr;
    }
}
if ( ! function_exists('getMonthLastDay')) {
    /**
     * 获取某月的最后一天
     *
     * @param $year  需要获取的年
     * @param $month 需要获取年的月
     *
     * @return false|int
     * @author: 永 | <chuanshuo_yongyuan@163.com>
     */
    function getMonthLastDay($year, $month)
    {
        $t = mktime(0, 0, 0, $month + 1, 1, $year);
        $t = $t - 60 * 60 * 24;

        return $t;
    }
}
if ( ! function_exists('getMonthWeekArr')) {
    /**
     * 获得系统某月的周数组，第一周不足的需要补足
     *
     * @param $current_year  需要获取的年
     * @param $current_month 需要获取的月份
     *
     * @return array
     * @author: 永 | <chuanshuo_yongyuan@163.com>
     */
    function getMonthWeekArr($current_year, $current_month)
    {
        //该月第一天
        $firstday = strtotime($current_year . '-' . $current_month . '-01');
        //该月的第一周有几天
        $firstweekday = (7 - date('N', $firstday) + 1);
        //计算该月第一个周一的时间
        $starttime = $firstday - 3600 * 24 * (7 - $firstweekday);
        //该月的最后一天
        $lastday = strtotime($current_year . '-' . $current_month . '-01' . " +1 month -1 day");
        //该月的最后一周有几天
        $lastweekday = date('N', $lastday);
        //该月的最后一个周末的时间
        $endtime = $lastday - 3600 * 24 * $lastweekday;
        $step = 3600 * 24 * 7;//步长值
        $week_arr = array();
        for ($i = $starttime; $i < $endtime; $i = $i + 3600 * 24 * 7) {
            $week_arr[] = array('key' => date('Y-m-d', $i) . '|' . date('Y-m-d', $i + 3600 * 24 * 6), 'val' => date('Y-m-d', $i) . '~' . date('Y-m-d', $i + 3600 * 24 * 6));
        }

        return $week_arr;
    }
}
if ( ! function_exists('getWeekSdateAndEdate')) {
    /**
     * 获取本周的开始时间和结束时间
     *
     * @param $current_time
     *
     * @return mixed
     * @author: 永 | <chuanshuo_yongyuan@163.com>
     */
    function getWeekSdateAndEdate($current_time)
    {
        $current_time = strtotime(date('Y-m-d', $current_time));
        $return_arr['sdate'] = date('Y-m-d', $current_time - 86400 * (date('N', $current_time) - 1));
        $return_arr['edate'] = date('Y-m-d', $current_time + 86400 * (7 - date('N', $current_time)));

        return $return_arr;
    }
}
if ( ! function_exists('in_array_case')) {
    /**
     * 不区分大小写的in_array实现
     *
     * @param $value
     * @param $array
     *
     * @return bool
     * @author: 永 | <chuanshuo_yongyuan@163.com>
     */
    function in_array_case($value, $array)
    {
        return in_array(strtolower($value), array_map('strtolower', $array));
    }
}

if ( ! function_exists('get_client_ip')) {

    /**
     * 获取用户的 iP 地址,通常情况下,使用 request 对象就可以
     * 但是,当使用了 docker 以后,使用 request 对象拿到的 IP 地址是docker 的转发地址
     * 所处出现了现在的方法
     * @return array|false|string
     * @author: 永 | <chuanshuo_yongyuan@163.com>
     */
    function get_client_ip()
    {
        $ip = '0.0.0.0';
        if (getenv("HTTP_CLIENT_IP")) {
            $ip = getenv("HTTP_CLIENT_IP");
        } else {
            if (getenv("HTTP_X_FORWARDED_FOR")) {
                $ip = getenv("HTTP_X_FORWARDED_FOR");
            } else {
                if (getenv("REMOTE_ADDR")) {
                    $ip = getenv("REMOTE_ADDR");
                }
            }
        }

        return $ip;
    }
}

/**
 * 生成uuid,满足目前系统需求了
 *
 * return string
 */
if ( ! function_exists('create_uuid')) {
    function create_uuid($prefix = "")
    {    //可以指定前缀
        $str = md5(uniqid(mt_rand(), true));
        $uuid = substr($str, 0, 8) . '-';
        $uuid .= substr($str, 8, 4) . '-';
        $uuid .= substr($str, 12, 4) . '-';
        $uuid .= substr($str, 16, 4) . '-';
        $uuid .= substr($str, 20, 12);

        return $prefix . $uuid;
    }
}


if ( ! function_exists('str_rand')) {
    /**
     * @title  生成随机字符串
     *
     * @param int    $length 生成随机字符串的长度
     * @param string $char   组成随机字符串的字符串
     *
     * @return bool|string
     * @author he
     * @date   2020-04-08 14:12
     */
    function str_rand($length = 32, $char = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ')
    {
        if ( ! is_int($length) || $length < 0) {
            return false;
        }

        $string = '';
        for ($i = $length; $i > 0; $i--) {
            $string .= $char[ mt_rand(0, strlen($char) - 1) ];
        }

        return $string;
    }
}

if ( ! function_exists('_getExchangeCode')) {
    /**
     * 获取兑换码
     */
    function _getExchangeCode()
    {
        $code = substr(strtotime(date("Y-m-d", time())), 0, 2)
            . substr(strrev(microtime()), 2, 4)
            . substr(mt_rand(), 0, 2)
            . substr(rand(), 0, 4);

        return $code;
    }
}

function create_random($length = 10)
{
    $code = '';
    $codeSet = '2345678abcdefhijkmnpqrstuvwxyzABCDEFGHJKLMNPQRTUVWXY';
    for ($i = 0; $i < $length; $i++) {
        $code .= $codeSet[ mt_rand(0, strlen($codeSet) - 1) ];
    }

    return $code;
}

if ( ! function_exists('array_filtration')) {
    /**
     * @title  数组字段过滤
     *
     * @param array $data
     * @param       $filtration
     * @param bool  $just true 正向过滤，false 反向过滤
     *
     * @return array
     * @author he
     * @date   2020-04-27 11:00
     */
    function array_filtration(array $data, $filtration, $just = true)
    {
        if (is_string($filtration)) {
            $filtration = explode(',', $filtration);
            $filtration = array_map(function ($item) {
                return trim($item);
            }, $filtration);
        }
        foreach ($data as $key => $temp) {
            if ($just) {
                if ( ! in_array($key, $filtration)) unset($data[ $key ]);
            } else {
                if (in_array($key, $filtration)) unset($data[ $key ]);
            }
        }

        return $data;
    }
}

if ( ! function_exists('getCols')) {
    /**
     * @title  获取一维数组数组下标为$col的值
     *
     * @param $arr
     * @param $col
     *
     * @return array
     * @author he
     * @date   2020-04-29 19:22
     */
    function getCols($arr, $col)
    {
        $ret = array();
        foreach ($arr as $row) {
            if (isset ($row [ $col ])) {
                $ret [] = $row [ $col ];
            }
        }

        return $ret;
    }
}

if ( ! function_exists('generateUnique')) {
    /**
     * @title  生成唯一标识
     * @return string
     * @author he
     * @date   2020-05-01 16:57
     */
    function generateUnique()
    {
        return date('Ymd') . str_pad(mt_rand(1, 99999), 5, '0', STR_PAD_LEFT) . substr(implode(null, array_map('ord', str_split(substr(uniqid(), 7, 13), 1))), 0, 8);//202004011843010248539
    }
}

if ( ! function_exists('half_replace')) {
    /**
     * @title  字符串中间隐藏
     * @return string
     * @author he
     * @date   2020-05-07 17:16
     */
    function half_replace($str)
    {
        $len = mb_strlen($str, 'utf-8');
        if ($len >= 6) {
            $str1 = mb_substr($str, 0, 2, 'utf-8');
            $str2 = mb_substr($str, $len - 2, 2, 'utf-8');
        } else {
            $str1 = mb_substr($str, 0, 1, 'utf-8');
            $str2 = mb_substr($str, $len - 1, 1, 'utf-8');
        }

        return $str1 . str_repeat('*', round($len / 2)) . $str2;
    }
}

use Symfony\Component\VarDumper\VarDumper;

if ( ! function_exists('dump')) {
    /**
     * @author Nicolas Grekas <p@tchwork.com>
     */
    function dump($var, ...$moreVars)
    {
        VarDumper::dump($var);

        foreach ($moreVars as $v) {
            VarDumper::dump($v);
        }

        if (1 < func_num_args()) {
            return func_get_args();
        }

        return $var;
    }
}

if ( ! function_exists('dd')) {
    function dd(...$vars)
    {
        foreach ($vars as $v) {
            VarDumper::dump($v);
        }

        exit(1);
    }
}


