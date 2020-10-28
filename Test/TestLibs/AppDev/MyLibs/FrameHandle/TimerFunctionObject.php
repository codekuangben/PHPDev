<?php

namespace MyLibs;

class TimerFunctionObject
{
	public $mHandle;

	public function __construct()
	{
		$this->mHandle = null;
	}

	public function setFuncObject($handle)
	{
		$this->mHandle = $handle;
	}

	public function isValid()
	{
		return $this->mHandle != null;
	}

	public function isEqual($handle)
	{
		$ret = false;
		if($handle != null)
		{
			$ret = UtilSysLibWrap::isAddressEqual($this->mHandle, $handle);
			if(!$ret)
			{
				return $ret;
			}
		}
		
		return $ret;
	}

	public function call($dispObj)
	{
		if (null != $this->mHandle)
		{
			$this->mHandle($dispObj);
		}
	}
}

?>