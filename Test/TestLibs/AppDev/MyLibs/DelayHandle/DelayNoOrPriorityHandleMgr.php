<?php

namespace MyLibs\DelayHandle;

use MyLibs\FrameWork\Ctx;
use MyLibs\FrameWork\MacroDef;
use MyLibs\Log\LogTypeId;

/**
 * @brief 延迟优先级处理管理器
 */
class DelayNoOrPriorityHandleMgr extends DelayNoOrPriorityHandleMgrBase
{
	protected $mNoOrPriorityList;

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
		$this->mNoOrPriorityList->clear();
	}

	protected function addObject($delayObject, $priority = 0.0)
	{
		if (null != delayObject)
		{
			if ($this->isInDepth())
			{
				parent::addObject(delayObject, priority);
			}
			else
			{
				$this->mNoOrPriorityList->addNoOrPriorityObject($delayObject, $priority);
			}
		}
		else
		{
			if (MacroDef::ENABLE_LOG)
			{
				Ctx::$msIns->mLogSys->log("DelayPriorityHandleMgr::addObject, failed", LogTypeId::eLogCommon);
			}
		}
	}

	protected function removeObject($delayObject)
	{
		if (null != $delayObject)
		{
			if ($this->isInDepth())
			{
				parent::removeObject($delayObject);
			}
			else
			{
				$this->mNoOrPriorityList->removeNoOrPriorityObject($delayObject);
			}
		}
		else
		{
			if (MacroDef::ENABLE_LOG)
			{
				Ctx::$msIns->mLogSys->log("DelayPriorityHandleMgr::removeObject, failed", LogTypeId::eLogCommon);
			}
		}
	}

	public function addNoOrPriorityObject($priorityObject, $priority = 0.0)
	{
		$this->addObject($priorityObject, $priority);
	}

	public function removeNoOrPriorityObject($tickObj)
	{
		$this->removeObject($tickObj);
	}
}

?>