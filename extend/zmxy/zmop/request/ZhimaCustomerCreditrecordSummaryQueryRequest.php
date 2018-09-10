<?php
/**
 * ZHIMA API: zhima.customer.creditrecord.summary.query request
 *
 * @author auto create
 * @since 1.0, 2017-10-20 14:52:52
 */
class ZhimaCustomerCreditrecordSummaryQueryRequest
{
	/** 
	 * 用户支付宝ID
	 **/
	private $alipayUserId;
	
	/** 
	 * 足迹业务场景
	 **/
	private $bizScene;

	private $apiParas = array();
	private $fileParas = array();
	private $apiVersion="1.0";
	private $scene;
	private $channel;
	private $platform;
	private $extParams;

	
	public function setAlipayUserId($alipayUserId)
	{
		$this->alipayUserId = $alipayUserId;
		$this->apiParas["alipay_user_id"] = $alipayUserId;
	}

	public function getAlipayUserId()
	{
		return $this->alipayUserId;
	}

	public function setBizScene($bizScene)
	{
		$this->bizScene = $bizScene;
		$this->apiParas["biz_scene"] = $bizScene;
	}

	public function getBizScene()
	{
		return $this->bizScene;
	}

	public function getApiMethodName()
	{
		return "zhima.customer.creditrecord.summary.query";
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
