<?php
/**
 * ZHIMA API: zhima.credit.kkcredit.abscore.query request
 *
 * @author auto create
 * @since 1.0, 2017-08-18 13:35:14
 */
class ZhimaCreditKkcreditAbscoreQueryRequest
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
	 * 当前正常的信用卡账户已用额度与可用额度之比的均值
	 **/
	private $normCdtBalUsedPctAvg;
	
	/** 
	 * 芝麻会员在商户端的身份标识。
	 **/
	private $openId;
	
	/** 
	 * 产品码
	 **/
	private $productCode;
	
	/** 
	 * 商户传入的业务流水号。此字段由商户生成，需确保唯一性，用于定位每一次请求，后续按此流水进行对帐。生成规则: 固定30位数字串，前17位为精确到毫秒的时间yyyyMMddhhmmssSSS，后13位为自增数字。
	 **/
	private $transactionId;

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

	public function setProductCode($productCode)
	{
		$this->productCode = $productCode;
		$this->apiParas["product_code"] = $productCode;
	}

	public function getProductCode()
	{
		return $this->productCode;
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

	public function getApiMethodName()
	{
		return "zhima.credit.kkcredit.abscore.query";
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
