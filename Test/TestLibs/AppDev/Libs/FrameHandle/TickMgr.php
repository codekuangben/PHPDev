<?php

namespace SDK\Lib;

/**
 * @brief 心跳管理器
 */
public class TickMgr : TickObjectPriorityMgr
{
	public TickMgr()
	{
		
	}

	override public void init()
	{
		base.init();
	}

	override public void dispose()
	{
		base.dispose();
	}

	override public void Advance(float delta, TickMode tickMode)
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

	public void addTick(ITickedObject tickObj, float priority = 0.0f)
	{
		$this->addObject(tickObj as IDelayHandleItem, priority);
	}

	public void removeTick(ITickedObject tickObj)
	{
		$this->removeObject(tickObj as IDelayHandleItem);
	}
}

?>