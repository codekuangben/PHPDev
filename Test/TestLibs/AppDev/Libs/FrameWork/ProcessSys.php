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

	public function processNextFrame()
	{
		if (MacroDef::ENABLE_PROFILE)
		{
			Ctx::$msInstance->mProfiler->enter("ProcessSys::ProcessNextFrame");
		}

		//Ctx::$msInstance->mSystemTimeData->nextFrame();
		$this->advance(Ctx::$msInstance->mSystemTimeData->getDeltaSec());

		if (MacroDef::ENABLE_PROFILE)
		{
		    Ctx::$msInstance->mProfiler->exit("ProcessSys::ProcessNextFrame");
		}
	}

	public function advance($delta)
	{
	    Ctx::$msInstance->mSystemFrameData->nextFrame($delta);
	    Ctx::$msInstance->mTickMgr->Advance($delta, TickMode::eTM_Update);            // 心跳
	    Ctx::$msInstance->mTimerMgr->Advance($delta);           // 定时器
	    Ctx::$msInstance->mFrameTimerMgr->Advance($delta);      // 帧定时器
	}
}

?>