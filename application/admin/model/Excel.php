<?php
/**
 * Project: huihuidao
 * User: Zhu Ziqiang
 * Date: 2017/9/14
 * Time: 15:21
 */

namespace app\admin\model;


use app\common\model\BaseAdminModel;

class Excel extends BaseAdminModel
{
    public static $sConfig = [
        /*'p_user' => [
            'name'=>'用户',
            'field'=>'user_id,nick_name,sex,country,city,ant_score,c_time',
            'alias'=>'ID,昵称,性别(1男2女),国家,所在城市,蚂蚁信用分,注册时间',
            'order'=>'user_id desc',
            'where'=>['searchDate'=>0,]
        ],
        'p_user_balance_log' => [
            'name'=>'用户资金流水',
            'field'=>'id,balance_sn,sign,money,balance,log,c_time',
            'alias'=>'ID,流水号,类型,交易金额,余额,备注,交易时间',
            'order'=>'id desc',
            'where'=>['searchDate'=>0,'searchName'=>1],
        ],
        'p_manager_action' => [
            'name'=>'管理员操作日志',
            'field'=>'id,manager_name,log,c_time',
            'alias'=>'ID,管理员,日志,操作时间',
            'order'=>'id desc',
            'where'=>['searchDate'=>0, 'searchName'=>2],
        ],
        'p_shop_balance_log' =>[
            'name'=>'店铺交易流水',
            'field'=>'id,balance_sn,sign,money,balance,log,c_time',
            'alias'=>'ID,交易流水号,收入或支出,交易金额,余额,备注,操作时间',
            'order'=>'id desc',
            'where'=>['searchDate'=>0, 'shop_id'=>0],
        ],
        'p_shop_coupon_money_log' =>[
            'name'=>'店铺卡券流水',
            'field'=>'id,balance_sn,sign,money,balance,log,c_time',
            'alias'=>'ID,交易流水号,收入或支出,交易金额,卡券余额,备注,操作时间',
            'order'=>'id desc',
            'where'=>['searchDate'=>0, 'shop_id'=>0],
        ],*/
        'p_user_balance_log' =>[
            'name'=>'用户提现',
            'field'=>'id,bank_number,bank_name,money,c_time',
            'alias'=>'ID,卡号,持卡人,提现金额,提现时间',
            'order'=>'id asc',
            'where'=>['user_id'=>0, 'searchDate'=>0],
            'defaultWhere'=>'status=0'
        ],
        'p_shop_balance_log' =>[
            'name'=>'商家提现',
            'field'=>'id,bank_number,bank_name,money,c_time',
            'alias'=>'ID,卡号,持卡人,提现金额,提现时间',
            'order'=>'id asc',
            'where'=>['shop_id'=>0, 'searchDate'=>0],
            'defaultWhere'=>'status=0'
        ]
    ];

    /**
     * @param $where string|array
     * @param $page integer
     * @param $limit integer
     * @return array
     */
    function index($where, $page, $limit)
    {
        // TODO: Implement index() method.
    }

    /**
     * @param $where string|array
     * @return integer
     */
    function totalCount($where)
    {
        // TODO: Implement totalCount() method.
    }

    /**
     * @param $data array
     * @return boolean
     */
    function add($data)
    {
        // TODO: Implement add() method.
    }

    /**
     * @param $data array
     * @return boolean
     */
    function edit($data)
    {
        // TODO: Implement edit() method.
    }

    /**
     * @return boolean
     */
    function del()
    {
        // TODO: Implement del() method.
    }

    /**
     * @return string
     */
    function getTitle()
    {
        // TODO: Implement getTitle() method.
    }
}