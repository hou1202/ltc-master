<?php
/**
 * ZHIMA API: zhima.credit.kkcredit.abcscore.query request
 *
 * @author auto create
 * @since 1.0, 2017-08-22 15:59:07
 */
class ZhimaCreditKkcreditAbcscoreQueryRequest
{
	/** 
	 * 年龄
	 **/
	private $age;
	
	/** 
	 * 未销信用卡开户距今月数的平均数
	 **/
	private $crdAgeUnclsAvg;
	
	/** 
	 * 性别，男=1，女=0
	 **/
	private $gender;
	
	/** 
	 * 近90天深夜联系人
	 **/
	private $lnizedLnitCttPpl;
	
	/** 
	 * 当前正常的信用卡账户已用额度与可用额度之比的均值
	 **/
	private $normCdtBalUsedPctAvg;
	
	/** 
	 * 芝麻会员在商户端的身份标识。
	 **/
	private $openId;
	
	/** 
	 * 手机注册时长
	 **/
	private $phoneUseMth;
	
	/** 
	 * 产品码
	 **/
	private $productCode;
	
	/** 
	 * 近150天短信发送联系人
	 **/
	private $smsLonfizedSendPpl;
	
	/** 
	 * 商户传入的业务流水号。此字段由商户生成，需确保唯一性，用于定位每一次请求，后续按此流水进行对帐。生成规则: 固定30位数字串，前17位为精确到毫秒的时间yyyyMMddhhmmssSSS，后13位为自增数字。
	 **/
	private $transactionId;
	
	/** 
	 * 近5月套餐金额占比
	 **/
	private $trcLsmfiAvgPlanTotalPct;

	private $apiParas = array();
	private $fileParas = array();
	private $apiVersion="1.0";
	private $scene;
	private $channel;
	private $platform;
	private $extParams;

	
	public function setAge($age)
	{
		$this->age = $age;
		$this->apiParas["age"] = $age;
	}

	public function getAge()
	{
		return $this->age;
	}

	public function setCrdAgeUnclsAvg($crdAgeUnclsAvg)
	{
		$this->crdAgeUnclsAvg = $crdAgeUnclsAvg;
		$this->apiParas["crd_age_uncls_avg"] = $crdAgeUnclsAvg;
	}

	public function getCrdAgeUnclsAvg()
	{
		return $this->crdAgeUnclsAvg;
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

	public function setLnizedLnitCttPpl($lnizedLnitCttPpl)
	{
		$this->lnizedLnitCttPpl = $lnizedLnitCttPpl;
		$this->apiParas["lnized_lnit_ctt_ppl"] = $lnizedLnitCttPpl;
	}

	public function getLnizedLnitCttPpl()
	{
		return $this->lnizedLnitCttPpl;
	}

	public function setNormCdtBalUsedPctAvg($normCdtBalUsedPctAvg)
	{
		$this->normCdtBalUsedPctAvg = $normCdtBalUsedPctAvg;
		$this->apiParas["norm_cdt_bal_used_pct_avg"] = $normCdtBalUsedPctAvg;
	}

	public function getNormCdtBalUsedPctAvg()
	{
		return $this->normCdtBalUsedPctAvg;
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

	public function setPhoneUseMth($phoneUseMth)
	{
		$this->phoneUseMth = $phoneUseMth;
		$this->apiParas["phone_use_mth"] = $phoneUseMth;
	}

	public function getPhoneUseMth()
	{
		return $this->phoneUseMth;
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

	public function setSmsLonfizedSendPpl($smsLonfizedSendPpl)
	{
		$this->smsLonfizedSendPpl = $smsLonfizedSendPpl;
		$this->apiParas["sms_lonfized_send_ppl"] = $smsLonfizedSendPpl;
	}

	public function getSmsLonfizedSendPpl()
	{
		return $this->smsLonfizedSendPpl;
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

	public function setTrcLsmfiAvgPlanTotalPct($trcLsmfiAvgPlanTotalPct)
	{
		$this->trcLsmfiAvgPlanTotalPct = $trcLsmfiAvgPlanTotalPct;
		$this->apiParas["trc_lsmfi_avg_plan_total_pct"] = $trcLsmfiAvgPlanTotalPct;
	}

	public function getTrcLsmfiAvgPlanTotalPct()
	{
		return $this->trcLsmfiAvgPlanTotalPct;
	}

	public function getApiMethodName()
	{
		return "zhima.credit.kkcredit.abcscore.query";
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
