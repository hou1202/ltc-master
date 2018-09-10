<?php
/**
 * Created by PhpStorm.
 * User: zhuziqiang
 * Date: 2016/12/1
 * Time: 9:29
 */
namespace app\api\model;


use think\Db;

class Message {

    public static $tableName = 'p_message';

    public function getNotReadCount($userId){
        $msgCount = Db::table(static::$tableName)->where('is_del=0')->count();
        $messageLog = new MessageLog();
        $readCount = $messageLog->getReadCount($userId);
        $count = $msgCount-$readCount;
        return $count>0 ? $count : 0;
    }

    public function getInfo($id){
        return Db::table(static::$tableName)->field('id,title,c_time,content')->where('id='.$id.' AND is_del=0')->find();
    }

    public function lists($where, $page){
        return Db::table(static::$tableName)->alias('m')->field('m.id,m.title,m.c_time,left(m.content,20) as sub_content,ifnull(l.id, 0) as is_read')
            ->join('p_message_log l', 'l.msg_id=m.id', 'LEFT')
            ->where($where)
            ->limit($page*10, 10)
            ->order('m.id DESC')
            ->select();
    }

}
