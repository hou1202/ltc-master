<?php
namespace app\admin\model;

use app\common\model\BaseAdminModel;
use think\Db;
use think\Config as TConfig;
use think\Model;
use think\Request;

class News extends BaseAdminModel
{

    public static $sLogTableName = 'news_log';

    protected $imageSize = [
        'poster'=>['name'=>'展示图', 'width'=>210, 'height'=>140, 'limit'=>1]
    ];

    public function getTitle()
    {
        return '文章';
    }

    public function index($where, $page, $limit, $order)
    {
        $offset = ($page-1)*$limit;
        return $this->field('news_id,title,author,click_count,is_zd,c_time')
            ->where($where)->limit($offset, $limit)->order($order)->select();
    }

    public function totalCount($where)
    {
        return $this->where($where)->count();
    }

    public function add($data)
    {
        $this->save($data);
        return true;
    }


    public function edit($data)
    {
        $this->save($data);
        return true;
    }

    public function del()
    {
        $this->save(['is_del'=>time()]);
        return true;
    }

    /**
     * 获取APP接口 新闻列表
     * @param $categoryId
     * @param $page
     * @return array
     */
    public static function getApiNews($categoryId, $page){
        $domain = TConfig::get('upload_file_domain');
        $newses = static::getNewses(['category_id'=>$categoryId], $page);
        foreach($newses as $k=>$v){
            $newses[$k]['link_url'] = $domain.'/api/page/newsDetail/news_id/'.$v['news_id'].'.html';
        }
        return ['banner'=>$page==0 ? Banner::getApiBanner($categoryId) : [], 'newses'=>$newses];
    }

    /**
     * 获取新闻列表
     * @param $where
     * @param int $page
     * @param int $limit
     * @return array
     */
    public static function getNewses($where, $page=0, $limit=10){
        $domain = TConfig::get('upload_file_domain');
        $where['is_del'] = 0;
        $newses = Db::table(static::getTable())->field('news_id,poster,title,click_count,DATE_FORMAT(c_time,\'%Y.%m.%d\') as c_time')
            ->where($where)
            ->order('is_zd desc,news_id desc')
            ->limit($page*$limit, $limit)
            ->select();
        foreach($newses as $k=>$v){
            $newses[$k]['link_url'] =  $domain.'/api/page/newsDetail/news_id/'.$v['news_id'].'.html';
        }
        return $newses;
    }

    /**
     * 新闻详情
     * @param $newsId
     * @return array|null
     * @throws \think\Exception
     */
    public static function getNewsDetail($newsId){
        $ip = Request::instance()->ip();
        if(Db::name(static::$sLogTableName)->where(['news_id'=>$newsId, 'ip'=>$ip])->count()<100){
            Db::name(static::$sLogTableName)->insert(['news_id'=>$newsId, 'ip'=>$ip]);
            Db::table(static::getTable())->where('news_id='.$newsId)->setInc('click_count');
        }
        return Db::table(static::getTable())->where('news_id='.$newsId)->field('title,author,click_count,date_format(c_time,\'%Y-%m-%d\') as c_time,content')->find();
    }


    /**
     * 获取会议咨询
     * @return array
     */
    public static function getConferences(){
        $categoryId = NewsCategory::getCategoryIdByCategoryName('会议资讯');
        $conferences = [];
        if($categoryId>0){
            $conferences = Db::table(static::getTable())->field('news_id,title,city')->where('category_id='.$categoryId.' AND is_del=0')->order('is_zd desc,news_id desc')->limit(5)->select();
        }
        return ['category_id'=>$categoryId, 'category_name'=>'会议资讯', 'newses'=>$conferences];
    }

    /**
     * 获取新闻头条
     * @return array
     */
    public static function getNewsHeads(){
        $categoryId = NewsCategory::getCategoryIdByCategoryName('新闻头条');
        $conferences = [];
        if($categoryId>0){
            $conferences = Db::table(static::getTable())->field('news_id,title')->where('category_id='.$categoryId.' AND is_del=0')->order('is_zd desc,news_id desc')->limit(5)->select();
        }
        return ['category_id'=>$categoryId, 'category_name'=>'新闻头条', 'newses'=>$conferences];
    }

    /**
     * 获取WEB端首页的新闻
     * @param $categoryId
     * @param $page int 从1开始
     * @return array
     */
    public static function getIndexNewses($categoryId, $page){
        $where = ['category_id'=>$categoryId, 'is_del'=>0];
        $newses = Db::table(static::getTable())->field('news_id,poster,title,sub_content')
            ->where($where)
            ->order('is_zd desc,news_id desc')
            ->limit(($page-1)*5, 5)
            ->select();
        return $newses;
    }

    /**
     * 获取新闻条数
     * @param $categoryId
     * @return int
     */
    public static function getIndexTotalCount($categoryId){
        $where = ['category_id'=>$categoryId, 'is_del'=>0];
        return (int)Db::table(static::getTable())->where($where)->count();
    }

}