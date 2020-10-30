<?php

namespace MyLibs\MsgRoute;

use MyLibs\EventHandle\EventDispatchGroup;

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

	public function addRouteHandle($eventId, $eventListener, $eventHandle)
	{
		$this->mEventDispatchGroup->addEventHandle($eventId, $eventListener, $eventHandle);
	}

	public function removeRouteHandle($eventId, $eventListener, $eventHandle)
	{
		$this->mEventDispatchGroup->removeEventHandle($eventId, $eventListener, $eventHandle);
	}

	public function handleMsg($msg)
	{
		$textStr = "";

		if($this->mEventDispatchGroup->hasEventHandle($msg->mMsgType))
		{
		    //$textStr = Ctx::$msIns->mLangMgr->getText(LangTypeId::eMsgRoute1, LangItemID::eItem2);
			//$this->mEventDispatchGroup->dispatchEvent($msg->mMsgType, msg);
		}
		else
		{
		    //$textStr = Ctx::$msIns->mLangMgr->getText(LangTypeId::eMsgRoute1, LangItemID::eItem3);
		}
	}
}

?>