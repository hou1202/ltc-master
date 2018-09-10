<?php
namespace app\admin\validate;

use app\admin\model\News;
use app\common\validate\BaseValidate;
use think\Db;

class NewsValidate extends BaseValidate
{
    /**
     * @var News
     */
    protected $model;


    protected $rule = [
        ['news_id', 'require|gt:0|checkId'],
        ['category_id', 'gt:0'],
        ['title', 'length:1,50'],
        ['sub_title', 'length:1,50'],
        ['sub_content', 'length:1,255'],
        ['author', 'length:1,50'],
        ['is_zd', 'in:0,1'],
        ['content', 'require'],
    ];

    protected $scene = [
        'add' => ['category_id', 'title', 'sub_title', 'author', 'is_zd', 'content', 'sub_content'],
        'edit' => ['news_id', 'category_id', 'title', 'sub_title', 'author', 'is_zd', 'content', 'sub_content'],
        'del'=>['news_id']
    ];

    protected $message = [

    ];

    public function checkId($value){
        $this->model = News::get($value);
        return $this->model != null;
    }

}