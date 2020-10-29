<?php

/**
* @brief 系统循环
*/
namespace MyLibs;

class ProcessSys
{
	public function __construct()
	{

	}

	public function processNextFrame()
	{
		if (MacroDef::ENABLE_PROFILE)
		{
			Ctx::$msIns->mProfiler->enter("ProcessSys::ProcessNextFrame");
		}

		//Ctx::$msIns->mSystemTimeData->nextFrame();
		$this->advance(Ctx::$msIns->mSystemTimeData->getDeltaSec());

		if (MacroDef::ENABLE_PROFILE)
		{
		    Ctx::$msIns->mProfiler->exit("ProcessSys::ProcessNextFrame");
		}
	}

	public function advance($delta)
	{
	    Ctx::$msIns->mSystemFrameData->nextFrame($delta);
	    Ctx::$msIns->mTickMgr->Advance($delta, TickMode::eTM_Update);            // 心跳
	    Ctx::$msIns->mTimerMgr->Advance($delta);           // 定时器
	    Ctx::$msIns->mFrameTimerMgr->Advance($delta);      // 帧定时器
	}
}

?>