<?php

namespace MyLibs;

// 每一帧执行的对象管理器
class TickObjectNoPriorityMgr extends DelayNoPriorityHandleMgr implements ITickedObject, IDelayHandleItem, INoOrPriorityObject
{
	public function __construct()
	{

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
		$idx = 0;
		$count = $this->mNoOrPriorityList->count();
		$tickObject = null;

		while (idx < count)
		{
			$tickObject = $this->mNoOrPriorityList->get(idx);

			if (null != $tickObject)
			{
				if (!$tickObject->isClientDispose())
				{
				    $tickObject->onTick(delta, tickMode);
				}
			}
			else
			{
				if (MacroDef::ENABLE_LOG)
				{
					Ctx::$msInstance->mLogSys->log("TickObjectNoPriorityMgr::onExecAdvance, failed", LogTypeId::eLogCommon);
				}
			}

			++$idx;
		}
	}

	protected function onPostAdvance($delta, $tickMode)
	{

	}
}

?>