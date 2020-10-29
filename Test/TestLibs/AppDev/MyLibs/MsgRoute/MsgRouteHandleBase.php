<?php

namespace MyLibs;

class MsgRouteHandleBase extends GObject implements ICalleeObject
{
	public $mId2HandleDic;

	public function __construct()
	{
		$this->mTypeId = "MsgRouteHandleBase";

		$this->mId2HandleDic = new MDictionary();
	}

	public function init()
	{

	}

	public function dispose()
	{

	}

	public function addMsgRouteHandle($msgRouteId, $eventHandle)
	{
	    if(!$this->mId2HandleDic->containsKey($msgRouteId))
		{
		    $this->mId2HandleDic.add($msgRouteId, new AddOnceEventDispatch());
		}

		$this->mId2HandleDic->value($msgRouteId)->addEventHandle(null, $eventHandle);
	}

	public function removeMsgRouteHandle($msgRouteId, $eventHandle)
	{
		if ($this->mId2HandleDic->containsKey((int)$msgRouteId))
		{
			$this->mId2HandleDic->value((int)$msgRouteId)->removeEventHandle(null, handle);
		}
	}

	public function handleMsg($dispatchObject)
	{
	    $msg = $dispatchObject;

	    if ($this->mId2HandleDic->containsKey($msg->mMsgId))
		{
		    $this->mId2HandleDic->value($msg->mMsgId)->dispatchEvent($msg);
		}
		else
		{
			
		}
	}

	public function call($dispatchObject)
	{

	}
}

?>