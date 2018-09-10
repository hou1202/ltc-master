<?php
namespace app\common\service;
/**
 * Project: wxShare
 * User: Zhu Ziqiang
 * Date: 2017/8/31
 * Time: 13:55
 */
interface IAction
{
    /**
     * @param $where string|array
     * @param $page integer
     * @param $limit integer
     * @param $order string
     * @return array
     */
    function index($where, $page, $limit, $order);

    /**
     * @param $where string|array
     * @return integer
     */
    function totalCount($where);

    /**
     * @param $data array
     * @return boolean
     */
    function add($data);

    /**
     * @param $data array
     * @return boolean
     */
    function edit($data);

    /**
     * @return boolean
     */
    function del();

    /**
     * @return string
     */
    function getTitle();
}