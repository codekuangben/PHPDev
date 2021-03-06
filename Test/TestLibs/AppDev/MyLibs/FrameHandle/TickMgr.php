<?php

namespace MyLibs\FrameHandle;

use MyLibs\FrameWork\Ctx;
use MyLibs\FrameWork\MacroDef;

/**
 * @brief 心跳管理器
 */
class TickMgr extends TickObjectPriorityMgr
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

	public function Advance($delta, $tickMode)
	{
		if (MacroDef::ENABLE_PROFILE)
		{
			Ctx::$msIns->mProfiler->enter("TickMgr::Advance");
		}

		parent::Advance($delta, $tickMode);

		if (MacroDef::ENABLE_PROFILE)
		{
			Ctx::$msIns->mProfiler->exit("TickMgr::Advance");
		}
	}

	public function addTick($tickObj, $priority = 0.0)
	{
		$this->addObject($tickObj, $priority);
	}

	public function removeTick($tickObj)
	{
		$this->removeObject($tickObj);
	}
}

?>