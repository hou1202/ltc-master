<?php
/**
 * Project: 基础库-验证数据类型
 * User: Zhu Ziqiang
 * Date: 2017/10/12
 * Time: 15:53
 */

namespace app\common\model;


class ValidateModel
{
    /**
     * 检查身份证号码
     * @param string $value
     * @return bool
     */
    public static function checkCardNu($value)
    {
        $set = [7, 9, 10, 5, 8, 4, 2, 1, 6, 3, 7, 9, 10, 5, 8, 4, 2];
        $ver = ['1', '0', 'x', '9', '8', '7', '6', '5', '4', '3', '2'];
        $arr = str_split($value);
        $sum = 0;
        try {
            for ($i = 0; $i < 17; $i++) {
                if (!is_numeric($arr[$i])) {
                    return false;
                }
                $sum += $arr[$i] * $set[$i];
            }
            $mod = $sum % 11;
            return strcasecmp($ver[$mod], $arr[17]) == 0;
        }catch(\Exception $e){
            return false;
        }
    }

    /**
     * 检查手机号
     * @param string $value
     * @return bool
     */
    public static function checkMobile($value){
        return (boolean)preg_match('/^1[23465789]{1}\d{9}$/', $value);
    }

    /**
     * 检查车牌号码
     * @param string $value
     * @return bool
     */
    public static function checkPlateNumber($value)
    {
        return (boolean)preg_match('/^[\x{4e00}-\x{9fa5}]{1}[A-Z]{1}[A-Z0-9]{5}$/u', $value);
    }

    /**
     * 检查银行卡号是否正确,Luhm算法
     * @param $card_number
     * @return bool
     */
    public static function checkBankCard($card_number){
        $arr_no = str_split($card_number);
        $last_n = $arr_no[count($arr_no)-1];
        krsort($arr_no);
        $i = 1;
        $total = 0;
        foreach ($arr_no as $n){
            if($i%2==0){
                $ix = $n*2;
                if($ix>=10){
                    $nx = 1 + ($ix % 10);
                    $total += $nx;
                }else{
                    $total += $ix;
                }
            }else{
                $total += $n;
            }
            $i++;
        }
        $total -= $last_n;
        $x = 10 - ($total % 10);
        if($x == $last_n){
            return true;
        }else{
            return false;
        }
    }




}