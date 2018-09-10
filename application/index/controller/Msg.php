<?php
namespace app\index\controller;



use app\api\model\Push;
use app\common\controller\IndexController;
use think\Db;
use think\Session;

class Msg extends IndexController
{

    protected static $sPermissionArr = [
        'add'=>5,
        'index'=>5,
        'detail'=>5,
        'commit'=>7,
    ];

    protected static $sParamsArr = [
        'commit'=>['content'=>2, 'images'=>2]
    ];

    public function add(){
        return $this->fetch();
    }

    public function commit(){
        $data = ['content'=>$this->requestData['content'], 'images'=>$this->requestData['images'], 'user_id'=>$this->userId];
        Db::name('kefu')->insert($data);
        return $this->jsonSuccess('提交成功', ['url'=>'/index/msg/index']);
    }

    public function index(){
        $kefus = Db::name('kefu')->field('id,content,date_format(c_time,\'%Y-%m-%d\') as c_time')
            ->where('user_id='.$this->userId)->order('id desc')->select();
        $this->assign(['kefus'=>$kefus]);
        return $this->fetch();
    }

    public function detail(){
        $id = (int)$this->request->param('id');
        if($id<=0){
            abort(404);
        }
        $kefu = Db::name('kefu')->where('id='.$id.' AND user_id='.$this->userId)->find();
        if(empty($kefu)){
            abort(404);
        }
        $images = explode(',', $kefu['images']);
        $replyTime = $kefu['reply'] == '' ? '' : $kefu['reply_time'];
        $title = $kefu['reply'] == '' ? '系统暂未反馈' : '系统反馈';
        $this->assign(['kefu'=>$kefu, 'images'=>$images, 'reply'=>$kefu['reply'], 'replyTime'=>$replyTime, 'title'=>$title]);
        return $this->fetch();
    }

}