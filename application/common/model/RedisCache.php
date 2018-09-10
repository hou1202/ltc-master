<?php
/**
 * Project: 基础库-Redis缓存
 * User: Zhu Ziqiang
 * Date: 2017/3/17
 * Time: 14:48
 */
namespace app\common\model;


use think\cache\driver\Redis;
use think\Config as ThinkConfig;

class RedisCache
{
    private static $instance;
    private $mRedis;
    /**
     * 不同的模块 缓存不联系
     * @var mixed|string
     */
    private $_prifix = '';

    private function __construct()
    {
        $this->_prifix = ThinkConfig::get('cache_prifix');
        $this->mRedis = new Redis();
    }

    /**
     * @return RedisCache
     */
    public static function newInstance(){
        if(RedisCache::$instance == null){
            RedisCache::$instance = new RedisCache();
        }
        return RedisCache::$instance;
    }

    /**
     * 通过key获取到Value
     * @param $key
     * @return mixed
     */
    public function get($key){
        $key = $this->makeKey($key);
        return $this->mRedis->handler()->get($key);
    }

    /**
     * 判断key是否存在
     * @param $key
     * @return mixed
     */
    public function exists($key){
        $key = $this->makeKey($key);
        return $this->mRedis->handler()->exists($key);
    }

    /**
     * 设置key=》value缓存
     * @param $key
     * @param $value
     * @param null $expire
     * @return bool
     */
    public function set($key, $value, $expire=null){
        $key = $this->makeKey($key);
        return $this->mRedis->set($key, $value, $expire);
    }

    /**
     * 删除
     * @param $key
     * @return mixed
     */
    public function del($key){
        $key = $this->makeKey($key);
        return $this->mRedis->handler()->delete($key);
    }

    /**
     * 更新时间
     * @param $key
     * @param $time
     * @return mixed
     */
    public function updateTime($key, $time){
        return $this->mRedis->handler()->expire($this->makeKey($key), $time);
    }

    /**
     * 剩余存活时间
     * @param $key
     * @return mixed
     */
    public function ttl($key){
        return $this->mRedis->handler()->ttl($this->makeKey($key));
    }

    /**
     * 自增缓存（针对数值缓存）
     * @access public
     * @param string    $key 缓存变量名
     * @param int       $step 步长
     * @return false|int
     */
    public function inc($key, $step = 1)
    {
        $key = $this->makeKey($key);
        return $this->mRedis->handler()->incrby($key, $step);
    }

    /**
     * 生成key
     * @param $name
     * @return string
     */
    private function makeKey($name){
        return md5($this->_prifix.$name);
    }

    private function __clone(){}

}