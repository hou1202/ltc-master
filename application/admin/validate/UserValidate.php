<?php
// +----------------------------------------------------------------------
// | snake
// +----------------------------------------------------------------------
// | Copyright (c) 2016~2022 http://baiyf.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: NickBai <1902822973@qq.com>
// +----------------------------------------------------------------------
namespace app\admin\validate;

use app\admin\model\User;
use app\common\model\BankSearch;
use app\common\model\ValidateModel;
use app\common\validate\BaseValidate;

class UserValidate extends BaseValidate
{

    protected $rule = [
        ['user_id|用户ID', 'require|gt:0|checkId'],
        ['status|审核结果', 'require|in:2,3|checkStatus'],
        ['tag|身份', 'checkTag'],
        ['bank_number|银行卡号' , 'checkBankNumber'],
        ['intro|简介' , 'length:1,1500'],
    ];

    protected $scene = [
        'del'=>['user_id'],
        'edit'=>['user_id'],
    ];

    protected $message = [
        'tag.checkTag' => '专家必须要选择身份'
    ];

    public function checkId($value){
        $this->model = User::get($value);
        return $this->model != null;
    }

    public function checkStatus($status, $rule, $data){
        if($status == 2){
            if(!isset($data['hospital_id']) || empty($data['hospital_id'])){
                $this->message['status.checkStatus'] = '通过审核，必须注明医院';
                return false;
            }
        }
        return true;
    }

    public function checkTag($value, $rule, $data){
        if($data['type']==1 && $value == ''){
            return false;
        }
        return true;
    }

    public function checkBankNumber($value){
        if($value == ''){
            $this->data['bank_name'] = '';
            return true;
        }
        if(!ValidateModel::checkBankCard($value)){
            return false;
        }
        $name = BankSearch::getname($value);
        if($name == null){
            return false;
        }
        $this->data['bank_name'] = $name;
        return true;
    }

}