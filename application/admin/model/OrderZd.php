<?php
// +----------------------------------------------------------------------
// | KingTP
// +----------------------------------------------------------------------
// | 2018/1/13 14:29
// +----------------------------------------------------------------------
// | **基于ThinkPHP 5.0.14 + LayUI2.2.5封装**
// +----------------------------------------------------------------------
// | Author: 晓晓攻城师 <邮箱：1228676735@qq.com><博客：http://blog.csdn.net/qq_26245325>
// +----------------------------------------------------------------------

namespace app\admin\model;


use app\api\model\Push;
use app\common\model\BaseAdminModel;
use think\Db;

class OrderZd extends BaseAdminModel
{

    const STATUS_NOT_PAY = 0;
    const STATUS_PAY = 1;


    /**
     * @param $where string|array
     * @param $page integer
     * @param $limit integer
     * @param $order string
     * @return array
     */
    function index($where, $page, $limit, $order)
    {
        return $this->alias('z')->field(['z.zd_id','z.zd_month','z.order_count','z.total_price','z.remark','z.status','u.real_name','h.hospital_name','z.c_time'])
            ->join('p_user u', 'u.user_id=z.user_id')
            ->join('p_hospital h', 'h.hospital_id=u.hospital_id')
            ->where($where)->limit(($page-1)*$limit, $limit)
            ->order($order)->select();
    }

    /**
     * @param $where string|array
     * @return integer
     */
    function totalCount($where)
    {
        return $this->alias('z') ->join('p_user u', 'u.user_id=z.user_id')->where($where)->count();
    }

    /**
     * @param $data array
     * @return boolean
     */
    function add($data)
    {
        //$this->save($data);
        return true;
    }

    /**
     * @param $data array
     * @return boolean
     */
    function edit($data)
    {
        if($data['status'] == 1){
            $data['e_time'] = date('Y-m-d H:i:s');
            Order::update(['status'=>Order::STATUS_PAY], ['zd_id'=>$this->zd_id]);
            //判断是否添加消息
            $msg = date('m', strtotime($this->zd_month)).'月份收入￥'.$this->total_price;
            Push::pushZd($this->user_id, $this->zd_id, $msg);
        }else{
            Order::update(['status'=>Order::STATUS_DIAGNOSE], ['zd_id'=>$this->zd_id]);
        }
        $this->save($data);
        return true;
    }

    /**
     * @return boolean
     */
    function del()
    {

        return true;
    }

    /**
     * @return string
     */
    function getTitle()
    {
        return '账单结算';
    }

    public static function getOrderZdPrice($where){
        $totalPrice = Db::table(static::getTable())->alias('z')->join('p_user u', 'u.user_id=z.user_id')->where($where)->sum('z.total_price');
        return $totalPrice==null? 0 : $totalPrice;
    }


    /**
     * 获取用户的账单
     * @param $userId
     * @return array
     */
    public static function getUserTotalPrice($userId){
        $totalPrice = Db::table(static::getTable())->where('user_id='.$userId.' AND status='.OrderZd::STATUS_PAY)->sum('total_price');
        return (string)(empty($totalPrice) ? 0.00 : number_format($totalPrice, 2));
    }

    /**
     * 获取用户的账单
     * @param $userId
     * @return array
     */
    public static function getUserBills($userId){
        $bills = Db::table(static::getTable())->field('zd_month,total_price,order_count,e_time')->where('user_id='.$userId.' AND status='.OrderZd::STATUS_PAY)->order('zd_id desc')->limit(12)->select();
        $formats = [];
        foreach($bills as $v){
            $formats[] = ['title'=>date('m',strtotime($v['e_time'])).'月账单', 'date'=>substr($v['e_time'], 0, 10), 'content'=>'累计诊断'.$v['order_count'].'次，共'.$v['total_price'].'元'];
        }
        return $formats;
    }

    /**
     * 获取用户的账单
     * @param $userId
     * @return array
     */
    public static function getIndexUserBills($userId, $page){
        $bills = Db::table(static::getTable())
            ->field('zd_month,total_price,DATE_FORMAT(e_time,\'%Y-%m-%d\') as e_time')
            ->where('user_id='.$userId.' AND status='.OrderZd::STATUS_PAY)
            ->order('zd_id desc')
            ->limit(($page-1)*6, 6)
            ->select();
        return $bills;
    }

    /**
     * 获取用户的账单总数量
     * @param $userId
     * @return int
     */
    public static function getIndexCount($userId){
        return (int)Db::table(static::getTable())->where('user_id='.$userId.' AND status='.OrderZd::STATUS_PAY)->count();
    }

}