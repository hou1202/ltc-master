<?php
/**
 * ZHIMA API: zhima.credit.hetrone.dasscore.query request
 *
 * @author auto create
 * @since 1.0, 2017-09-07 14:52:06
 */
class ZhimaCreditHetroneDasscoreQueryRequest
{
	/** 
	 * 近3月交易总金额
	 **/
	private $amtBankcardTransacThreeMonths;
	
	/** 
	 * 近十二个月有交易的月数
	 **/
	private $cntBankcardTransacTwelveMonths;
	
	/** 
	 * 手机在网时长
	 **/
	private $cntMobileOnline;
	
	/** 
	 * 通讯录分数
	 **/
	private $contactScore;
	
	/** 
	 * 最近有无境外消费
	 **/
	private $existsBankcardTransacOversea;
	
	/** 
	 * 性别
	 **/
	private $gender;
	
	/** 
	 * 用户在商端的身份标识
	 **/
	private $openId;
	
	/** 
	 * 信用产品码，对应云产品的标识
	 **/
	private $productCode;
	
	/** 
	 * 代表一笔请求的唯一标志，该标识作为对账的关键信息，对于用户使用相同transaction_id的查询，芝麻在一天（86400秒）内返回首次查询数据，超过有效期的查询即为无效并返回异常，有效期内的反复查询不重新计费。
transaction_id 推荐生成方式是：30位，（其中17位时间值（精确到毫秒）：yyyyMMddHHmmssSSS）加上（13位自增数字：1234567890123）
	 **/
	private $transactionId;

	private $apiParas = array();
	private $fileParas = array();
	private $apiVersion="1.0";
	private $scene;
	private $channel;
	private $platform;
	private $extParams;

	
	public function setAmtBankcardTransacThreeMonths($amtBankcardTransacThreeMonths)
	{
		$this->amtBankcardTransacThreeMonths = $amtBankcardTransacThreeMonths;
		$this->apiParas["amt_bankcard_transac_three_months"] = $amtBankcardTransacThreeMonths;
	}

	public function getAmtBankcardTransacThreeMonths()
	{
		return $this->amtBankcardTransacThreeMonths;
	}

	public function setCntBankcardTransacTwelveMonths($cntBankcardTransacTwelveMonths)
	{
		$this->cntBankcardTransacTwelveMonths = $cntBankcardTransacTwelveMonths;
		$this->apiParas["cnt_bankcard_transac_twelve_months"] = $cntBankcardTransacTwelveMonths;
	}

	public function getCntBankcardTransacTwelveMonths()
	{
		return $this->cntBankcardTransacTwelveMonths;
	}

	public function setCntMobileOnline($cntMobileOnline)
	{
		$this->cntMobileOnline = $cntMobileOnline;
		$this->apiParas["cnt_mobile_online"] = $cntMobileOnline;
	}

	public function getCntMobileOnline()
	{
		return $this->cntMobileOnline;
	}

	public function setContactScore($contactScore)
	{
		$this->contactScore = $contactScore;
		$this->apiParas["contact_score"] = $contactScore;
	}

	public function getContactScore()
	{
		return $this->contactScore;
	}

	public function setExistsBankcardTransacOversea($existsBankcardTransacOversea)
	{
		$this->existsBankcardTransacOversea = $existsBankcardTransacOversea;
		$this->apiParas["exists_bankcard_transac_oversea"] = $existsBankcardTransacOversea;
	}

	public function getExistsBankcardTransacOversea()
	{
		return $this->existsBankcardTransacOversea;
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
		return "zhima.credit.hetrone.dasscore.query";
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
