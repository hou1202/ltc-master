<?php
namespace app\api\model;

use think\Db;
use think\Exception;

class FailureToken
{

    public static $sTableName = 'zp_failure_token';

    public static function add($data){
        return Db::table(static::$sTableName)->insert($data);
    }

    public static function getUserIdByOldToken($token){
        return Db::table(static::$sTableName)->where('token like :token')->bind(['token'=>$token])->value('user_id', 0);
    }

}
