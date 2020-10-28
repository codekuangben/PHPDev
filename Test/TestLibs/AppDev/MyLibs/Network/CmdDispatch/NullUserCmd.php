<?php

namespace SDK\Lib;

class NullUserCmd
{
	public const TIME_USERCMD = 2;
	public const DATA_USERCMD = 3;
	public const PROPERTY_USERCMD = 4;
	public const CHAT_USERCMD = 14;
	public const SELECT_USERCMD = 24;
	public const LOGON_USERCMD = 104;
	public const HERO_CARD_USERCMD = 162;

	public $byCmd;
	public $byParam;
	public $dwTimestamp;

	// 二进制序列化
	public function serialize($bu)
	{
	    $bu->writeUnsignedInt8($this->byCmd);
	    $bu->writeUnsignedInt8($this->byParam);
		$this->dwTimestamp = UtilSysLibWrap::getUTCSec();
		$bu->writeUnsignedInt32($this->dwTimestamp);
	}
	
	// Get 方式序列化
	public function serializeGet(MStringBuffer $stringBuffer)
	{
	    $bu->writeUnsignedInt8($this->byCmd);
	    $bu->writeUnsignedInt8($this->byParam);
	    $this->dwTimestamp = UtilSysLibWrap::getUTCSec();
	    $bu->writeUnsignedInt32($this->dwTimestamp);
	}

	// 二进制反序列化
	public function derialize($bu)
	{
	    $bu->readUnsignedInt8($this->byCmd);
	    $bu->readUnsignedInt8($this->byParam);
	    $bu->readUnsignedInt32($this->dwTimestamp);
	}
	
	// Get 方式反序列化
	public function derializeGet()
	{
	    $this->byCmd = $_REQUEST["byCmd"];
	    $this->byParam = $_REQUEST["byParam"];
	}
}

?>