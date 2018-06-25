<?php
/**
 * Created by VeryStar.
 * Author: hsx
 * Create: 2018/6/21 11:33
 * Editor: created by PhpStorm.
 */
if (!function_exists('xml_to_array')) {
    //xml转换为array
    function xml_to_array($xml)
    {
        return json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
    }
}

if (!function_exists('return_ip')) {
    function return_ip()
    {
        $ip = '-1';
        if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip_a = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
            for ($i = 0; $i < count($ip_a); $i++) { //
                $tmp = trim($ip_a[$i]);
                if ($tmp == 'unknown' || $tmp == '127.0.0.1' || strncmp($tmp, '10.', 3) == 0 || strncmp(
                        $tmp, '172',
                        3
                    ) == 0 || strncmp($tmp, '192', 3) == 0) {
                    continue;
                }
                $ip = $tmp;
                break;
            }
        } elseif (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = trim($_SERVER['HTTP_CLIENT_IP']);
        } elseif (!empty($_SERVER['REMOTE_ADDR'])) {
            $ip = trim($_SERVER['REMOTE_ADDR']);
        } else {
            $ip = '-1';
        }

        return $ip;
    }
}

if (!function_exists('is_cli')) {
    function is_cli()
    {
        return PHP_SAPI == 'cli' && empty($_SERVER['REMOTE_ADDR']);
    }
}

if (!function_exists('msubstr')) {
    /**
     * 字符串截取，支持中文和其他编码
     *
     * @param string $str     需要转换的字符串
     * @param int    $start   开始位置
     * @param int    $length  截取长度
     * @param string $charset 编码格式
     * @param string $suffix  截断显示字符
     *
     * @return string
     */
    function msubstr($str, $start, $length, $charset = 'utf-8', $suffix = '...')
    {
        if (function_exists('mb_substr')) {
            $slice = mb_substr($str, $start, $length, $charset);
        } elseif (function_exists('iconv_substr')) {
            $slice = iconv_substr($str, $start, $length, $charset);
        } else {
            $re['utf-8'] = "/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|[\xe0-\xef][\x80-\xbf]{2}|[\xf0-\xff][\x80-\xbf]{3}/";
            $re['gb2312'] = "/[\x01-\x7f]|[\xb0-\xf7][\xa0-\xfe]/";
            $re['gbk'] = "/[\x01-\x7f]|[\x81-\xfe][\x40-\xfe]/";
            $re['big5'] = "/[\x01-\x7f]|[\x81-\xfe]([\x40-\x7e]|\xa1-\xfe])/";
            preg_match_all($re[$charset], $str, $match);
            $slice = implode('', array_slice($match[0], $start, $length));
        }

        return $slice.$suffix;
    }
}

if (!function_exists('trim_space')) {

    /**
     * 去除中英文空格
     *
     * @param $s
     *
     * @return string
     */
    function trim_space($s)
    {
        $s = mb_ereg_replace('^(　| )+', '', $s);
        $s = mb_ereg_replace('(　| )+$', '', $s);

        return $s;
    }
}

if (!function_exists('rand_sample')) {

    /**
     * 随机采样.
     *
     * @param $str
     * @param $prob
     *
     * @return string
     */
    function rand_sample($str, $prob = 100)
    {
        $prob = $prob < 10 ? 10 : $prob;
        $rt = mt_rand(1, $prob);

        return $rt == 8 ? $str : null;
    }
}

/**
 * @param $data
 * @param $encodeing
 */
function _iconv(&$data, $encodeing)
{
    $data = mb_convert_encoding($data, $encodeing[1], $encodeing[0]);
}

function gbk2utf8($data)
{
    if (is_array($data)) {
        array_walk_recursive($data, '_iconv', ['gbk', 'utf-8']);
    } elseif (is_object($data)) {
        array_walk_recursive(get_object_vars($data), '_iconv', ['utf-8', 'gbk']);
    } else {
        $data = mb_convert_encoding($data, 'utf-8', 'gbk');
    }

    return $data;
}

function utf8togbk($data)
{
    if (is_array($data)) {
        array_walk_recursive($data, '_iconv', ['utf-8', 'gbk']);
    } elseif (is_object($data)) {
        array_walk_recursive(get_object_vars($data), '_iconv', ['utf-8', 'gbk']);
    } else {
        $data = mb_convert_encoding($data, 'gbk', 'utf-8');
    }

    return $data;
}

if (!function_exists('rand_str')) {

    /**
     * 生成字符串.
     *
     * @param int $len
     *
     * @return string
     */
    function rand_str($len = 5)
    {
        return substr(str_shuffle('1234567890abcdefghijklmnopqrstuvwzxyABCDEFGHIJKLMNOPQRSTUVWZXY'), 0, $len);
    }
}

if (!function_exists('base32_encode')) {
    function base32_encode($input)
    {
        $base32_alphabet = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ234567';
        $output = '';
        //$position         = 0;
        $stored_data = 0;
        $stored_bit_count = 0;
        $index = 0;

        while ($index < strlen($input)) {
            $stored_data <<= 8;
            $stored_data += ord($input[$index]);
            $stored_bit_count += 8;
            $index += 1;

            //take as much data as possible out of storedData
            while ($stored_bit_count >= 5) {
                $stored_bit_count -= 5;
                $output .= $base32_alphabet[$stored_data >> $stored_bit_count];
                $stored_data &= ((1 << $stored_bit_count) - 1);
            }
        } //while

        //deal with leftover data
        if ($stored_bit_count > 0) {
            $stored_data <<= (5 - $stored_bit_count);
            $output .= $base32_alphabet[$stored_data];
        }

        return $output;
    }
}

if (!function_exists('base32_decode')) {

    /**
     * @param $input
     *
     * @return string
     *
     * @author 蔡旭东 mailto:fifsky@dev.ppstream.com
     */
    function base32_decode($input)
    {
        if (empty($input)) {
            return $input;
        }

        static $asc = [];
        $output = '';
        $v = 0;
        $vbits = 0;
        $i = 0;
        $input = strtolower($input);
        $j = strlen($input);
        while ($i < $j) {
            if (!isset($asc[$input[$i]])) {
                $asc[$input[$i]] = ord($input[$i]);
            }

            $v <<= 5;
            if ($input[$i] >= 'a' && $input[$i] <= 'z') {
                $v += ($asc[$input[$i]] - 97);
            } elseif ($input[$i] >= '2' && $input[$i] <= '7') {
                $v += (24 + $input[$i]);
            } else {
                exit(1);
            }
            $i++;

            $vbits += 5;
            while ($vbits >= 8) {
                $vbits -= 8;
                $output .= chr($v >> $vbits);
                $v &= ((1 << $vbits) - 1);
            }
        }

        return $output;
    }
}

if (!function_exists('textarea_to_html')) {
    function textarea_to_html($str)
    {
        $str = str_replace(chr(13), '<br>', $str);
        $str = str_replace(chr(9), '&nbsp;&nbsp;', $str);
        $str = str_replace(chr(32), '&nbsp;', $str);

        return $str;
    }
}

if (!function_exists('html_to_textarea')) {
    function html_to_textarea($str)
    {
        $str = str_replace('<br>', chr(13), $str);
        $str = str_replace('&nbsp;', chr(32), $str);

        return $str;
    }
}

if (!function_exists('str_encrypt')) {

//加密解密
    function str_encrypt($string, $skey = '%f1f5kyL@<eYu9n$')
    {
        $code = '';
        $key = substr(md5($skey), 8, 18);
        $keylen = strlen($key);
        $strlen = strlen($string);
        for ($i = 0; $i < $strlen; $i++) {
            $k = $i % $keylen;
            $code .= $string[$i] ^ $key[$k];
        }

        return str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($code));
    }
}

if (!function_exists('str_decrypt')) {
    function str_decrypt($string, $skey = '%f1f5kyL@<eYu9n$')
    {
        $string = base64_decode(str_replace(['-', '_'], ['+', '/'], $string));
        $code = '';
        $key = substr(md5($skey), 8, 18);
        $keylen = strlen($key);
        $strlen = strlen($string);
        for ($i = 0; $i < $strlen; $i++) {
            $k = $i % $keylen;
            $code .= $string[$i] ^ $key[$k];
        }

        return $code;
    }
}

if (!function_exists('ifset')) {
    function ifset($array, $key, $default = null)
    {
        return isset($array[$key]) ? $array[$key] : $default;
    }
}

if (!function_exists('required_params')) {

    /**
     * 检测参数是否有值，如果有一个参数没有值则返回false.
     *
     * @return bool
     */
    function required_params()
    {
        $params = func_get_args();
        foreach ($params as $value) {
            if (is_array($value)) {
                if (empty($value)) {
                    return false;
                }
            } else {
                if ($value === null || strlen(trim($value)) == 0) {
                    return false;
                }
            }
        }

        return true;
    }
}

if (!function_exists('filter_empty')) {

    /**
     * 过滤数组的空.
     *
     * @param $arr
     *
     * @return array
     */
    function filter_empty($arr)
    {
        return array_filter(
            $arr, function ($val) {
                if (is_bool($val) || is_array($val)) {
                    //此处之所以做出这样的判断因为trim对false 和 true返回值完全不同
                    return true;
                }

                return $val !== '' && $val !== null && strlen(trim($val)) > 0;
            }
        );
    }
}

if (!function_exists('restore_empty')) {

    /**
     * 还原过滤的空字段.
     *
     * @param $data
     * @param $filed
     *
     * @return array
     */
    function restore_empty($data, $filed)
    {
        return array_merge(array_fill_keys($filed, ''), $data);
    }
}

if (!function_exists('filter_field')) {

    /**
     * 根据固定key过滤数组.
     *
     * @param $data
     * @param $field
     *
     * @return array
     */
    function filter_field($data, $field)
    {
        return array_intersect_key($data, array_fill_keys($field, ''));
    }
}

if (!function_exists('emptystr_tonull')) {

    /**
     * 空字符串转换为NULL，不包含对空格的处理.
     *
     * @param $arr
     *
     * @return array
     */
    function emptystr_tonull($arr)
    {
        return array_map(
            function ($val) {
                if ($val === '') {
                    $val = null;
                }

                return $val;
            }, $arr
        );
    }
}

if (!function_exists('nullto_emptystr')) {

    /**
     * NULL换换为空字符串.
     *
     * @param $arr
     *
     * @return array
     */
    function nullto_emptystr($arr)
    {
        return array_map(
            function ($val) {
                if ($val === null) {
                    $val = '';
                }

                return $val;
            }, $arr
        );
    }
}

if (!function_exists('debug_start')) {
    function debug_start($s)
    {
        $GLOBALS[$s]['start_time'] = microtime(true);
        if (!isset($GLOBALS[$s]['start_total_time'])) {
            $GLOBALS[$s]['start_total_time'] = $GLOBALS[$s]['start_time'];
        }
        $GLOBALS[$s]['start_mem'] = memory_get_usage();
    }
}

function random_password()
{
    return substr(md5(uniqid()), 0, 8);
}

//注册用户加密密码
function my_password_hash($password)
{
    return substr(md5($password.'$DY*m72%82n({;9*#'), 3, 18);
}

function fee_format($fee)
{
    return number_format($fee / 100, 2);
}

function generateTree($items)
{
    $tree = [];
    $v = [];
    foreach ($items as $key => $value) {
        $v[$value['cate_id']] = $value;
    }
    foreach ($v as $item) {
        if (isset($v[$item['parent_cate_id']])) {
            $v[$item['parent_cate_id']]['son'][] = &$v[$item['cate_id']];
        } else {
            $tree[] = &$v[$item['cate_id']];
        }
    }

    return $tree;
}

if (!function_exists('array_only_keys')) {

    /****
     *
     * 判断指定数组是否为给定的keys集合
     *
     * @param $array
     * @param $keys
     *
     * @return bool
     */
    function array_only_keys($array, $keys)
    {
        $ret = false;

        $keys = (array) $keys;
        //判断条件，个数相同，并且每个KEY都存在
        if (is_array($array) && count($array) == count($keys)) {
            $ret = true;
            foreach ($keys as $key) {
                if (!isset($array[$key])) {
                    $ret = false;
                    break;
                }
            }
        }

        return $ret;
    }
}

if (!function_exists('trim_body')) {

    /****
     * * *
     * 去除空白字符(两端+中间换行，回车，空格)
     *
     * @param &$body
     *
     * @return mixed|string
     */
    function trim_body(&$body)
    {
        $body = trim($body);
        $body = str_replace("\r\n", '', $body);
        $body = str_replace("\n", '', $body);
        $body = str_replace(' ', '', $body);

        return $body;
    }
}

if (!function_exists('number_to_array')) {
    /**
     * 将数字通过2进制转为数组.
     *
     * @param int $number
     *
     * @return array
     */
    function number_to_array($number)
    {
        $array = [];
        $two_number = decbin($number);
        $length = strlen($two_number);
        for ($i = 0; $i < $length; $i++) {
            // 需要从左边第一位往右边数，看是否等于一
            if ($two_number[$length - $i - 1] == '1') {
                $array[] = pow(2, $i);
            }
        }

        return $array;
    }
}

if (!function_exists('array_multi_sort')) {
    /**
     * 对多维数组进行排序.
     *
     * @param array  $arrays     排序数组
     * @param string $sort_key   排序字段
     * @param int    $sort_order 排序方式
     * @param int    $sort_type  排序类型
     *
     * @return bool|array
     */
    function array_multi_sort(array &$arrays, $sort_key, $sort_order = SORT_ASC, $sort_type = SORT_NUMERIC)
    {
        $key_arrays = array_column($arrays, $sort_key);
        if (empty($key_arrays)) {
            return false;
        }

        array_multisort($key_arrays, $sort_order, $sort_type, $arrays);

        return true;
    }
}

if (!function_exists('time_to_string')) {
    function time_to_string($time)
    {
        $time = $time ?: time();
        $time = is_numeric($time) ? $time : strtotime($time);
        $difference = time() - $time;
        if ($difference <= 300) {
            return '刚刚';
        } elseif ($difference <= 3600) {
            return ceil($difference / 60).'分钟前';
        } elseif ($difference <= 86400) {
            return ceil($difference / 3600).'小时前';
        } elseif ($difference <= 2592000) {
            return ceil($difference / 86400).'天前';
        } else {
            return '';
        }
    }
}

if (!function_exists('parse_raw_http_request')) {
    /**
     * 格式化请求参数信息.
     *
     * @param string $str      请求参数内容
     * @param string $boundary 默认分割信息
     *
     * @return array
     */
    function parse_raw_http_request($str, $boundary = '----footfoodapplicationrequestnetwork')
    {
        // split content by boundary and get rid of last -- element
        $arr_blocks = preg_split("/-+$boundary/", $str);
        if (empty($arr_blocks) || count($arr_blocks) <= 1) {
            return [];
        }

        array_pop($arr_blocks);
        $array = [];
        // loop data blocks
        foreach ($arr_blocks as $block) {
            if (empty($block)) {
                continue;
            }

            // parse uploaded files
            if (strpos($block, 'application/octet-stream') !== false) {
                // match "name", then everything after "stream" (optional) except for prepending newlines
                preg_match("/name=\"([^\"]*)\".*stream[\n|\r]+([^\n\r].*)?$/s", $block, $matches);
            // parse all other fields
            } else {
                // match "name" and optional value in between newline sequences
                preg_match("/name=\"([^\"]*)\"[\n|\r]+([^\n\r].*)?[\r|\n]$/s", $block, $matches);
            }

            if (!empty($matches[1])) {
                $array[$matches[1]] = isset($matches[2]) ? trim($matches[2]) : null;
            }
        }

        return $array;
    }
}

if (!function_exists('handle_request_body')) {
    /**
     * 处理日志请求参数统一使用json字符串.
     *
     * @param string $string
     * @param int    $topic
     *
     * @return string
     */
    function handle_request_body($string, $topic)
    {
        if ($topic != 5) {
            return $string;
        } elseif (strpos($string, '{') !== false && strpos($string, '}') !== false) {
            return $string;
        } elseif (strpos($string, '----footfoodapplicationrequestnetwork') !== false) {
            $array = parse_raw_http_request($string);

            return $array ? json_encode($array, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) : $string;
        } elseif (strpos($string, '=') !== false) {
            parse_str($string, $array);

            return $array ? json_encode($array, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) : $string;
        } else {
            return $string;
        }
    }
}

if (!function_exists('great_number_format')) {
    function great_number_format($number, $precision = 2, $great_title = '万')
    {
        if ($number < 10000) {
            return $number;
        }

        return round($number / 10000, $precision).$great_title;
    }
}

if (!function_exists('get_sign')) {

    /**
     * 获取加密sign值
     *
     * @param $params
     * @param $app_secret
     *
     * @return string
     */
    function get_sign($params, $app_secret)
    {
        ksort($params, SORT_STRING);
        $arg = [];
        foreach ($params as $key => $val) {
            if (trim($key) === 'sign' || trim($val) === '') {
                continue;
            } else {
                $arg[] = $key.'='.$val;
            }
        }
        $arg = implode('&', $arg);

        return md5($arg.$app_secret);
    }
}

if (!function_exists('get_value')) {
    /**
     * 获取数组的值
     *
     * @param      $array
     * @param      $key
     * @param null $default
     *
     * @return mixed|null
     */
    function get_value($array, $key, $default = null)
    {
        if ($key instanceof \Closure) {
            return $key($array, $default);
        }

        if (is_array($key)) {
            $lastKey = array_pop($key);
            foreach ($key as $keyPart) {
                $array = get_value($array, $keyPart);
            }
            $key = $lastKey;
        }

        if (is_array($array) && (isset($array[$key]) || array_key_exists($key, $array))) {
            return $array[$key];
        }

        if (($pos = strrpos($key, '.')) !== false) {
            $array = get_value($array, substr($key, 0, $pos), $default);
            $key = substr($key, $pos + 1);
        }

        if (is_object($array)) {
            // this is expected to fail if the property does not exist, or __get() is not implemented
            // it is not reliably possible to check whether a property is accessible beforehand
            return $array->$key;
        } elseif (is_array($array)) {
            return (isset($array[$key]) || array_key_exists($key, $array)) ? $array[$key] : $default;
        }

        return $default;
    }
}

if (!function_exists('array_index')) {
    /**
     * 数组重新建立索引.
     *
     * @param       $array
     * @param       $key
     * @param array $groups
     *
     * @return array
     */
    function array_index($array, $key, $groups = [])
    {
        $result = [];
        $groups = (array) $groups;

        foreach ($array as $element) {
            $lastArray = &$result;

            foreach ($groups as $group) {
                $value = get_value($element, $group);
                if (!array_key_exists($value, $lastArray)) {
                    $lastArray[$value] = [];
                }
                $lastArray = &$lastArray[$value];
            }

            if ($key === null) {
                if (!empty($groups)) {
                    $lastArray[] = $element;
                }
            } else {
                $value = get_value($element, $key);
                if ($value !== null) {
                    if (is_float($value)) {
                        $value = str_replace(',', '.', (string) $value);
                    }
                    $lastArray[$value] = $element;
                }
            }
            unset($lastArray);
        }

        return $result;
    }
}

if (!function_exists('core_path')) {

    /**
     * 获取秘钥文件的绝对路径信息.
     *
     * @param string $file_name 路径从src目录下开始
     *
     * @return string 返回文件的绝对路径
     */
    function core_path($file_name)
    {
        $path = __DIR__.'/../'.$file_name;

        return $path;
    }
}
