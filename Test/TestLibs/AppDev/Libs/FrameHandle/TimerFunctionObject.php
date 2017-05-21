<?php

namespace SDK\Lib;

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
			$ret = UtilApi::isAddressEqual($this->mHandle, $handle);
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