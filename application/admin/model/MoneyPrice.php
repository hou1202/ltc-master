<?php
namespace app\admin\model;

use app\common\model\BaseAdminModel;
use think\Db;
use think\Model;

class MoneyPrice extends BaseAdminModel
{

    public function getTitle()
    {
        return 'LTC价格';
    }

    public function index($where, $page, $limit, $order)
    {
        $offset = ($page-1)*$limit;
        return $this->field('id,price,zhangfu,c_time,sign')
            ->where($where)->limit($offset, $limit)->order($order)->select();
    }

    public function totalCount($where)
    {
        return $this->where($where)->count();
    }

    public function add($data)
    {
        $toDay = date('Y-m-d');
        for($i=1; $i<100; $i++) {
            $yesTime = strtotime($toDay) - $i*24 * 3600;
            $toDayTime = strtotime($toDay) - ($i-1)*24 * 3600;
            $price = Db::name('money_price')->where('c_time>=\'' . date('Y-m-d H:i:s', $yesTime) . '\' AND c_time<\'' . date('Y-m-d H:i:s', $toDayTime) . '\' AND is_del=0')->order('id desc')->value('price');
            if(!empty($price)){
                break;
            }
        }
        if (bccomp($data['price'], $price, 2) >= 0) {
            $data['zhangfu'] = bcmul(bcdiv(bcsub($data['price'], $price, 2), $price, 4), 100, 2);
        } else {
            $data['zhangfu'] = bcmul(bcdiv(bcsub($price, $data['price'], 2), $price, 4), 100, 2);
            $data['sign'] = '-';
        }
        $this->save($data);
        return true;
    }


    public function edit($data)
    {
        $toDay = date('Y-m-d', strtotime($this->c_time));
        $yesTime = strtotime($toDay) - 24*3600;
        $price = Db::name('money_price')->where('c_time>=\''.date('Y-m-d H:i:s', $yesTime).'\' AND c_time<\''.$toDay.'\'')->order('id desc')->value('price');
        empty($price) && $price = 0.5;
        if (bccomp($data['price'], $price, 2) >= 0) {
            $data['zhangfu'] = bcmul(bcdiv(bcsub($data['price'], $price, 2), $price, 4), 100, 2);
        } else {
            $data['zhangfu'] = bcmul(bcdiv(bcsub($price, $data['price'], 2), $price, 4), 100, 2);
            $data['sign'] = '-';
        }
        $this->save($data);
        return true;
    }

    public function del()
    {
        $this->save(['is_del'=>time()]);
        return true;
    }

}