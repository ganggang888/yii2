<?php
/**
 * Created by PhpStorm.
 * User: ganggang
 * Date: 2017/5/10
 * Time: 13:11
 */
namespace app\models;
class Msg
{
    const SUCCESS = 0;
    const ERROR = 1;
    const PARAMS = 10;
    const NONE = 20;
    const POWER = 30;
    const MEMORY = 40;

    /*
     * 返回对应的信息
     */
    public static function errorMsg($error = 0)
    {
        $errorMsg = [
            self::SUCCESS => '成功',
            self::ERROR => '失败',
            self::PARAMS => '参数错误',
            self::NONE => '没有数据',
            self::POWER => '权限不足',
            self::MEMORY=> '一次最多核对1W条或json数据传输错误',
        ];
        return (isset($errorMsg[$error])) ? $errorMsg[$error] : '';
    }

    /*
     * json返回
     */
    public static function AjaxReturn($errorCode = 0,$errorMessage = 0,$list = '',$result = 0)
    {
        $msg = func_get_args();
        $arr = array(
            'errorCode'     => $errorCode,
            'errorMessage'  => $errorMessage,
            'list'          => $list,
            'result'        => $result,
        );
        return $arr;
    }

    /*
     * AES256加密
     */
    public static  function encrypt($key,$value)
    {
        $iv = substr($key,16);
        $padSize = 16 - (strlen ($value) % 16) ;
        $value = $value . str_repeat (chr ($padSize), $padSize) ;
        $output = mcrypt_encrypt (MCRYPT_RIJNDAEL_128, $key, $value, MCRYPT_MODE_CBC, $iv) ;
        return base64_encode ($output) ;
    }
}