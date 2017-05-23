<?php

/**
* @brief 系统循环
*/
namespace SDK\Lib;

class ProcessSys
{
	public function __construct()
	{

	}

	public function ProcessNextFrame()
	{
		if (MacroDef.ENABLE_PROFILE)
		{
			Ctx.mInstance.mProfiler.enter("ProcessSys::ProcessNextFrame");
		}

		//Ctx.mInstance.mSystemTimeData.nextFrame();
		$this->Advance(Ctx.mInstance.mSystemTimeData.deltaSec);

		if (MacroDef.ENABLE_PROFILE)
		{
			Ctx.mInstance.mProfiler.exit("ProcessSys::ProcessNextFrame");
		}
	}

	public function Advance($delta)
	{
		Ctx.mInstance.mFrameCollideMgr.clear();
		Ctx.mInstance.mSystemFrameData.nextFrame(delta);
		Ctx.mInstance.mLuaSystem.advance(delta, TickMode.eTM_Update);        // lua 脚本 Advance
		Ctx.mInstance.mTickMgr.Advance(delta, TickMode.eTM_Update);            // 心跳
		Ctx.mInstance.mTimerMgr.Advance(delta);           // 定时器
		Ctx.mInstance.mFrameTimerMgr.Advance(delta);      // 帧定时器
	}

	public function ProcessNextFixedFrame()
	{
		if (MacroDef.ENABLE_PROFILE)
		{
			Ctx.mInstance.mProfiler.enter("ProcessSys::ProcessNextFixedFrame");
		}

		$this->FixedAdvance(Ctx.mInstance.mSystemTimeData.getFixedTimestep());

		if (MacroDef.ENABLE_PROFILE)
		{
			Ctx.mInstance.mProfiler.exit("ProcessSys::ProcessNextFixedFrame");
		}
	}

	public function FixedAdvance($delta)
	{
		if (MacroDef.ENABLE_PROFILE)
		{
			Ctx.mInstance.mProfiler.enter("ProcessSys::FixedAdvance");
		}

		Ctx.mInstance.mFixedTickMgr.Advance(delta, TickMode.eTM_FixedUpdate);

		if (MacroDef.ENABLE_PROFILE)
		{
			Ctx.mInstance.mProfiler.exit("ProcessSys::FixedAdvance");
		}
	}

	public function ProcessNextLateFrame()
	{
		if (MacroDef.ENABLE_PROFILE)
		{
			Ctx.mInstance.mProfiler.enter("ProcessSys::ProcessNextLateFrame");
		}

		$this->LateAdvance(Ctx.mInstance.mSystemTimeData.deltaSec);

		if (MacroDef.ENABLE_PROFILE)
		{
			Ctx.mInstance.mProfiler.exit("ProcessSys::ProcessNextLateFrame");
		}
	}

	public function LateAdvance($delta)
	{
		if (MacroDef.ENABLE_PROFILE)
		{
			Ctx.mInstance.mProfiler.enter("ProcessSys::LateAdvance");
		}

		Ctx.mInstance.mLateTickMgr.Advance(delta, TickMode.eTM_LateUpdate);

		if (MacroDef.ENABLE_PROFILE)
		{
			Ctx.mInstance.mProfiler.exit("ProcessSys::LateAdvance");
		}
	}
}

?>