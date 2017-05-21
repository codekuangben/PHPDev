<?php

namespace SDK\Lib;

/**
 * @brief 心跳管理器
 */
class TickMgr extends TickObjectPriorityMgr
{
	public function __construct()
	{
		
	}

	public function init()
	{
		base.init();
	}

	public function dispose()
	{
		base.dispose();
	}

	public function Advance($delta, $tickMode)
	{
		if (MacroDef.ENABLE_PROFILE)
		{
			Ctx.mInstance.mProfiler.enter("TickMgr::Advance");
		}

		base.Advance(delta, tickMode);

		if (MacroDef.ENABLE_PROFILE)
		{
			Ctx.mInstance.mProfiler.exit("TickMgr::Advance");
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