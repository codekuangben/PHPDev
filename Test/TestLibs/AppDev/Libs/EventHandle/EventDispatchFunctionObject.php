<?php

namespace SDK\Lib;

class EventDispatchFunctionObject implements IDelayHandleItem, INoOrPriorityObject
{
	public $mIsClientDispose;       // 是否释放了资源
	public $mThis;
	public $mHandle;
	public $mEventId;   // 事件唯一 Id

	public function __construct()
	{
		$this->mIsClientDispose = false;
	}

	public function setFuncObject($pThis, $handle, $eventId = 0)
	{
		$this->mThis = $pThis;
		$this->mHandle = $handle;
		$this->mEventId = $eventId;
	}

	public function isValid()
	{
		return $this->mThis != null || $this->mHandle != null;
	}

	public function isEventIdEqual($eventId)
	{
		return $this->mEventId == eventId;
	}

	public function isEqual($pThis, $handle, $eventId)
	{
		$ret = false;

		if($pThis != null)
		{
			$ret = UtilApi.isAddressEqual($this->mThis, $pThis);

			if (!$ret)
			{
				return $ret;
			}
		}

		if ($handle != null)
		{
			$ret = UtilApi.isDelegateEqual($this->mHandle, $handle);

			if (!$ret)
			{
				return $ret;
			}
		}

		if ($pThis != null || $handle != null)
		{
			$ret = $this->isEventIdEqual($eventId);

			if (!$ret)
			{
				return ret;
			}
		}

		return $ret;
	}

	public function call($dispObj)
	{
		if(null != $this->mHandle)
		{
			if(null != $this->mThis)
			{
				call_user_func(array($this->mThis, $this->mHandle), $dispObj);
			}
			else
			{
				call_user_func($this->mHandle, $dispObj);
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