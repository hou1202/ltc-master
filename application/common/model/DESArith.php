<?php
/**
 * Created by PhpStorm.
 * User: zhuziqiang
 * Date: 2016/12/21
 * Time: 10:01
 */

namespace app\common\model;

class DESArith {


    /*
     *最长8位 
     */
    private static $_key = 'a3ed725a';



    public static function encrypt($str)
    {
        $block = mcrypt_get_block_size('des', 'ecb');
        $pad = $block - (strlen($str) % $block);
        $str .= str_repeat(chr($pad), $pad);
        $iv = mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_DES, MCRYPT_MODE_ECB), MCRYPT_RAND);
        $str_encrypt = mcrypt_encrypt(MCRYPT_DES, static::$_key, $str, MCRYPT_MODE_ECB, $iv);
        return base64_encode($str_encrypt);
    }

    public static function decrypt($str)
    {
        $data = base64_decode($str);
        $str = mcrypt_decrypt(MCRYPT_DES, static::$_key, $data, MCRYPT_MODE_ECB);
        $block = mcrypt_get_block_size('des', 'ecb');
        $pad = ord($str[($len = strlen($str)) - 1]);
        return substr($str, 0, strlen($str) - $pad);
    }


}