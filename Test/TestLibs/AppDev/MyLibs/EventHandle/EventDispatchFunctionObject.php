<?php

namespace MyLibs\EventHandle;

use MyLibs\Base\GObject;
use MyLibs\DataStruct\NoOrPriorityList\INoOrPriorityObject;
use MyLibs\DelayHandle\IDelayHandleItem;
use MyLibs\Tools\UtilSysLibWrap;

class EventDispatchFunctionObject extends GObject implements IDelayHandleItem, INoOrPriorityObject
{
	public $mIsClientDispose;       // 是否释放了资源
	public $mEventListener;
	public $mEventHandle;
	public $mEventId;   // 事件唯一 Id

	public function __construct()
	{
	    Parent::__construct();
	    
		$this->mIsClientDispose = false;
	}

	public function setFuncObject($eventListener, $eventHandle, $eventId = 0)
	{
		$this->mEventListener = $eventListener;
		$this->mEventHandle = $eventHandle;
		$this->mEventId = $eventId;
	}

	public function isValid()
	{
		return $this->mEventListener != null || $this->mEventHandle != null;
	}

	public function isEventIdEqual($eventId)
	{
		return $this->mEventId == eventId;
	}

	public function isEqual($eventListener, $eventHandle, $eventId)
	{
		$ret = false;

		if($eventListener != null)
		{
			$ret = UtilSysLibWrap::isAddressEqual($this->mEventListener, $eventListener);

			if (!$ret)
			{
				return $ret;
			}
		}

		if ($eventHandle != null)
		{
			$ret = UtilSysLibWrap::isDelegateEqual($this->mEventHandle, $eventHandle);

			if (!$ret)
			{
				return $ret;
			}
		}

		if ($eventListener != null || $eventHandle != null)
		{
			$ret = $this->isEventIdEqual($eventId);

			if (!$ret)
			{
				return ret;
			}
		}

		return $ret;
	}

	public function call($dispatchObject)
	{
		if(null != $this->mEventHandle)
		{
			if(null != $this->mEventListener)
			{
			    call_user_func(array($this->mEventListener, $this->mEventHandle), $dispatchObject, $this->mEventId);
			}
			else
			{
			    call_user_func($this->mEventHandle, $dispatchObject, $this->mEventId);
			}
		}
	}

	public function setClientDispose($isDispose)
	{
		$this->mIsClientDispose = $isDispose;
	}

	public function isClientDispose()
	{
		return $this->mIsClientDispose;
	}
}

?>