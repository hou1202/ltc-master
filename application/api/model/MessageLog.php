<?php
/**
 * Created by PhpStorm.
 * User: zhuziqiang
 * Date: 2016/12/1
 * Time: 9:29
 */
namespace app\api\model;


use think\Db;

class MessageLog
{

    public static $tableName = 'p_message_log';

    public function getReadCount($userId)
    {
        return Db::table(static::$tableName)->where('user_id=' . $userId . ' AND type=0')->count();
    }

    public function addReadRecord($userId, $messageId)
    {
        if ($this->isRead($userId, $messageId) < 1) {
            return Db::table(static::$tableName)->insert(['user_id' => $userId, 'msg_id' => $messageId, 'type' => 0]);
        }
        return 0;
    }

    public function isRead($userId, $messageId)
    {
        return Db::table(static::$tableName)->where('user_id=' . $userId . ' AND msg_id=' . $messageId . ' AND type=0')->count();
    }

    public function delMessage($userId)
    {
        //$this->addReadRecord($userId, $messageId);
        $inserts = [];
        //查找所有没读的消息
        $readIds = $this->getReadMsgIds($userId);
        $notReadIds = $this->getOtherMessageIds($readIds);
        if (!empty($notReadIds)) {
            foreach ($notReadIds as $v) {
                $inserts[] = ['user_id' => $userId, 'msg_id' => $v, 'type' => 0];
            }
        }
        //查找所有没删除的消息
        $delIds = $this->getDelMsgIds($userId);
        $notDelIds = $this->getOtherMessageIds($delIds);
        if (!empty($notDelIds)) {
            foreach ($notDelIds as $v) {
                $inserts[] = ['user_id' => $userId, 'msg_id' => $v, 'type' => 1];
            }
        }
        if(!empty($inserts)){
            return Db::table(static::$tableName)->insertAll($inserts);
        }
        return 0;
    }

    public function isDel($userId, $messageId)
    {
        return Db::table(static::$tableName)->where('user_id=' . $userId . ' AND msg_id=' . $messageId . ' AND type=1')->count();
    }

    public function getDelMsgIds($userId)
    {
        return Db::table(static::$tableName)->where('user_id=' . $userId . ' AND type=1')->column('msg_id');
    }

    public function getReadMsgIds($userId)
    {
        return Db::table(static::$tableName)->where('user_id=' . $userId . ' AND type=0')->column('msg_id');
    }

    public function getOtherMessageIds($ids)
    {
        $where = [];
        if (!empty($ids)) {
            $where = ['id' => ['not in', $ids]];
        }
        return Db::table(Message::$tableName)->where($where)->column('id');
    }
}
