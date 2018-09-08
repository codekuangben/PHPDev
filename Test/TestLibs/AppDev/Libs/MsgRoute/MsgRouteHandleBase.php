<?php

namespace SDK\Lib;

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

	public function addMsgRouteHandle($msgRouteID, $handle)
	{
		if(!$this->mId2HandleDic.ContainsKey((int)msgRouteID))
		{
			$this->mId2HandleDic[(int)msgRouteID] = new AddOnceEventDispatch();
		}

		$this->mId2HandleDic[(int)msgRouteID].addEventHandle(null, handle);
	}

	public function removeMsgRouteHandle($msgRouteID, $handle)
	{
		if ($this->mId2HandleDic.ContainsKey((int)msgRouteID))
		{
			$this->mId2HandleDic[(int)msgRouteID].removeEventHandle(null, handle);
		}
	}

	public function handleMsg($dispObj)
	{
		$msg = dispObj;

		if ($this->mId2HandleDic.ContainsKey(msg.mMsgID))
		{
			$this->mId2HandleDic[msg.mMsgID].dispatchEvent(msg);
		}
		else
		{
			
		}
	}

	public function call($dispObj)
	{

	}
}

?>