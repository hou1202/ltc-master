<?php
namespace app\index\controller;

use app\common\controller\IndexController;
use app\common\model\VerifyModel;

class Verify extends IndexController
{

    protected static $sPermissionArr = [
        'get' => 3
    ];

    protected static $sParamsArr = [
        'get' => ['mobile'=>2, 'type'=>2, 'captcha'=>2]
    ];

    public function get(){
        $verifyModel = new VerifyModel();
        $logData = $this->requestData;
        unset($logData['captcha']);
        $logData['ip'] = $this->request->ip();
        $logData['request_type'] = $this->request->post('request_type',99);
        return $verifyModel->send($this->requestData['type'], $this->requestData['mobile'], $logData) ? $this->jsonSuccess('发送成功') : $this->jsonFail('发送失败');
    }

}
