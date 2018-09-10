<?php
/**
 * ZHIMA API: zhima.credit.bqs.defaultscore.query request
 *
 * @author auto create
 * @since 1.0, 2017-09-07 14:55:48
 */
class ZhimaCreditBqsDefaultscoreQueryRequest
{
	/** 
	 * 申请事件通过比率
	 **/
	private $acceptPercentApply;
	
	/** 
	 * 年龄
	 **/
	private $age;
	
	/** 
	 * 申请时间（小时，0-23）
	 **/
	private $applyHour;
	
	/** 
	 * 多头申请商户类型数量
	 **/
	private $applyPartnerTypeCount;
	
	/** 
	 * 黑名单命中个数
	 **/
	private $blackCount;
	
	/** 
	 * 本人主要通话活动区域在几线城市
	 **/
	private $callActiveArea;
	
	/** 
	 * 排除被叫电话很短的联系人个数
	 **/
	private $contactExcludedCount;
	
	/** 
	 * 朋友圈活动区域在几线城市
	 **/
	private $contactsActiveArea;
	
	/** 
	 * 关联设备数量
	 **/
	private $deviceCount;
	
	/** 
	 * 性别
	 **/
	private $gender;
	
	/** 
	 * GPS城市数量
	 **/
	private $gpsCityCount;
	
	/** 
	 * 全天未使用通话和短信功能天数
	 **/
	private $inactiveDays;
	
	/** 
	 * IP城市数量
	 **/
	private $ipCityCount;
	
	/** 
	 * 设备中借贷app数量
	 **/
	private $loanAppCount;
	
	/** 
	 * 手机号
	 **/
	private $mobile;
	
	/** 
	 * 多头申请商户数量
	 **/
	private $multiapplyCount;
	
	/** 
	 * 夜间通话次数
	 **/
	private $nightCalls;
	
	/** 
	 * 联系人中非手机个数
	 **/
	private $noneMobileCount;
	
	/** 
	 * 仅有被叫联系人个数
	 **/
	private $onlyTerminCount;
	
	/** 
	 * 入网时长
	 **/
	private $openDays;
	
	/** 
	 * 用户在商端的身份标识
	 **/
	private $openId;
	
	/** 
	 * 该用户第一次事件距今时间
	 **/
	private $phoneDays;
	
	/** 
	 * 信用产品码，对应云产品的标识
	 **/
	private $productCode;
	
	/** 
	 * 省份代码
	 **/
	private $provinceId;
	
	/** 
	 * 申请事件拒绝比率
	 **/
	private $rejectPercentApply;
	
	/** 
	 * 话费消费总金额
	 **/
	private $sumInfoCostMoney;
	
	/** 
	 * 最常用联系人，多个用逗号分隔
	 **/
	private $topContact;
	
	/** 
	 * 代表一笔请求的唯一标志，该标识作为对账的关键信息，对于用户使用相同transaction_id的查询，芝麻在一天（86400秒）内返回首次查询数据，超过有效期的查询即为无效并返回异常，有效期内的反复查询不重新计费。
transaction_id 推荐生成方式是：30位，（其中17位时间值（精确到毫秒）：yyyyMMddHHmmssSSS）加上（13位自增数字：1234567890123）
	 **/
	private $transactionId;
	
	/** 
	 * 白名单等级
	 **/
	private $whiteGrade;
	
	/** 
	 * 上班时间手机号关联城市数量
	 **/
	private $workCityCount;

	private $apiParas = array();
	private $fileParas = array();
	private $apiVersion="1.0";
	private $scene;
	private $channel;
	private $platform;
	private $extParams;

	
	public function setAcceptPercentApply($acceptPercentApply)
	{
		$this->acceptPercentApply = $acceptPercentApply;
		$this->apiParas["accept_percent_apply"] = $acceptPercentApply;
	}

	public function getAcceptPercentApply()
	{
		return $this->acceptPercentApply;
	}

	public function setAge($age)
	{
		$this->age = $age;
		$this->apiParas["age"] = $age;
	}

	public function getAge()
	{
		return $this->age;
	}

	public function setApplyHour($applyHour)
	{
		$this->applyHour = $applyHour;
		$this->apiParas["apply_hour"] = $applyHour;
	}

	public function getApplyHour()
	{
		return $this->applyHour;
	}

	public function setApplyPartnerTypeCount($applyPartnerTypeCount)
	{
		$this->applyPartnerTypeCount = $applyPartnerTypeCount;
		$this->apiParas["apply_partner_type_count"] = $applyPartnerTypeCount;
	}

	public function getApplyPartnerTypeCount()
	{
		return $this->applyPartnerTypeCount;
	}

	public function setBlackCount($blackCount)
	{
		$this->blackCount = $blackCount;
		$this->apiParas["black_count"] = $blackCount;
	}

	public function getBlackCount()
	{
		return $this->blackCount;
	}

	public function setCallActiveArea($callActiveArea)
	{
		$this->callActiveArea = $callActiveArea;
		$this->apiParas["call_active_area"] = $callActiveArea;
	}

	public function getCallActiveArea()
	{
		return $this->callActiveArea;
	}

	public function setContactExcludedCount($contactExcludedCount)
	{
		$this->contactExcludedCount = $contactExcludedCount;
		$this->apiParas["contact_excluded_count"] = $contactExcludedCount;
	}

	public function getContactExcludedCount()
	{
		return $this->contactExcludedCount;
	}

	public function setContactsActiveArea($contactsActiveArea)
	{
		$this->contactsActiveArea = $contactsActiveArea;
		$this->apiParas["contacts_active_area"] = $contactsActiveArea;
	}

	public function getContactsActiveArea()
	{
		return $this->contactsActiveArea;
	}

	public function setDeviceCount($deviceCount)
	{
		$this->deviceCount = $deviceCount;
		$this->apiParas["device_count"] = $deviceCount;
	}

	public function getDeviceCount()
	{
		return $this->deviceCount;
	}

	public function setGender($gender)
	{
		$this->gender = $gender;
		$this->apiParas["gender"] = $gender;
	}

	public function getGender()
	{
		return $this->gender;
	}

	public function setGpsCityCount($gpsCityCount)
	{
		$this->gpsCityCount = $gpsCityCount;
		$this->apiParas["gps_city_count"] = $gpsCityCount;
	}

	public function getGpsCityCount()
	{
		return $this->gpsCityCount;
	}

	public function setInactiveDays($inactiveDays)
	{
		$this->inactiveDays = $inactiveDays;
		$this->apiParas["inactive_days"] = $inactiveDays;
	}

	public function getInactiveDays()
	{
		return $this->inactiveDays;
	}

	public function setIpCityCount($ipCityCount)
	{
		$this->ipCityCount = $ipCityCount;
		$this->apiParas["ip_city_count"] = $ipCityCount;
	}

	public function getIpCityCount()
	{
		return $this->ipCityCount;
	}

	public function setLoanAppCount($loanAppCount)
	{
		$this->loanAppCount = $loanAppCount;
		$this->apiParas["loan_app_count"] = $loanAppCount;
	}

	public function getLoanAppCount()
	{
		return $this->loanAppCount;
	}

	public function setMobile($mobile)
	{
		$this->mobile = $mobile;
		$this->apiParas["mobile"] = $mobile;
	}

	public function getMobile()
	{
		return $this->mobile;
	}

	public function setMultiapplyCount($multiapplyCount)
	{
		$this->multiapplyCount = $multiapplyCount;
		$this->apiParas["multiapply_count"] = $multiapplyCount;
	}

	public function getMultiapplyCount()
	{
		return $this->multiapplyCount;
	}

	public function setNightCalls($nightCalls)
	{
		$this->nightCalls = $nightCalls;
		$this->apiParas["night_calls"] = $nightCalls;
	}

	public function getNightCalls()
	{
		return $this->nightCalls;
	}

	public function setNoneMobileCount($noneMobileCount)
	{
		$this->noneMobileCount = $noneMobileCount;
		$this->apiParas["none_mobile_count"] = $noneMobileCount;
	}

	public function getNoneMobileCount()
	{
		return $this->noneMobileCount;
	}

	public function setOnlyTerminCount($onlyTerminCount)
	{
		$this->onlyTerminCount = $onlyTerminCount;
		$this->apiParas["only_termin_count"] = $onlyTerminCount;
	}

	public function getOnlyTerminCount()
	{
		return $this->onlyTerminCount;
	}

	public function setOpenDays($openDays)
	{
		$this->openDays = $openDays;
		$this->apiParas["open_days"] = $openDays;
	}

	public function getOpenDays()
	{
		return $this->openDays;
	}

	public function setOpenId($openId)
	{
		$this->openId = $openId;
		$this->apiParas["open_id"] = $openId;
	}

	public function getOpenId()
	{
		return $this->openId;
	}

	public function setPhoneDays($phoneDays)
	{
		$this->phoneDays = $phoneDays;
		$this->apiParas["phone_days"] = $phoneDays;
	}

	public function getPhoneDays()
	{
		return $this->phoneDays;
	}

	public function setProductCode($productCode)
	{
		$this->productCode = $productCode;
		$this->apiParas["product_code"] = $productCode;
	}

	public function getProductCode()
	{
		return $this->productCode;
	}

	public function setProvinceId($provinceId)
	{
		$this->provinceId = $provinceId;
		$this->apiParas["province_id"] = $provinceId;
	}

	public function getProvinceId()
	{
		return $this->provinceId;
	}

	public function setRejectPercentApply($rejectPercentApply)
	{
		$this->rejectPercentApply = $rejectPercentApply;
		$this->apiParas["reject_percent_apply"] = $rejectPercentApply;
	}

	public function getRejectPercentApply()
	{
		return $this->rejectPercentApply;
	}

	public function setSumInfoCostMoney($sumInfoCostMoney)
	{
		$this->sumInfoCostMoney = $sumInfoCostMoney;
		$this->apiParas["sum_info_cost_money"] = $sumInfoCostMoney;
	}

	public function getSumInfoCostMoney()
	{
		return $this->sumInfoCostMoney;
	}

	public function setTopContact($topContact)
	{
		$this->topContact = $topContact;
		$this->apiParas["top_contact"] = $topContact;
	}

	public function getTopContact()
	{
		return $this->topContact;
	}

	public function setTransactionId($transactionId)
	{
		$this->transactionId = $transactionId;
		$this->apiParas["transaction_id"] = $transactionId;
	}

	public function getTransactionId()
	{
		return $this->transactionId;
	}

	public function setWhiteGrade($whiteGrade)
	{
		$this->whiteGrade = $whiteGrade;
		$this->apiParas["white_grade"] = $whiteGrade;
	}

	public function getWhiteGrade()
	{
		return $this->whiteGrade;
	}

	public function setWorkCityCount($workCityCount)
	{
		$this->workCityCount = $workCityCount;
		$this->apiParas["work_city_count"] = $workCityCount;
	}

	public function getWorkCityCount()
	{
		return $this->workCityCount;
	}

	public function getApiMethodName()
	{
		return "zhima.credit.bqs.defaultscore.query";
	}

	public function setScene($scene)
	{
		$this->scene=$scene;
	}

	public function getScene()
	{
		return $this->scene;
	}
	
	public function setChannel($channel)
	{
		$this->channel=$channel;
	}

	public function getChannel()
	{
		return $this->channel;
	}
	
	public function setPlatform($platform)
	{
		$this->platform=$platform;
	}

	public function getPlatform()
	{
		return $this->platform;
	}

	public function setExtParams($extParams)
	{
		$this->extParams=$extParams;
	}

	public function getExtParams()
	{
		return $this->extParams;
	}	

	public function getApiParas()
	{
		return $this->apiParas;
	}
	
	public function getFileParas()
	{
		return $this->fileParas;
	}

	public function setApiVersion($apiVersion)
	{
		$this->apiVersion=$apiVersion;
	}

	public function getApiVersion()
	{
		return $this->apiVersion;
	}

}
