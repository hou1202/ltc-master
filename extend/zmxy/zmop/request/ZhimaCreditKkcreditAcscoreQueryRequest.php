<?php
/**
 * ZHIMA API: zhima.credit.kkcredit.acscore.query request
 *
 * @author auto create
 * @since 1.0, 2017-08-18 13:35:59
 */
class ZhimaCreditKkcreditAcscoreQueryRequest
{
	/** 
	 * 近90天深夜联系人
	 **/
	private $lnizedLnitCttPpl;
	
	/** 
	 * 近150天被叫通话天数
	 **/
	private $lonfizedAnsCttDay;
	
	/** 
	 * 近150天固话通话时长
	 **/
	private $lonfizedRgCttTm;
	
	/** 
	 * 近120天工作日通话联系人占比
	 **/
	private $lontwzedWeekCttPplPct;
	
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
	 * 近5月内平均套餐金额占比
	 **/
	private $trcLsmfiAvgPlanTotalPct;

	private $apiParas = array();
	private $fileParas = array();
	private $apiVersion="1.0";
	private $scene;
	private $channel;
	private $platform;
	private $extParams;

	
	public function setLnizedLnitCttPpl($lnizedLnitCttPpl)
	{
		$this->lnizedLnitCttPpl = $lnizedLnitCttPpl;
		$this->apiParas["lnized_lnit_ctt_ppl"] = $lnizedLnitCttPpl;
	}

	public function getLnizedLnitCttPpl()
	{
		return $this->lnizedLnitCttPpl;
	}

	public function setLonfizedAnsCttDay($lonfizedAnsCttDay)
	{
		$this->lonfizedAnsCttDay = $lonfizedAnsCttDay;
		$this->apiParas["lonfized_ans_ctt_day"] = $lonfizedAnsCttDay;
	}

	public function getLonfizedAnsCttDay()
	{
		return $this->lonfizedAnsCttDay;
	}

	public function setLonfizedRgCttTm($lonfizedRgCttTm)
	{
		$this->lonfizedRgCttTm = $lonfizedRgCttTm;
		$this->apiParas["lonfized_rg_ctt_tm"] = $lonfizedRgCttTm;
	}

	public function getLonfizedRgCttTm()
	{
		return $this->lonfizedRgCttTm;
	}

	public function setLontwzedWeekCttPplPct($lontwzedWeekCttPplPct)
	{
		$this->lontwzedWeekCttPplPct = $lontwzedWeekCttPplPct;
		$this->apiParas["lontwzed_week_ctt_ppl_pct"] = $lontwzedWeekCttPplPct;
	}

	public function getLontwzedWeekCttPplPct()
	{
		return $this->lontwzedWeekCttPplPct;
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
		return "zhima.credit.kkcredit.acscore.query";
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
