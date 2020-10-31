<?php

namespace MyLibs\FrameWork;

/**
 * @brief 主循环
 */
class EngineLoop
{
	public function MainLoop()
	{
		if(MacroDef::ENABLE_PROFILE)
		{
			Ctx::$msIns->mProfiler->enter("EngineLoop::MainLoop");
		}

		// 时间间隔
		Ctx::$msIns->mSystemTimeData->nextFrame();

		// 每一帧处理
		// 处理 input
		//Ctx::$msIns->mInputMgr->handleKeyBoard();

		// 处理客户端的各类消息
		// 处理客户端自己的消息机制

		$routeMsg = null;

		while (($routeMsg = Ctx::$msIns->mSysMsgRoute->popMsg()) != null)
		{
		    Ctx::$msIns->mMsgRouteNotify->handleMsg($routeMsg);
		}

		// 处理网络
		//if (!Ctx::$msIns->mNetCmdNotify->isStopNetHandle)
		//{
		//    ByteBuffer ret = null;
		//    while ((ret = Ctx::$msIns->mNetMgr->getMsg()) != null)
		//    {
		//        if (null != Ctx::$msIns->mNetCmdNotify)
		//        {
		//            Ctx::$msIns->mNetCmdNotify->addOneHandleMsg();
		//            Ctx::$msIns->mNetCmdNotify->handleMsg(ret);       // CS 中处理
		//            Ctx::$msIns->mLuaSystem->receiveToLuaRpc(ret);    // Lua 中处理
		//        }
		//    }
		//}

		if (null != Ctx::$msIns->mLightServer_GB)
		{
			Ctx::$msIns->mLightServer_GB->Receive();
		}

		// 填充数据到 KBEngine ，使用 KBEngine 引擎的逻辑解析
		//if (!Ctx::$msIns->mNetCmdNotify->isStopNetHandle)
		//{
		//    ByteBuffer ret = null;
		//    while ((ret = Ctx::$msIns->mNetMgr->getMsg_KBE()) != null)
		//    {
		//        Ctx::$msIns->mMKBEMainEntry->gameapp->pushBuffer(ret->dynBuffer->buffer, ret->dynBuffer->size);
		//    }
		//}

		// KBEngine 引擎逻辑处理
		//Ctx::$msIns->mMKBEMainEntry->FixedUpdate();

		// 每一帧的游戏逻辑处理
		Ctx::$msIns->mProcessSys->ProcessNextFrame();
		// 日志处理
		Ctx::$msIns->mLogSys->updateLog();

		if (MacroDef::ENABLE_PROFILE)
		{
			Ctx::$msIns->mProfiler->exit("EngineLoop::MainLoop");
		}
	}

	public function fixedUpdate()
	{
		Ctx::$msIns->mProcessSys->ProcessNextFixedFrame();
	}

	// 循环执行完成后，再次
	public function postUpdate()
	{
		Ctx::$msIns->mProcessSys->ProcessNextLateFrame();
	}
}
?>