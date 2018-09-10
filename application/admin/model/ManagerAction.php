<?php
/**
 * Project: huihuidao
 * User: Zhu Ziqiang
 * Date: 2017/9/15
 * Time: 9:15
 */

namespace app\admin\model;


use app\common\model\BaseAdminModel;

class ManagerAction extends BaseAdminModel
{

    function index($where, $page, $limit, $order)
    {
        $offset = ($page-1)*$limit;
        return $this->where($where)->limit($offset, $limit)->order($order)->select();
    }

    /**
     * @param $where string|array
     * @return integer
     */
    function totalCount($where)
    {
        return $this->where($where)->count();
    }

    /**
     * @param $data array
     * @return boolean
     */
    function add($data)
    {
        $this->save($data);
        return true;
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
        return '操作日志';
    }
}