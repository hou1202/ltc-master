<?php
namespace app\api\controller;

use app\api\validate\VerifyValidate;
use app\common\controller\ApiController;
use app\common\model\VerifyModel;

class Verify extends ApiController
{

    protected static $sPermissionArr = [
        'get' => 3
    ];

    protected static $sParamsArr = [
        'get' => ['mobile'=>2, 'type'=>2]
    ];

    public function get(){
        $verifyModel = new VerifyModel();
        $logData = $this->requestPostData;
        $logData['ip'] = $this->request->ip();
        $logData['request_type'] = $this->request->post('request_type',99);
        return $verifyModel->send($this->requestPostData['type'], $this->requestPostData['mobile'], $logData) ? $this->jsonSuccess('发送成功') : $this->jsonFail('发送失败');
    }

}
