<?php
/**
 * ZHIMA API: zhima.customer.contract.initialize request
 *
 * @author auto create
 * @since 1.0, 2017-09-19 14:41:27
 */
class ZhimaCustomerContractInitializeRequest
{
	/** 
	 * 合约内容，PDF文件流，BASE64编码
	 **/
	private $contractFile;
	
	/** 
	 * 合约名称，展示给签约方
	 **/
	private $contractName;
	
	/** 
	 * 芝麻认证产品码,示例值为真实的产品码
	 **/
	private $productCode;

	private $apiParas = array();
	private $fileParas = array();
	private $apiVersion="1.0";
	private $scene;
	private $channel;
	private $platform;
	private $extParams;

	
	public function setContractFile($contractFile)
	{
		$this->contractFile = $contractFile;
		$this->apiParas["contract_file"] = $contractFile;
	}

	public function getContractFile()
	{
		return $this->contractFile;
	}

	public function setContractName($contractName)
	{
		$this->contractName = $contractName;
		$this->apiParas["contract_name"] = $contractName;
	}

	public function getContractName()
	{
		return $this->contractName;
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

	public function getApiMethodName()
	{
		return "zhima.customer.contract.initialize";
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
