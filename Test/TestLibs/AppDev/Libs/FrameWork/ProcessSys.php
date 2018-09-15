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
			Ctx::$mInstance->mProfiler->enter("ProcessSys::ProcessNextFrame");
		}

		//Ctx::$mInstance->mSystemTimeData->nextFrame();
		$this->advance(Ctx::$mInstance->mSystemTimeData->getDeltaSec());

		if (MacroDef::ENABLE_PROFILE)
		{
		    Ctx::$mInstance->mProfiler->exit("ProcessSys::ProcessNextFrame");
		}
	}

	public function advance($delta)
	{
	    Ctx::$mInstance->mSystemFrameData->nextFrame($delta);
	    Ctx::$mInstance->mTickMgr->Advance($delta, TickMode::eTM_Update);            // 心跳
	    Ctx::$mInstance->mTimerMgr->Advance($delta);           // 定时器
	    Ctx::$mInstance->mFrameTimerMgr->Advance($delta);      // 帧定时器
	}
}

?>