<?php

namespace MyLibs;

class TimerFunctionObject
{
	public $mEventHandle;

	public function __construct()
	{
		$this->mEventHandle = null;
	}

	public function setFuncObject($eventHandle)
	{
		$this->mEventHandle = $eventHandle;
	}

	public function isValid()
	{
		return $this->mEventHandle != null;
	}

	public function isEqual($eventHandle)
	{
		$ret = false;
		if($eventHandle != null)
		{
			$ret = UtilSysLibWrap::isAddressEqual($this->mEventHandle, $eventHandle);
			if(!$ret)
			{
				return $ret;
			}
		}
		
		return $ret;
	}

	public function call($dispatchObject)
	{
		if (null != $this->mEventHandle)
		{
			$this->mEventHandle($dispatchObject);
		}
	}
}

?>