<?php
/**
 * ZHIMA API: zhima.credit.risk.evaluate.query request
 *
 * @author auto create
 * @since 1.0, 2017-09-21 13:15:15
 */
class ZhimaCreditRiskEvaluateQueryRequest
{
	/** 
	 * 证件号。当证件类型为身份证时，cert_no为身份证号
	 **/
	private $certNo;
	
	/** 
	 * 证件类型 目前支持两种IDENTITY_CARD(身份证),ALIPAY_USER_ID(支付宝uid)
	 **/
	private $certType;
	
	/** 
	 * 扩展参数，供提供更多信息给规则引擎做风险判断。以JSON字符串形式配置
	 **/
	private $extendInfo;
	
	/** 
	 * 姓名，当传入cert_type类型为IDENTITY_CARD时该值为必传项
	 **/
	private $name;
	
	/** 
	 * 产品码
	 **/
	private $productCode;
	
	/** 
	 * ISV商户传入二级商户APPID
普通商户传入自身APPID
	 **/
	private $ruleId;
	
	/** 
	 * 标识对接业务场景，业务场景下商户可做自定义策略配置
	 **/
	private $sceneCode;
	
	/** 
	 * 芝麻业务凭证，详见https://b.zmxy.com.cn/technology/openDoc.htm?id=334
	 **/
	private $transactionId;

	private $apiParas = array();
	private $fileParas = array();
	private $apiVersion="1.0";
	private $scene;
	private $channel;
	private $platform;
	private $extParams;

	
	public function setCertNo($certNo)
	{
		$this->certNo = $certNo;
		$this->apiParas["cert_no"] = $certNo;
	}

	public function getCertNo()
	{
		return $this->certNo;
	}

	public function setCertType($certType)
	{
		$this->certType = $certType;
		$this->apiParas["cert_type"] = $certType;
	}

	public function getCertType()
	{
		return $this->certType;
	}

	public function setExtendInfo($extendInfo)
	{
		$this->extendInfo = $extendInfo;
		$this->apiParas["extend_info"] = $extendInfo;
	}

	public function getExtendInfo()
	{
		return $this->extendInfo;
	}

	public function setName($name)
	{
		$this->name = $name;
		$this->apiParas["name"] = $name;
	}

	public function getName()
	{
		return $this->name;
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

	public function setRuleId($ruleId)
	{
		$this->ruleId = $ruleId;
		$this->apiParas["rule_id"] = $ruleId;
	}

	public function getRuleId()
	{
		return $this->ruleId;
	}

	public function setSceneCode($sceneCode)
	{
		$this->sceneCode = $sceneCode;
		$this->apiParas["scene_code"] = $sceneCode;
	}

	public function getSceneCode()
	{
		return $this->sceneCode;
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
		return "zhima.credit.risk.evaluate.query";
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
