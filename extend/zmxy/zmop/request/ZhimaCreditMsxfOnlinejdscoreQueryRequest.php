<?php
/**
 * ZHIMA API: zhima.credit.msxf.onlinejdscore.query request
 *
 * @author auto create
 * @since 1.0, 2017-08-22 17:29:40
 */
class ZhimaCreditMsxfOnlinejdscoreQueryRequest
{
	/** 
	 * 特殊订单金额差异占比
	 **/
	private $allFddifDivideSixaQdHourbinAmtaorder;
	
	/** 
	 * 短时间下单金额的差异系数
	 **/
	private $allFddifMinusFiveaRangeHourbinAmtaorder;
	
	/** 
	 * 特殊时间下单金额的波动指标
	 **/
	private $allFddifMinusTwoaSdHourbinAmtaorder;
	
	/** 
	 * 特定支付方式金额指标
	 **/
	private $allFdescMeanPayonlinepaymentAmtorder;
	
	/** 
	 * 用户购买时间波动系数1
	 **/
	private $allGddescMinusLoantimenowMaxDaydiff;
	
	/** 
	 * 用户购买时间波动系数2
	 **/
	private $allGddescMinusLoantimenowMinHourdiff;
	
	/** 
	 * 用户特殊支付金额占比
	 **/
	private $allGddifDivCashondeliveryallSumPayAmtorder;
	
	/** 
	 * 用户特殊支付金额指标
	 **/
	private $allGddifDivOnlinepaymentallSumPayAmtorder;
	
	/** 
	 * 用户特殊商品差异性指标
	 **/
	private $allGddifDivSportsoutdoorallNCntprdcategory;
	
	/** 
	 * 用户特殊订单占比
	 **/
	private $allGddifDivideFailallNStsCntorder;
	
	/** 
	 * 用户特殊时间下单量指标
	 **/
	private $allGddifDivideFiveeightallNHourCntorder;
	
	/** 
	 * 用户特殊商品差异性系数
	 **/
	private $allGddifDividePhonedigitalallNCntprdcategory;
	
	/** 
	 * 用户特殊订单量指标
	 **/
	private $allGddifMinusCaMaxProductCntaorder;
	
	/** 
	 * 用户特殊订单的差异性指标
	 **/
	private $allGddifMinusCaSumAorderCntproduct;
	
	/** 
	 * 用户特殊产品之间差异系数
	 **/
	private $allGddifMinusCsMedianProductCntaorder;
	
	/** 
	 * 用户特殊订单下单金额异常指标
	 **/
	private $allGddifMinusCsSkewAorderAmtaorder;
	
	/** 
	 * 用户购买商品的波动性指标
	 **/
	private $allGddifMinusSaEntropyAorderCntproduct;
	
	/** 
	 * 用户购买商品量
	 **/
	private $allGddifMinusSaSumAorderCntproduct;
	
	/** 
	 * 用户购买固定商品的稳定性
	 **/
	private $allGddifMinusSaSumProductCntaorder;
	
	/** 
	 * 用户购买固定商品的差异
	 **/
	private $allGdescMeanProductCntaorder;
	
	/** 
	 * 用户购买特殊订单的数量
	 **/
	private $allGdescMeanSorderAmtaorder;
	
	/** 
	 * 用户特殊订单金额指标
	 **/
	private $allGdescMinCorderAmtaorder;
	
	/** 
	 * 用户下单稳定性系数
	 **/
	private $allGdescMinPhoneSumamt;
	
	/** 
	 * 用户购买稳定性指标
	 **/
	private $allGdescMinRecaddrcitySumamt;
	
	/** 
	 * 用户下单稳定性
	 **/
	private $allGdescMinRecaddrprovinceAvgamt;
	
	/** 
	 * 用户购物行为稳定性指标
	 **/
	private $allGdescNormentropyPhoneCntorder;
	
	/** 
	 * 用户特殊购买行为稳定性指标
	 **/
	private $allGdescNormentropyProductCntsorder;
	
	/** 
	 * 用户特殊订单容量指标
	 **/
	private $allGdescQlSorderAmtaorder;
	
	/** 
	 * 用户下单跨度行为指标
	 **/
	private $allTsdescAmtorderdiffAmtdiffMedian;
	
	/** 
	 * 用户下单跨度行为稳定性
	 **/
	private $allTsdescAmtorderdiffAmtdiffQu;
	
	/** 
	 * 用户下单跨度行为波动性
	 **/
	private $allTsdescAmtorderdiffAmtdiffSum;
	
	/** 
	 * 用户下单跨度特殊差异性
	 **/
	private $allTsdescAmtorderdiffTimediffCv;
	
	/** 
	 * 用户下单跨度可靠性
	 **/
	private $allTsdescAmtorderdiffTimediffQfour;
	
	/** 
	 * 用户下单金额差异稳定度
	 **/
	private $allTsdescAmtorderdiffTimediffQsix;
	
	/** 
	 * 用户下单时间稳定度
	 **/
	private $allTsdescAmtorderdiffTimediffQu;
	
	/** 
	 * 用户下单行为差异度
	 **/
	private $allTsdescAmtorderdiffVamtQnine;
	
	/** 
	 * 用户可信度指标
	 **/
	private $jdauthFddescExistChannelfinanceAuth;
	
	/** 
	 * 用户授信稳定性指标
	 **/
	private $jdauthFddescExistLoginnameEqualPhone;
	
	/** 
	 * 用户信用欺诈指标
	 **/
	private $jdauthFddescMinusNowauthtimeSeconds;
	
	/** 
	 * 用户信用稳定性指标
	 **/
	private $jdbankcardDescDivideNOwnernameReceiver;
	
	/** 
	 * 用户可信度差异
	 **/
	private $jdbankcardDescNBankphoneAuthphone;
	
	/** 
	 * 用户可信度波动系数
	 **/
	private $jdbankcardDescNOwnernameReceiver;
	
	/** 
	 * 用户稳定性支付系数
	 **/
	private $jdbankcardDiffDivideNndBindphone;
	
	/** 
	 * 用户主流支付差异
	 **/
	private $jdbankcardFdescNBanknameMajorfourbanks;
	
	/** 
	 * 用户支付信用系数
	 **/
	private $jdbankcardFdescNBanknameOthers;
	
	/** 
	 * 用户支付差异系数
	 **/
	private $jdbankcardFdiffDivideAbcallCntbankname;
	
	/** 
	 * 用户信用卡稳定性
	 **/
	private $jdbankcardFdiffDivideCreditallCntcardtype;
	
	/** 
	 * 用户支付稳定性
	 **/
	private $jdbankcardFdiffDividePostallCntbankname;
	
	/** 
	 * 用户信用指标
	 **/
	private $jdbtGddescExtractCreditscoreBt;
	
	/** 
	 * 用户信用稳定系数
	 **/
	private $jdbtGddiffMinusOverdraftquotaBtAmt;
	
	/** 
	 * 用户活动金额指标
	 **/
	private $jdoneoneoneonesumGdescAmt;
	
	/** 
	 * 用户居住稳定性指标
	 **/
	private $jdreceivaddrDescNAddress;
	
	/** 
	 * 用户收货地址差异系数
	 **/
	private $jdreceivaddrDescNNaemail;
	
	/** 
	 * 用户收货地址稳定性指标
	 **/
	private $jdreceivaddrDescRateNafixphone;
	
	/** 
	 * 用户活动金额范围系数
	 **/
	private $jdsixoneeightsumGdescAmt;
	
	/** 
	 * 用户注册差异性指标
	 **/
	private $jduserFddescExistWebloginnameLogname;
	
	/** 
	 * 用户下单时间金额总共的时间精度
	 **/
	private $jduserFddescNdCompareThreenames;
	
	/** 
	 * 用户的绑定粘性指标
	 **/
	private $jduserIsbindBothqqwechat;
	
	/** 
	 * 1年内短时间金额稳定性指标
	 **/
	private $oneyFddifDivideSevenaRangeHourbinAmtaorder;
	
	/** 
	 * 1年内短时间金额占比
	 **/
	private $oneyFddifMinusOneaRangeHourbinAmtaorder;
	
	/** 
	 * 1年内短时间订单金额稳定性指标
	 **/
	private $oneyFddifMinusSixaRangeHourbinAmtaorder;
	
	/** 
	 * 1年内特殊订单金额平均水平
	 **/
	private $oneyFdescMeanPaycashondeliveryAmtorder;
	
	/** 
	 * 1年内特殊订单金额异常指标
	 **/
	private $oneyFdescSumMeaninvoicecontentAmtorder;
	
	/** 
	 * 1年内在线支付金额占比
	 **/
	private $oneyGddifDivOnlinepaymentallSumPayAmtorder;
	
	/** 
	 * 1年内特殊订单购买能力
	 **/
	private $oneyGddifMinusCaMedianAorderAmtaorder;
	
	/** 
	 * 1年内取消订单订单金额差异性指标
	 **/
	private $oneyGddifMinusCaSdAmtbinAmtaorder;
	
	/** 
	 * 1年内订单数量总和差异
	 **/
	private $oneyGddifMinusCaSumAorderCntproduct;
	
	/** 
	 * 1年内特殊订单金额波动性指标
	 **/
	private $oneyGddifMinusSaEntropyAmtbinAmtaorder;
	
	/** 
	 * 1年内地址差异指标
	 **/
	private $oneyGdescCvRecaddrcityAvgamt;
	
	/** 
	 * 1年内特殊订单金额分段差异性指标
	 **/
	private $oneyGdescNormentropyAmtbinAmtsorder;
	
	/** 
	 * 1年内订单金额特殊时间差异性系数
	 **/
	private $oneyTsdescAmtorderdiffTimediffQsix;
	
	/** 
	 * 1年内下单时间金额波动指标
	 **/
	private $oneyTsdescAmtorderdiffVamtRange;
	
	/** 
	 * 芝麻会员在商户端的身份标识。
	 **/
	private $openId;
	
	/** 
	 * 产品码
	 **/
	private $productCode;
	
	/** 
	 * 6月内特殊时间下波动性指标
	 **/
	private $sixmFdescCvHourCntorder;
	
	/** 
	 * 6月内在线支付总金额的占比
	 **/
	private $sixmGddifDivOnlinepaymentallSumPayAmtorder;
	
	/** 
	 * 6月内电子产品类目占比
	 **/
	private $sixmGddifDivPhonedigitalallNCntprdcategory;
	
	/** 
	 * 6月内特殊下单量的占比
	 **/
	private $sixmGddifDivSixmallNHourtwefourteenCntorder;
	
	/** 
	 * 6月内短时间下单占比
	 **/
	private $sixmGddifDivideFiveeightallNHourCntorder;
	
	/** 
	 * 6月内异常商品占比
	 **/
	private $sixmGddifMinusCaSumAorderCntproduct;
	
	/** 
	 * 6月内收货地址平均下单量的差异性指标
	 **/
	private $sixmGdescMinRecaddrcityAvgamt;
	
	/** 
	 * 6月内收货地址稳定性指标
	 **/
	private $sixmGdescRangeRecaddrprovinceAvgamt;
	
	/** 
	 * 用户活动期间的下单系数
	 **/
	private $springfestivalGdescQuAamt;
	
	/** 
	 * 3个月内特殊时段购买能力
	 **/
	private $threemFddifMinusSevenaQdHourbinAmtaorder;
	
	/** 
	 * 3月内特殊用途商品占比
	 **/
	private $threemGddifDivTravelrechargeallNCntprdcateg;
	
	/** 
	 * 3月内异常订单占比
	 **/
	private $threemGddifDivideFailallNStsCntorder;
	
	/** 
	 * 3月内金额总和异常占比
	 **/
	private $threemGddifDivideNullallSumPayAmtorder;
	
	/** 
	 * 3月内特殊订单金额指标
	 **/
	private $threemGdescSumSorderAmtaorder;
	
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

	
	public function setAllFddifDivideSixaQdHourbinAmtaorder($allFddifDivideSixaQdHourbinAmtaorder)
	{
		$this->allFddifDivideSixaQdHourbinAmtaorder = $allFddifDivideSixaQdHourbinAmtaorder;
		$this->apiParas["all_fddif_divide_sixa_qd_hourbin_amtaorder"] = $allFddifDivideSixaQdHourbinAmtaorder;
	}

	public function getAllFddifDivideSixaQdHourbinAmtaorder()
	{
		return $this->allFddifDivideSixaQdHourbinAmtaorder;
	}

	public function setAllFddifMinusFiveaRangeHourbinAmtaorder($allFddifMinusFiveaRangeHourbinAmtaorder)
	{
		$this->allFddifMinusFiveaRangeHourbinAmtaorder = $allFddifMinusFiveaRangeHourbinAmtaorder;
		$this->apiParas["all_fddif_minus_fivea_range_hourbin_amtaorder"] = $allFddifMinusFiveaRangeHourbinAmtaorder;
	}

	public function getAllFddifMinusFiveaRangeHourbinAmtaorder()
	{
		return $this->allFddifMinusFiveaRangeHourbinAmtaorder;
	}

	public function setAllFddifMinusTwoaSdHourbinAmtaorder($allFddifMinusTwoaSdHourbinAmtaorder)
	{
		$this->allFddifMinusTwoaSdHourbinAmtaorder = $allFddifMinusTwoaSdHourbinAmtaorder;
		$this->apiParas["all_fddif_minus_twoa_sd_hourbin_amtaorder"] = $allFddifMinusTwoaSdHourbinAmtaorder;
	}

	public function getAllFddifMinusTwoaSdHourbinAmtaorder()
	{
		return $this->allFddifMinusTwoaSdHourbinAmtaorder;
	}

	public function setAllFdescMeanPayonlinepaymentAmtorder($allFdescMeanPayonlinepaymentAmtorder)
	{
		$this->allFdescMeanPayonlinepaymentAmtorder = $allFdescMeanPayonlinepaymentAmtorder;
		$this->apiParas["all_fdesc_mean_payonlinepayment_amtorder"] = $allFdescMeanPayonlinepaymentAmtorder;
	}

	public function getAllFdescMeanPayonlinepaymentAmtorder()
	{
		return $this->allFdescMeanPayonlinepaymentAmtorder;
	}

	public function setAllGddescMinusLoantimenowMaxDaydiff($allGddescMinusLoantimenowMaxDaydiff)
	{
		$this->allGddescMinusLoantimenowMaxDaydiff = $allGddescMinusLoantimenowMaxDaydiff;
		$this->apiParas["all_gddesc_minus_loantimenow_max_daydiff"] = $allGddescMinusLoantimenowMaxDaydiff;
	}

	public function getAllGddescMinusLoantimenowMaxDaydiff()
	{
		return $this->allGddescMinusLoantimenowMaxDaydiff;
	}

	public function setAllGddescMinusLoantimenowMinHourdiff($allGddescMinusLoantimenowMinHourdiff)
	{
		$this->allGddescMinusLoantimenowMinHourdiff = $allGddescMinusLoantimenowMinHourdiff;
		$this->apiParas["all_gddesc_minus_loantimenow_min_hourdiff"] = $allGddescMinusLoantimenowMinHourdiff;
	}

	public function getAllGddescMinusLoantimenowMinHourdiff()
	{
		return $this->allGddescMinusLoantimenowMinHourdiff;
	}

	public function setAllGddifDivCashondeliveryallSumPayAmtorder($allGddifDivCashondeliveryallSumPayAmtorder)
	{
		$this->allGddifDivCashondeliveryallSumPayAmtorder = $allGddifDivCashondeliveryallSumPayAmtorder;
		$this->apiParas["all_gddif_div_cashondeliveryall_sum_pay_amtorder"] = $allGddifDivCashondeliveryallSumPayAmtorder;
	}

	public function getAllGddifDivCashondeliveryallSumPayAmtorder()
	{
		return $this->allGddifDivCashondeliveryallSumPayAmtorder;
	}

	public function setAllGddifDivOnlinepaymentallSumPayAmtorder($allGddifDivOnlinepaymentallSumPayAmtorder)
	{
		$this->allGddifDivOnlinepaymentallSumPayAmtorder = $allGddifDivOnlinepaymentallSumPayAmtorder;
		$this->apiParas["all_gddif_div_onlinepaymentall_sum_pay_amtorder"] = $allGddifDivOnlinepaymentallSumPayAmtorder;
	}

	public function getAllGddifDivOnlinepaymentallSumPayAmtorder()
	{
		return $this->allGddifDivOnlinepaymentallSumPayAmtorder;
	}

	public function setAllGddifDivSportsoutdoorallNCntprdcategory($allGddifDivSportsoutdoorallNCntprdcategory)
	{
		$this->allGddifDivSportsoutdoorallNCntprdcategory = $allGddifDivSportsoutdoorallNCntprdcategory;
		$this->apiParas["all_gddif_div_sportsoutdoorall_n_cntprdcategory"] = $allGddifDivSportsoutdoorallNCntprdcategory;
	}

	public function getAllGddifDivSportsoutdoorallNCntprdcategory()
	{
		return $this->allGddifDivSportsoutdoorallNCntprdcategory;
	}

	public function setAllGddifDivideFailallNStsCntorder($allGddifDivideFailallNStsCntorder)
	{
		$this->allGddifDivideFailallNStsCntorder = $allGddifDivideFailallNStsCntorder;
		$this->apiParas["all_gddif_divide_failall_n_sts_cntorder"] = $allGddifDivideFailallNStsCntorder;
	}

	public function getAllGddifDivideFailallNStsCntorder()
	{
		return $this->allGddifDivideFailallNStsCntorder;
	}

	public function setAllGddifDivideFiveeightallNHourCntorder($allGddifDivideFiveeightallNHourCntorder)
	{
		$this->allGddifDivideFiveeightallNHourCntorder = $allGddifDivideFiveeightallNHourCntorder;
		$this->apiParas["all_gddif_divide_fiveeightall_n_hour_cntorder"] = $allGddifDivideFiveeightallNHourCntorder;
	}

	public function getAllGddifDivideFiveeightallNHourCntorder()
	{
		return $this->allGddifDivideFiveeightallNHourCntorder;
	}

	public function setAllGddifDividePhonedigitalallNCntprdcategory($allGddifDividePhonedigitalallNCntprdcategory)
	{
		$this->allGddifDividePhonedigitalallNCntprdcategory = $allGddifDividePhonedigitalallNCntprdcategory;
		$this->apiParas["all_gddif_divide_phonedigitalall_n_cntprdcategory"] = $allGddifDividePhonedigitalallNCntprdcategory;
	}

	public function getAllGddifDividePhonedigitalallNCntprdcategory()
	{
		return $this->allGddifDividePhonedigitalallNCntprdcategory;
	}

	public function setAllGddifMinusCaMaxProductCntaorder($allGddifMinusCaMaxProductCntaorder)
	{
		$this->allGddifMinusCaMaxProductCntaorder = $allGddifMinusCaMaxProductCntaorder;
		$this->apiParas["all_gddif_minus_ca_max_product_cntaorder"] = $allGddifMinusCaMaxProductCntaorder;
	}

	public function getAllGddifMinusCaMaxProductCntaorder()
	{
		return $this->allGddifMinusCaMaxProductCntaorder;
	}

	public function setAllGddifMinusCaSumAorderCntproduct($allGddifMinusCaSumAorderCntproduct)
	{
		$this->allGddifMinusCaSumAorderCntproduct = $allGddifMinusCaSumAorderCntproduct;
		$this->apiParas["all_gddif_minus_ca_sum_aorder_cntproduct"] = $allGddifMinusCaSumAorderCntproduct;
	}

	public function getAllGddifMinusCaSumAorderCntproduct()
	{
		return $this->allGddifMinusCaSumAorderCntproduct;
	}

	public function setAllGddifMinusCsMedianProductCntaorder($allGddifMinusCsMedianProductCntaorder)
	{
		$this->allGddifMinusCsMedianProductCntaorder = $allGddifMinusCsMedianProductCntaorder;
		$this->apiParas["all_gddif_minus_cs_median_product_cntaorder"] = $allGddifMinusCsMedianProductCntaorder;
	}

	public function getAllGddifMinusCsMedianProductCntaorder()
	{
		return $this->allGddifMinusCsMedianProductCntaorder;
	}

	public function setAllGddifMinusCsSkewAorderAmtaorder($allGddifMinusCsSkewAorderAmtaorder)
	{
		$this->allGddifMinusCsSkewAorderAmtaorder = $allGddifMinusCsSkewAorderAmtaorder;
		$this->apiParas["all_gddif_minus_cs_skew_aorder_amtaorder"] = $allGddifMinusCsSkewAorderAmtaorder;
	}

	public function getAllGddifMinusCsSkewAorderAmtaorder()
	{
		return $this->allGddifMinusCsSkewAorderAmtaorder;
	}

	public function setAllGddifMinusSaEntropyAorderCntproduct($allGddifMinusSaEntropyAorderCntproduct)
	{
		$this->allGddifMinusSaEntropyAorderCntproduct = $allGddifMinusSaEntropyAorderCntproduct;
		$this->apiParas["all_gddif_minus_sa_entropy_aorder_cntproduct"] = $allGddifMinusSaEntropyAorderCntproduct;
	}

	public function getAllGddifMinusSaEntropyAorderCntproduct()
	{
		return $this->allGddifMinusSaEntropyAorderCntproduct;
	}

	public function setAllGddifMinusSaSumAorderCntproduct($allGddifMinusSaSumAorderCntproduct)
	{
		$this->allGddifMinusSaSumAorderCntproduct = $allGddifMinusSaSumAorderCntproduct;
		$this->apiParas["all_gddif_minus_sa_sum_aorder_cntproduct"] = $allGddifMinusSaSumAorderCntproduct;
	}

	public function getAllGddifMinusSaSumAorderCntproduct()
	{
		return $this->allGddifMinusSaSumAorderCntproduct;
	}

	public function setAllGddifMinusSaSumProductCntaorder($allGddifMinusSaSumProductCntaorder)
	{
		$this->allGddifMinusSaSumProductCntaorder = $allGddifMinusSaSumProductCntaorder;
		$this->apiParas["all_gddif_minus_sa_sum_product_cntaorder"] = $allGddifMinusSaSumProductCntaorder;
	}

	public function getAllGddifMinusSaSumProductCntaorder()
	{
		return $this->allGddifMinusSaSumProductCntaorder;
	}

	public function setAllGdescMeanProductCntaorder($allGdescMeanProductCntaorder)
	{
		$this->allGdescMeanProductCntaorder = $allGdescMeanProductCntaorder;
		$this->apiParas["all_gdesc_mean_product_cntaorder"] = $allGdescMeanProductCntaorder;
	}

	public function getAllGdescMeanProductCntaorder()
	{
		return $this->allGdescMeanProductCntaorder;
	}

	public function setAllGdescMeanSorderAmtaorder($allGdescMeanSorderAmtaorder)
	{
		$this->allGdescMeanSorderAmtaorder = $allGdescMeanSorderAmtaorder;
		$this->apiParas["all_gdesc_mean_sorder_amtaorder"] = $allGdescMeanSorderAmtaorder;
	}

	public function getAllGdescMeanSorderAmtaorder()
	{
		return $this->allGdescMeanSorderAmtaorder;
	}

	public function setAllGdescMinCorderAmtaorder($allGdescMinCorderAmtaorder)
	{
		$this->allGdescMinCorderAmtaorder = $allGdescMinCorderAmtaorder;
		$this->apiParas["all_gdesc_min_corder_amtaorder"] = $allGdescMinCorderAmtaorder;
	}

	public function getAllGdescMinCorderAmtaorder()
	{
		return $this->allGdescMinCorderAmtaorder;
	}

	public function setAllGdescMinPhoneSumamt($allGdescMinPhoneSumamt)
	{
		$this->allGdescMinPhoneSumamt = $allGdescMinPhoneSumamt;
		$this->apiParas["all_gdesc_min_phone_sumamt"] = $allGdescMinPhoneSumamt;
	}

	public function getAllGdescMinPhoneSumamt()
	{
		return $this->allGdescMinPhoneSumamt;
	}

	public function setAllGdescMinRecaddrcitySumamt($allGdescMinRecaddrcitySumamt)
	{
		$this->allGdescMinRecaddrcitySumamt = $allGdescMinRecaddrcitySumamt;
		$this->apiParas["all_gdesc_min_recaddrcity_sumamt"] = $allGdescMinRecaddrcitySumamt;
	}

	public function getAllGdescMinRecaddrcitySumamt()
	{
		return $this->allGdescMinRecaddrcitySumamt;
	}

	public function setAllGdescMinRecaddrprovinceAvgamt($allGdescMinRecaddrprovinceAvgamt)
	{
		$this->allGdescMinRecaddrprovinceAvgamt = $allGdescMinRecaddrprovinceAvgamt;
		$this->apiParas["all_gdesc_min_recaddrprovince_avgamt"] = $allGdescMinRecaddrprovinceAvgamt;
	}

	public function getAllGdescMinRecaddrprovinceAvgamt()
	{
		return $this->allGdescMinRecaddrprovinceAvgamt;
	}

	public function setAllGdescNormentropyPhoneCntorder($allGdescNormentropyPhoneCntorder)
	{
		$this->allGdescNormentropyPhoneCntorder = $allGdescNormentropyPhoneCntorder;
		$this->apiParas["all_gdesc_normentropy_phone_cntorder"] = $allGdescNormentropyPhoneCntorder;
	}

	public function getAllGdescNormentropyPhoneCntorder()
	{
		return $this->allGdescNormentropyPhoneCntorder;
	}

	public function setAllGdescNormentropyProductCntsorder($allGdescNormentropyProductCntsorder)
	{
		$this->allGdescNormentropyProductCntsorder = $allGdescNormentropyProductCntsorder;
		$this->apiParas["all_gdesc_normentropy_product_cntsorder"] = $allGdescNormentropyProductCntsorder;
	}

	public function getAllGdescNormentropyProductCntsorder()
	{
		return $this->allGdescNormentropyProductCntsorder;
	}

	public function setAllGdescQlSorderAmtaorder($allGdescQlSorderAmtaorder)
	{
		$this->allGdescQlSorderAmtaorder = $allGdescQlSorderAmtaorder;
		$this->apiParas["all_gdesc_ql_sorder_amtaorder"] = $allGdescQlSorderAmtaorder;
	}

	public function getAllGdescQlSorderAmtaorder()
	{
		return $this->allGdescQlSorderAmtaorder;
	}

	public function setAllTsdescAmtorderdiffAmtdiffMedian($allTsdescAmtorderdiffAmtdiffMedian)
	{
		$this->allTsdescAmtorderdiffAmtdiffMedian = $allTsdescAmtorderdiffAmtdiffMedian;
		$this->apiParas["all_tsdesc_amtorderdiff_amtdiff_median"] = $allTsdescAmtorderdiffAmtdiffMedian;
	}

	public function getAllTsdescAmtorderdiffAmtdiffMedian()
	{
		return $this->allTsdescAmtorderdiffAmtdiffMedian;
	}

	public function setAllTsdescAmtorderdiffAmtdiffQu($allTsdescAmtorderdiffAmtdiffQu)
	{
		$this->allTsdescAmtorderdiffAmtdiffQu = $allTsdescAmtorderdiffAmtdiffQu;
		$this->apiParas["all_tsdesc_amtorderdiff_amtdiff_qu"] = $allTsdescAmtorderdiffAmtdiffQu;
	}

	public function getAllTsdescAmtorderdiffAmtdiffQu()
	{
		return $this->allTsdescAmtorderdiffAmtdiffQu;
	}

	public function setAllTsdescAmtorderdiffAmtdiffSum($allTsdescAmtorderdiffAmtdiffSum)
	{
		$this->allTsdescAmtorderdiffAmtdiffSum = $allTsdescAmtorderdiffAmtdiffSum;
		$this->apiParas["all_tsdesc_amtorderdiff_amtdiff_sum"] = $allTsdescAmtorderdiffAmtdiffSum;
	}

	public function getAllTsdescAmtorderdiffAmtdiffSum()
	{
		return $this->allTsdescAmtorderdiffAmtdiffSum;
	}

	public function setAllTsdescAmtorderdiffTimediffCv($allTsdescAmtorderdiffTimediffCv)
	{
		$this->allTsdescAmtorderdiffTimediffCv = $allTsdescAmtorderdiffTimediffCv;
		$this->apiParas["all_tsdesc_amtorderdiff_timediff_cv"] = $allTsdescAmtorderdiffTimediffCv;
	}

	public function getAllTsdescAmtorderdiffTimediffCv()
	{
		return $this->allTsdescAmtorderdiffTimediffCv;
	}

	public function setAllTsdescAmtorderdiffTimediffQfour($allTsdescAmtorderdiffTimediffQfour)
	{
		$this->allTsdescAmtorderdiffTimediffQfour = $allTsdescAmtorderdiffTimediffQfour;
		$this->apiParas["all_tsdesc_amtorderdiff_timediff_qfour"] = $allTsdescAmtorderdiffTimediffQfour;
	}

	public function getAllTsdescAmtorderdiffTimediffQfour()
	{
		return $this->allTsdescAmtorderdiffTimediffQfour;
	}

	public function setAllTsdescAmtorderdiffTimediffQsix($allTsdescAmtorderdiffTimediffQsix)
	{
		$this->allTsdescAmtorderdiffTimediffQsix = $allTsdescAmtorderdiffTimediffQsix;
		$this->apiParas["all_tsdesc_amtorderdiff_timediff_qsix"] = $allTsdescAmtorderdiffTimediffQsix;
	}

	public function getAllTsdescAmtorderdiffTimediffQsix()
	{
		return $this->allTsdescAmtorderdiffTimediffQsix;
	}

	public function setAllTsdescAmtorderdiffTimediffQu($allTsdescAmtorderdiffTimediffQu)
	{
		$this->allTsdescAmtorderdiffTimediffQu = $allTsdescAmtorderdiffTimediffQu;
		$this->apiParas["all_tsdesc_amtorderdiff_timediff_qu"] = $allTsdescAmtorderdiffTimediffQu;
	}

	public function getAllTsdescAmtorderdiffTimediffQu()
	{
		return $this->allTsdescAmtorderdiffTimediffQu;
	}

	public function setAllTsdescAmtorderdiffVamtQnine($allTsdescAmtorderdiffVamtQnine)
	{
		$this->allTsdescAmtorderdiffVamtQnine = $allTsdescAmtorderdiffVamtQnine;
		$this->apiParas["all_tsdesc_amtorderdiff_vamt_qnine"] = $allTsdescAmtorderdiffVamtQnine;
	}

	public function getAllTsdescAmtorderdiffVamtQnine()
	{
		return $this->allTsdescAmtorderdiffVamtQnine;
	}

	public function setJdauthFddescExistChannelfinanceAuth($jdauthFddescExistChannelfinanceAuth)
	{
		$this->jdauthFddescExistChannelfinanceAuth = $jdauthFddescExistChannelfinanceAuth;
		$this->apiParas["jdauth_fddesc_exist_channelfinance_auth"] = $jdauthFddescExistChannelfinanceAuth;
	}

	public function getJdauthFddescExistChannelfinanceAuth()
	{
		return $this->jdauthFddescExistChannelfinanceAuth;
	}

	public function setJdauthFddescExistLoginnameEqualPhone($jdauthFddescExistLoginnameEqualPhone)
	{
		$this->jdauthFddescExistLoginnameEqualPhone = $jdauthFddescExistLoginnameEqualPhone;
		$this->apiParas["jdauth_fddesc_exist_loginname_equal_phone"] = $jdauthFddescExistLoginnameEqualPhone;
	}

	public function getJdauthFddescExistLoginnameEqualPhone()
	{
		return $this->jdauthFddescExistLoginnameEqualPhone;
	}

	public function setJdauthFddescMinusNowauthtimeSeconds($jdauthFddescMinusNowauthtimeSeconds)
	{
		$this->jdauthFddescMinusNowauthtimeSeconds = $jdauthFddescMinusNowauthtimeSeconds;
		$this->apiParas["jdauth_fddesc_minus_nowauthtime_seconds"] = $jdauthFddescMinusNowauthtimeSeconds;
	}

	public function getJdauthFddescMinusNowauthtimeSeconds()
	{
		return $this->jdauthFddescMinusNowauthtimeSeconds;
	}

	public function setJdbankcardDescDivideNOwnernameReceiver($jdbankcardDescDivideNOwnernameReceiver)
	{
		$this->jdbankcardDescDivideNOwnernameReceiver = $jdbankcardDescDivideNOwnernameReceiver;
		$this->apiParas["jdbankcard_desc_divide_n_ownername_receiver"] = $jdbankcardDescDivideNOwnernameReceiver;
	}

	public function getJdbankcardDescDivideNOwnernameReceiver()
	{
		return $this->jdbankcardDescDivideNOwnernameReceiver;
	}

	public function setJdbankcardDescNBankphoneAuthphone($jdbankcardDescNBankphoneAuthphone)
	{
		$this->jdbankcardDescNBankphoneAuthphone = $jdbankcardDescNBankphoneAuthphone;
		$this->apiParas["jdbankcard_desc_n_bankphone_authphone"] = $jdbankcardDescNBankphoneAuthphone;
	}

	public function getJdbankcardDescNBankphoneAuthphone()
	{
		return $this->jdbankcardDescNBankphoneAuthphone;
	}

	public function setJdbankcardDescNOwnernameReceiver($jdbankcardDescNOwnernameReceiver)
	{
		$this->jdbankcardDescNOwnernameReceiver = $jdbankcardDescNOwnernameReceiver;
		$this->apiParas["jdbankcard_desc_n_ownername_receiver"] = $jdbankcardDescNOwnernameReceiver;
	}

	public function getJdbankcardDescNOwnernameReceiver()
	{
		return $this->jdbankcardDescNOwnernameReceiver;
	}

	public function setJdbankcardDiffDivideNndBindphone($jdbankcardDiffDivideNndBindphone)
	{
		$this->jdbankcardDiffDivideNndBindphone = $jdbankcardDiffDivideNndBindphone;
		$this->apiParas["jdbankcard_diff_divide_nnd_bindphone"] = $jdbankcardDiffDivideNndBindphone;
	}

	public function getJdbankcardDiffDivideNndBindphone()
	{
		return $this->jdbankcardDiffDivideNndBindphone;
	}

	public function setJdbankcardFdescNBanknameMajorfourbanks($jdbankcardFdescNBanknameMajorfourbanks)
	{
		$this->jdbankcardFdescNBanknameMajorfourbanks = $jdbankcardFdescNBanknameMajorfourbanks;
		$this->apiParas["jdbankcard_fdesc_n_bankname_majorfourbanks"] = $jdbankcardFdescNBanknameMajorfourbanks;
	}

	public function getJdbankcardFdescNBanknameMajorfourbanks()
	{
		return $this->jdbankcardFdescNBanknameMajorfourbanks;
	}

	public function setJdbankcardFdescNBanknameOthers($jdbankcardFdescNBanknameOthers)
	{
		$this->jdbankcardFdescNBanknameOthers = $jdbankcardFdescNBanknameOthers;
		$this->apiParas["jdbankcard_fdesc_n_bankname_others"] = $jdbankcardFdescNBanknameOthers;
	}

	public function getJdbankcardFdescNBanknameOthers()
	{
		return $this->jdbankcardFdescNBanknameOthers;
	}

	public function setJdbankcardFdiffDivideAbcallCntbankname($jdbankcardFdiffDivideAbcallCntbankname)
	{
		$this->jdbankcardFdiffDivideAbcallCntbankname = $jdbankcardFdiffDivideAbcallCntbankname;
		$this->apiParas["jdbankcard_fdiff_divide_abcall_cntbankname"] = $jdbankcardFdiffDivideAbcallCntbankname;
	}

	public function getJdbankcardFdiffDivideAbcallCntbankname()
	{
		return $this->jdbankcardFdiffDivideAbcallCntbankname;
	}

	public function setJdbankcardFdiffDivideCreditallCntcardtype($jdbankcardFdiffDivideCreditallCntcardtype)
	{
		$this->jdbankcardFdiffDivideCreditallCntcardtype = $jdbankcardFdiffDivideCreditallCntcardtype;
		$this->apiParas["jdbankcard_fdiff_divide_creditall_cntcardtype"] = $jdbankcardFdiffDivideCreditallCntcardtype;
	}

	public function getJdbankcardFdiffDivideCreditallCntcardtype()
	{
		return $this->jdbankcardFdiffDivideCreditallCntcardtype;
	}

	public function setJdbankcardFdiffDividePostallCntbankname($jdbankcardFdiffDividePostallCntbankname)
	{
		$this->jdbankcardFdiffDividePostallCntbankname = $jdbankcardFdiffDividePostallCntbankname;
		$this->apiParas["jdbankcard_fdiff_divide_postall_cntbankname"] = $jdbankcardFdiffDividePostallCntbankname;
	}

	public function getJdbankcardFdiffDividePostallCntbankname()
	{
		return $this->jdbankcardFdiffDividePostallCntbankname;
	}

	public function setJdbtGddescExtractCreditscoreBt($jdbtGddescExtractCreditscoreBt)
	{
		$this->jdbtGddescExtractCreditscoreBt = $jdbtGddescExtractCreditscoreBt;
		$this->apiParas["jdbt_gddesc_extract_creditscore_bt"] = $jdbtGddescExtractCreditscoreBt;
	}

	public function getJdbtGddescExtractCreditscoreBt()
	{
		return $this->jdbtGddescExtractCreditscoreBt;
	}

	public function setJdbtGddiffMinusOverdraftquotaBtAmt($jdbtGddiffMinusOverdraftquotaBtAmt)
	{
		$this->jdbtGddiffMinusOverdraftquotaBtAmt = $jdbtGddiffMinusOverdraftquotaBtAmt;
		$this->apiParas["jdbt_gddiff_minus_overdraftquota_bt_amt"] = $jdbtGddiffMinusOverdraftquotaBtAmt;
	}

	public function getJdbtGddiffMinusOverdraftquotaBtAmt()
	{
		return $this->jdbtGddiffMinusOverdraftquotaBtAmt;
	}

	public function setJdoneoneoneonesumGdescAmt($jdoneoneoneonesumGdescAmt)
	{
		$this->jdoneoneoneonesumGdescAmt = $jdoneoneoneonesumGdescAmt;
		$this->apiParas["jdoneoneoneonesum_gdesc_amt"] = $jdoneoneoneonesumGdescAmt;
	}

	public function getJdoneoneoneonesumGdescAmt()
	{
		return $this->jdoneoneoneonesumGdescAmt;
	}

	public function setJdreceivaddrDescNAddress($jdreceivaddrDescNAddress)
	{
		$this->jdreceivaddrDescNAddress = $jdreceivaddrDescNAddress;
		$this->apiParas["jdreceivaddr_desc_n_address"] = $jdreceivaddrDescNAddress;
	}

	public function getJdreceivaddrDescNAddress()
	{
		return $this->jdreceivaddrDescNAddress;
	}

	public function setJdreceivaddrDescNNaemail($jdreceivaddrDescNNaemail)
	{
		$this->jdreceivaddrDescNNaemail = $jdreceivaddrDescNNaemail;
		$this->apiParas["jdreceivaddr_desc_n_naemail"] = $jdreceivaddrDescNNaemail;
	}

	public function getJdreceivaddrDescNNaemail()
	{
		return $this->jdreceivaddrDescNNaemail;
	}

	public function setJdreceivaddrDescRateNafixphone($jdreceivaddrDescRateNafixphone)
	{
		$this->jdreceivaddrDescRateNafixphone = $jdreceivaddrDescRateNafixphone;
		$this->apiParas["jdreceivaddr_desc_rate_nafixphone"] = $jdreceivaddrDescRateNafixphone;
	}

	public function getJdreceivaddrDescRateNafixphone()
	{
		return $this->jdreceivaddrDescRateNafixphone;
	}

	public function setJdsixoneeightsumGdescAmt($jdsixoneeightsumGdescAmt)
	{
		$this->jdsixoneeightsumGdescAmt = $jdsixoneeightsumGdescAmt;
		$this->apiParas["jdsixoneeightsum_gdesc_amt"] = $jdsixoneeightsumGdescAmt;
	}

	public function getJdsixoneeightsumGdescAmt()
	{
		return $this->jdsixoneeightsumGdescAmt;
	}

	public function setJduserFddescExistWebloginnameLogname($jduserFddescExistWebloginnameLogname)
	{
		$this->jduserFddescExistWebloginnameLogname = $jduserFddescExistWebloginnameLogname;
		$this->apiParas["jduser_fddesc_exist_webloginname_logname"] = $jduserFddescExistWebloginnameLogname;
	}

	public function getJduserFddescExistWebloginnameLogname()
	{
		return $this->jduserFddescExistWebloginnameLogname;
	}

	public function setJduserFddescNdCompareThreenames($jduserFddescNdCompareThreenames)
	{
		$this->jduserFddescNdCompareThreenames = $jduserFddescNdCompareThreenames;
		$this->apiParas["jduser_fddesc_nd_compare_threenames"] = $jduserFddescNdCompareThreenames;
	}

	public function getJduserFddescNdCompareThreenames()
	{
		return $this->jduserFddescNdCompareThreenames;
	}

	public function setJduserIsbindBothqqwechat($jduserIsbindBothqqwechat)
	{
		$this->jduserIsbindBothqqwechat = $jduserIsbindBothqqwechat;
		$this->apiParas["jduser_isbind_bothqqwechat"] = $jduserIsbindBothqqwechat;
	}

	public function getJduserIsbindBothqqwechat()
	{
		return $this->jduserIsbindBothqqwechat;
	}

	public function setOneyFddifDivideSevenaRangeHourbinAmtaorder($oneyFddifDivideSevenaRangeHourbinAmtaorder)
	{
		$this->oneyFddifDivideSevenaRangeHourbinAmtaorder = $oneyFddifDivideSevenaRangeHourbinAmtaorder;
		$this->apiParas["oney_fddif_divide_sevena_range_hourbin_amtaorder"] = $oneyFddifDivideSevenaRangeHourbinAmtaorder;
	}

	public function getOneyFddifDivideSevenaRangeHourbinAmtaorder()
	{
		return $this->oneyFddifDivideSevenaRangeHourbinAmtaorder;
	}

	public function setOneyFddifMinusOneaRangeHourbinAmtaorder($oneyFddifMinusOneaRangeHourbinAmtaorder)
	{
		$this->oneyFddifMinusOneaRangeHourbinAmtaorder = $oneyFddifMinusOneaRangeHourbinAmtaorder;
		$this->apiParas["oney_fddif_minus_onea_range_hourbin_amtaorder"] = $oneyFddifMinusOneaRangeHourbinAmtaorder;
	}

	public function getOneyFddifMinusOneaRangeHourbinAmtaorder()
	{
		return $this->oneyFddifMinusOneaRangeHourbinAmtaorder;
	}

	public function setOneyFddifMinusSixaRangeHourbinAmtaorder($oneyFddifMinusSixaRangeHourbinAmtaorder)
	{
		$this->oneyFddifMinusSixaRangeHourbinAmtaorder = $oneyFddifMinusSixaRangeHourbinAmtaorder;
		$this->apiParas["oney_fddif_minus_sixa_range_hourbin_amtaorder"] = $oneyFddifMinusSixaRangeHourbinAmtaorder;
	}

	public function getOneyFddifMinusSixaRangeHourbinAmtaorder()
	{
		return $this->oneyFddifMinusSixaRangeHourbinAmtaorder;
	}

	public function setOneyFdescMeanPaycashondeliveryAmtorder($oneyFdescMeanPaycashondeliveryAmtorder)
	{
		$this->oneyFdescMeanPaycashondeliveryAmtorder = $oneyFdescMeanPaycashondeliveryAmtorder;
		$this->apiParas["oney_fdesc_mean_paycashondelivery_amtorder"] = $oneyFdescMeanPaycashondeliveryAmtorder;
	}

	public function getOneyFdescMeanPaycashondeliveryAmtorder()
	{
		return $this->oneyFdescMeanPaycashondeliveryAmtorder;
	}

	public function setOneyFdescSumMeaninvoicecontentAmtorder($oneyFdescSumMeaninvoicecontentAmtorder)
	{
		$this->oneyFdescSumMeaninvoicecontentAmtorder = $oneyFdescSumMeaninvoicecontentAmtorder;
		$this->apiParas["oney_fdesc_sum_meaninvoicecontent_amtorder"] = $oneyFdescSumMeaninvoicecontentAmtorder;
	}

	public function getOneyFdescSumMeaninvoicecontentAmtorder()
	{
		return $this->oneyFdescSumMeaninvoicecontentAmtorder;
	}

	public function setOneyGddifDivOnlinepaymentallSumPayAmtorder($oneyGddifDivOnlinepaymentallSumPayAmtorder)
	{
		$this->oneyGddifDivOnlinepaymentallSumPayAmtorder = $oneyGddifDivOnlinepaymentallSumPayAmtorder;
		$this->apiParas["oney_gddif_div_onlinepaymentall_sum_pay_amtorder"] = $oneyGddifDivOnlinepaymentallSumPayAmtorder;
	}

	public function getOneyGddifDivOnlinepaymentallSumPayAmtorder()
	{
		return $this->oneyGddifDivOnlinepaymentallSumPayAmtorder;
	}

	public function setOneyGddifMinusCaMedianAorderAmtaorder($oneyGddifMinusCaMedianAorderAmtaorder)
	{
		$this->oneyGddifMinusCaMedianAorderAmtaorder = $oneyGddifMinusCaMedianAorderAmtaorder;
		$this->apiParas["oney_gddif_minus_ca_median_aorder_amtaorder"] = $oneyGddifMinusCaMedianAorderAmtaorder;
	}

	public function getOneyGddifMinusCaMedianAorderAmtaorder()
	{
		return $this->oneyGddifMinusCaMedianAorderAmtaorder;
	}

	public function setOneyGddifMinusCaSdAmtbinAmtaorder($oneyGddifMinusCaSdAmtbinAmtaorder)
	{
		$this->oneyGddifMinusCaSdAmtbinAmtaorder = $oneyGddifMinusCaSdAmtbinAmtaorder;
		$this->apiParas["oney_gddif_minus_ca_sd_amtbin_amtaorder"] = $oneyGddifMinusCaSdAmtbinAmtaorder;
	}

	public function getOneyGddifMinusCaSdAmtbinAmtaorder()
	{
		return $this->oneyGddifMinusCaSdAmtbinAmtaorder;
	}

	public function setOneyGddifMinusCaSumAorderCntproduct($oneyGddifMinusCaSumAorderCntproduct)
	{
		$this->oneyGddifMinusCaSumAorderCntproduct = $oneyGddifMinusCaSumAorderCntproduct;
		$this->apiParas["oney_gddif_minus_ca_sum_aorder_cntproduct"] = $oneyGddifMinusCaSumAorderCntproduct;
	}

	public function getOneyGddifMinusCaSumAorderCntproduct()
	{
		return $this->oneyGddifMinusCaSumAorderCntproduct;
	}

	public function setOneyGddifMinusSaEntropyAmtbinAmtaorder($oneyGddifMinusSaEntropyAmtbinAmtaorder)
	{
		$this->oneyGddifMinusSaEntropyAmtbinAmtaorder = $oneyGddifMinusSaEntropyAmtbinAmtaorder;
		$this->apiParas["oney_gddif_minus_sa_entropy_amtbin_amtaorder"] = $oneyGddifMinusSaEntropyAmtbinAmtaorder;
	}

	public function getOneyGddifMinusSaEntropyAmtbinAmtaorder()
	{
		return $this->oneyGddifMinusSaEntropyAmtbinAmtaorder;
	}

	public function setOneyGdescCvRecaddrcityAvgamt($oneyGdescCvRecaddrcityAvgamt)
	{
		$this->oneyGdescCvRecaddrcityAvgamt = $oneyGdescCvRecaddrcityAvgamt;
		$this->apiParas["oney_gdesc_cv_recaddrcity_avgamt"] = $oneyGdescCvRecaddrcityAvgamt;
	}

	public function getOneyGdescCvRecaddrcityAvgamt()
	{
		return $this->oneyGdescCvRecaddrcityAvgamt;
	}

	public function setOneyGdescNormentropyAmtbinAmtsorder($oneyGdescNormentropyAmtbinAmtsorder)
	{
		$this->oneyGdescNormentropyAmtbinAmtsorder = $oneyGdescNormentropyAmtbinAmtsorder;
		$this->apiParas["oney_gdesc_normentropy_amtbin_amtsorder"] = $oneyGdescNormentropyAmtbinAmtsorder;
	}

	public function getOneyGdescNormentropyAmtbinAmtsorder()
	{
		return $this->oneyGdescNormentropyAmtbinAmtsorder;
	}

	public function setOneyTsdescAmtorderdiffTimediffQsix($oneyTsdescAmtorderdiffTimediffQsix)
	{
		$this->oneyTsdescAmtorderdiffTimediffQsix = $oneyTsdescAmtorderdiffTimediffQsix;
		$this->apiParas["oney_tsdesc_amtorderdiff_timediff_qsix"] = $oneyTsdescAmtorderdiffTimediffQsix;
	}

	public function getOneyTsdescAmtorderdiffTimediffQsix()
	{
		return $this->oneyTsdescAmtorderdiffTimediffQsix;
	}

	public function setOneyTsdescAmtorderdiffVamtRange($oneyTsdescAmtorderdiffVamtRange)
	{
		$this->oneyTsdescAmtorderdiffVamtRange = $oneyTsdescAmtorderdiffVamtRange;
		$this->apiParas["oney_tsdesc_amtorderdiff_vamt_range"] = $oneyTsdescAmtorderdiffVamtRange;
	}

	public function getOneyTsdescAmtorderdiffVamtRange()
	{
		return $this->oneyTsdescAmtorderdiffVamtRange;
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

	public function setSixmFdescCvHourCntorder($sixmFdescCvHourCntorder)
	{
		$this->sixmFdescCvHourCntorder = $sixmFdescCvHourCntorder;
		$this->apiParas["sixm_fdesc_cv_hour_cntorder"] = $sixmFdescCvHourCntorder;
	}

	public function getSixmFdescCvHourCntorder()
	{
		return $this->sixmFdescCvHourCntorder;
	}

	public function setSixmGddifDivOnlinepaymentallSumPayAmtorder($sixmGddifDivOnlinepaymentallSumPayAmtorder)
	{
		$this->sixmGddifDivOnlinepaymentallSumPayAmtorder = $sixmGddifDivOnlinepaymentallSumPayAmtorder;
		$this->apiParas["sixm_gddif_div_onlinepaymentall_sum_pay_amtorder"] = $sixmGddifDivOnlinepaymentallSumPayAmtorder;
	}

	public function getSixmGddifDivOnlinepaymentallSumPayAmtorder()
	{
		return $this->sixmGddifDivOnlinepaymentallSumPayAmtorder;
	}

	public function setSixmGddifDivPhonedigitalallNCntprdcategory($sixmGddifDivPhonedigitalallNCntprdcategory)
	{
		$this->sixmGddifDivPhonedigitalallNCntprdcategory = $sixmGddifDivPhonedigitalallNCntprdcategory;
		$this->apiParas["sixm_gddif_div_phonedigitalall_n_cntprdcategory"] = $sixmGddifDivPhonedigitalallNCntprdcategory;
	}

	public function getSixmGddifDivPhonedigitalallNCntprdcategory()
	{
		return $this->sixmGddifDivPhonedigitalallNCntprdcategory;
	}

	public function setSixmGddifDivSixmallNHourtwefourteenCntorder($sixmGddifDivSixmallNHourtwefourteenCntorder)
	{
		$this->sixmGddifDivSixmallNHourtwefourteenCntorder = $sixmGddifDivSixmallNHourtwefourteenCntorder;
		$this->apiParas["sixm_gddif_div_sixmall_n_hourtwefourteen_cntorder"] = $sixmGddifDivSixmallNHourtwefourteenCntorder;
	}

	public function getSixmGddifDivSixmallNHourtwefourteenCntorder()
	{
		return $this->sixmGddifDivSixmallNHourtwefourteenCntorder;
	}

	public function setSixmGddifDivideFiveeightallNHourCntorder($sixmGddifDivideFiveeightallNHourCntorder)
	{
		$this->sixmGddifDivideFiveeightallNHourCntorder = $sixmGddifDivideFiveeightallNHourCntorder;
		$this->apiParas["sixm_gddif_divide_fiveeightall_n_hour_cntorder"] = $sixmGddifDivideFiveeightallNHourCntorder;
	}

	public function getSixmGddifDivideFiveeightallNHourCntorder()
	{
		return $this->sixmGddifDivideFiveeightallNHourCntorder;
	}

	public function setSixmGddifMinusCaSumAorderCntproduct($sixmGddifMinusCaSumAorderCntproduct)
	{
		$this->sixmGddifMinusCaSumAorderCntproduct = $sixmGddifMinusCaSumAorderCntproduct;
		$this->apiParas["sixm_gddif_minus_ca_sum_aorder_cntproduct"] = $sixmGddifMinusCaSumAorderCntproduct;
	}

	public function getSixmGddifMinusCaSumAorderCntproduct()
	{
		return $this->sixmGddifMinusCaSumAorderCntproduct;
	}

	public function setSixmGdescMinRecaddrcityAvgamt($sixmGdescMinRecaddrcityAvgamt)
	{
		$this->sixmGdescMinRecaddrcityAvgamt = $sixmGdescMinRecaddrcityAvgamt;
		$this->apiParas["sixm_gdesc_min_recaddrcity_avgamt"] = $sixmGdescMinRecaddrcityAvgamt;
	}

	public function getSixmGdescMinRecaddrcityAvgamt()
	{
		return $this->sixmGdescMinRecaddrcityAvgamt;
	}

	public function setSixmGdescRangeRecaddrprovinceAvgamt($sixmGdescRangeRecaddrprovinceAvgamt)
	{
		$this->sixmGdescRangeRecaddrprovinceAvgamt = $sixmGdescRangeRecaddrprovinceAvgamt;
		$this->apiParas["sixm_gdesc_range_recaddrprovince_avgamt"] = $sixmGdescRangeRecaddrprovinceAvgamt;
	}

	public function getSixmGdescRangeRecaddrprovinceAvgamt()
	{
		return $this->sixmGdescRangeRecaddrprovinceAvgamt;
	}

	public function setSpringfestivalGdescQuAamt($springfestivalGdescQuAamt)
	{
		$this->springfestivalGdescQuAamt = $springfestivalGdescQuAamt;
		$this->apiParas["springfestival_gdesc_qu_aamt"] = $springfestivalGdescQuAamt;
	}

	public function getSpringfestivalGdescQuAamt()
	{
		return $this->springfestivalGdescQuAamt;
	}

	public function setThreemFddifMinusSevenaQdHourbinAmtaorder($threemFddifMinusSevenaQdHourbinAmtaorder)
	{
		$this->threemFddifMinusSevenaQdHourbinAmtaorder = $threemFddifMinusSevenaQdHourbinAmtaorder;
		$this->apiParas["threem_fddif_minus_sevena_qd_hourbin_amtaorder"] = $threemFddifMinusSevenaQdHourbinAmtaorder;
	}

	public function getThreemFddifMinusSevenaQdHourbinAmtaorder()
	{
		return $this->threemFddifMinusSevenaQdHourbinAmtaorder;
	}

	public function setThreemGddifDivTravelrechargeallNCntprdcateg($threemGddifDivTravelrechargeallNCntprdcateg)
	{
		$this->threemGddifDivTravelrechargeallNCntprdcateg = $threemGddifDivTravelrechargeallNCntprdcateg;
		$this->apiParas["threem_gddif_div_travelrechargeall_n_cntprdcateg"] = $threemGddifDivTravelrechargeallNCntprdcateg;
	}

	public function getThreemGddifDivTravelrechargeallNCntprdcateg()
	{
		return $this->threemGddifDivTravelrechargeallNCntprdcateg;
	}

	public function setThreemGddifDivideFailallNStsCntorder($threemGddifDivideFailallNStsCntorder)
	{
		$this->threemGddifDivideFailallNStsCntorder = $threemGddifDivideFailallNStsCntorder;
		$this->apiParas["threem_gddif_divide_failall_n_sts_cntorder"] = $threemGddifDivideFailallNStsCntorder;
	}

	public function getThreemGddifDivideFailallNStsCntorder()
	{
		return $this->threemGddifDivideFailallNStsCntorder;
	}

	public function setThreemGddifDivideNullallSumPayAmtorder($threemGddifDivideNullallSumPayAmtorder)
	{
		$this->threemGddifDivideNullallSumPayAmtorder = $threemGddifDivideNullallSumPayAmtorder;
		$this->apiParas["threem_gddif_divide_nullall_sum_pay_amtorder"] = $threemGddifDivideNullallSumPayAmtorder;
	}

	public function getThreemGddifDivideNullallSumPayAmtorder()
	{
		return $this->threemGddifDivideNullallSumPayAmtorder;
	}

	public function setThreemGdescSumSorderAmtaorder($threemGdescSumSorderAmtaorder)
	{
		$this->threemGdescSumSorderAmtaorder = $threemGdescSumSorderAmtaorder;
		$this->apiParas["threem_gdesc_sum_sorder_amtaorder"] = $threemGdescSumSorderAmtaorder;
	}

	public function getThreemGdescSumSorderAmtaorder()
	{
		return $this->threemGdescSumSorderAmtaorder;
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
		return "zhima.credit.msxf.onlinejdscore.query";
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
