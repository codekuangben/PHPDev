<?php

namespace MyLibs;

/**
 * @brief 主循环
 */
class EngineLoop
{
	public function MainLoop()
	{
		if(MacroDef::ENABLE_PROFILE)
		{
			Ctx::$msInstance->mProfiler->enter("EngineLoop::MainLoop");
		}

		// 时间间隔
		Ctx::$msInstance->mSystemTimeData->nextFrame();

		// 每一帧处理
		// 处理 input
		//Ctx::$msInstance->mInputMgr->handleKeyBoard();

		// 处理客户端的各类消息
		// 处理客户端自己的消息机制

		$routeMsg = null;

		while (($routeMsg = Ctx::$msInstance->mSysMsgRoute->popMsg()) != null)
		{
			Ctx::$msInstance->mMsgRouteNotify->handleMsg(routeMsg);
		}

		// 处理网络
		//if (!Ctx::$msInstance->mNetCmdNotify->isStopNetHandle)
		//{
		//    ByteBuffer ret = null;
		//    while ((ret = Ctx::$msInstance->mNetMgr->getMsg()) != null)
		//    {
		//        if (null != Ctx::$msInstance->mNetCmdNotify)
		//        {
		//            Ctx::$msInstance->mNetCmdNotify->addOneHandleMsg();
		//            Ctx::$msInstance->mNetCmdNotify->handleMsg(ret);       // CS 中处理
		//            Ctx::$msInstance->mLuaSystem->receiveToLuaRpc(ret);    // Lua 中处理
		//        }
		//    }
		//}

		if (null != Ctx::$msInstance->mLightServer_GB)
		{
			Ctx::$msInstance->mLightServer_GB->Receive();
		}

		// 填充数据到 KBEngine ，使用 KBEngine 引擎的逻辑解析
		//if (!Ctx::$msInstance->mNetCmdNotify->isStopNetHandle)
		//{
		//    ByteBuffer ret = null;
		//    while ((ret = Ctx::$msInstance->mNetMgr->getMsg_KBE()) != null)
		//    {
		//        Ctx::$msInstance->mMKBEMainEntry->gameapp->pushBuffer(ret->dynBuffer->buffer, ret->dynBuffer->size);
		//    }
		//}

		// KBEngine 引擎逻辑处理
		//Ctx::$msInstance->mMKBEMainEntry->FixedUpdate();

		// 每一帧的游戏逻辑处理
		Ctx::$msInstance->mProcessSys->ProcessNextFrame();
		// 日志处理
		Ctx::$msInstance->mLogSys->updateLog();

		if (MacroDef::ENABLE_PROFILE)
		{
			Ctx::$msInstance->mProfiler->exit("EngineLoop::MainLoop");
		}
	}

	public function fixedUpdate()
	{
		Ctx::$msInstance->mProcessSys->ProcessNextFixedFrame();
	}

	// 循环执行完成后，再次
	public function postUpdate()
	{
		Ctx::$msInstance->mProcessSys->ProcessNextLateFrame();
	}
}
?>