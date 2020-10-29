<?php

namespace MyLibs;

// 每一帧执行的对象管理器
class TickObjectPriorityMgr extends DelayPriorityHandleMgr implements ITickedObject, IDelayHandleItem, INoOrPriorityObject
{
	public function __construct()
	{
	    parent::__construct();
	}

	public function init()
	{
		parent::init();
	}

	public function dispose()
	{
		parent::dispose();
	}

	public function setClientDispose($isDispose)
	{

	}

	public function isClientDispose()
	{
		return false;
	}

	public function onTick($delta, $tickMode)
	{
		$this->Advance($delta, $tickMode);
	}

	public function Advance($delta, $tickMode)
	{
		$this->incDepth();

		$this->onPreAdvance($delta, $tickMode);
		$this->onExecAdvance($delta, $tickMode);
		$this->onPostAdvance($delta, $tickMode);

		$this->decDepth();
	}

	protected function onPreAdvance($delta, $tickMode)
	{

	}

	protected function onExecAdvance($delta, $tickMode)
	{
		$index = 0;
		$listLen = $this->mNoOrPriorityList->count();
		$tickObject = null;

		while ($index < $listLen)
		{
		    $tickObject = $this->mNoOrPriorityList->get($index);

			if (null != $tickObject)
			{
			    if (!$tickObject->isClientDispose())
				{
				    $tickObject->onTick($delta, $tickMode);
				}
			}
			else
			{
				if (MacroDef::ENABLE_LOG)
				{
					Ctx::$msIns->mLogSys->log("TickObjectPriorityMgr::onExecAdvance, failed", LogTypeId::eLogCommon);
				}
			}

			$index += 1;
		}
	}

	protected function onPostAdvance($delta, $tickMode)
	{

	}
}

?>