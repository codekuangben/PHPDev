<?php

namespace MyLibs\Network\CmdDispatch;

use MyLibs\DataStruct\MStringBuffer;
use MyLibs\Tools\UtilSysLibWrap;

class NullUserCmd
{
	public const TIME_USERCMD = 2;
	public const DATA_USERCMD = 3;
	public const PROPERTY_USERCMD = 4;
	public const CHAT_USERCMD = 14;
	public const SELECT_USERCMD = 24;
	public const LOGON_USERCMD = 104;
	public const HERO_CARD_USERCMD = 162;

	public $CmdId;
	public $ParamId;
	public $dwTimestamp;

	// 二进制序列化
	public function serialize($byteBuffer)
	{
	    $byteBuffer->writeUnsignedInt8($this->CmdId);
	    $byteBuffer->writeUnsignedInt8($this->ParamId);
		$this->dwTimestamp = UtilSysLibWrap::getUTCSec();
		$byteBuffer->writeUnsignedInt32($this->dwTimestamp);
	}
	
	// Get 方式序列化
	public function serializeGet($byteBuffer)
	{
	    $byteBuffer->writeUnsignedInt8($this->CmdId);
	    $byteBuffer->writeUnsignedInt8($this->ParamId);
	    $this->dwTimestamp = UtilSysLibWrap::getUTCSec();
	    $byteBuffer->writeUnsignedInt32($this->dwTimestamp);
	}

	// 二进制反序列化
	public function derialize($byteBuffer)
	{
	    $byteBuffer->readUnsignedInt8($this->CmdId);
	    $byteBuffer->readUnsignedInt8($this->ParamId);
	    $byteBuffer->readUnsignedInt32($this->dwTimestamp);
	}
	
	// Get 方式反序列化
	public function derializeGet()
	{
	    $this->CmdId = $_REQUEST["CmdId"];
	    $this->ParamId = $_REQUEST["ParamId"];
	}
}

?>