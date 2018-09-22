<?php

namespace SDK\Lib;

class MsgRouteDispatchHandle
{
	protected $mEventDispatchGroup;

	public function __construct()
	{
		$this->mEventDispatchGroup = new EventDispatchGroup();
	}

	public function init()
	{

	}

	public function dispose()
	{

	}

	public function addRouteHandle($evtId, $pThis, $handle)
	{
		$this->mEventDispatchGroup->addEventHandle($evtId, $pThis, $handle);
	}

	public function removeRouteHandle($evtId, $pThis, $handle)
	{
		$this->mEventDispatchGroup->removeEventHandle($evtId, $pThis, $handle);
	}

	public function handleMsg($msg)
	{
		$textStr = "";

		if($this->mEventDispatchGroup->hasEventHandle($msg->mMsgType))
		{
		    //$textStr = Ctx::$msInstance->mLangMgr->getText(LangTypeId::eMsgRoute1, LangItemID::eItem2);
			//$this->mEventDispatchGroup->dispatchEvent($msg->mMsgType, msg);
		}
		else
		{
		    //$textStr = Ctx::$msInstance->mLangMgr->getText(LangTypeId::eMsgRoute1, LangItemID::eItem3);
		}
	}
}

?>