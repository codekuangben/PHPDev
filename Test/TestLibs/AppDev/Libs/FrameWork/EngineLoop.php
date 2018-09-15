<?php

namespace SDK\Lib;

/**
 * @brief 主循环
 */
class EngineLoop
{
	public function MainLoop()
	{
		if(MacroDef::ENABLE_PROFILE)
		{
			Ctx::$mInstance->mProfiler->enter("EngineLoop::MainLoop");
		}

		// 时间间隔
		Ctx::$mInstance->mSystemTimeData->nextFrame();

		// 每一帧处理
		// 处理 input
		//Ctx::$mInstance->mInputMgr->handleKeyBoard();

		// 处理客户端的各类消息
		// 处理客户端自己的消息机制

		$routeMsg = null;

		while (($routeMsg = Ctx::$mInstance->mSysMsgRoute->popMsg()) != null)
		{
			Ctx::$mInstance->mMsgRouteNotify->handleMsg(routeMsg);
		}

		// 处理网络
		//if (!Ctx::$mInstance->mNetCmdNotify->isStopNetHandle)
		//{
		//    ByteBuffer ret = null;
		//    while ((ret = Ctx::$mInstance->mNetMgr->getMsg()) != null)
		//    {
		//        if (null != Ctx::$mInstance->mNetCmdNotify)
		//        {
		//            Ctx::$mInstance->mNetCmdNotify->addOneHandleMsg();
		//            Ctx::$mInstance->mNetCmdNotify->handleMsg(ret);       // CS 中处理
		//            Ctx::$mInstance->mLuaSystem->receiveToLuaRpc(ret);    // Lua 中处理
		//        }
		//    }
		//}

		if (null != Ctx::$mInstance->mLightServer_GB)
		{
			Ctx::$mInstance->mLightServer_GB->Receive();
		}

		// 填充数据到 KBEngine ，使用 KBEngine 引擎的逻辑解析
		//if (!Ctx::$mInstance->mNetCmdNotify->isStopNetHandle)
		//{
		//    ByteBuffer ret = null;
		//    while ((ret = Ctx::$mInstance->mNetMgr->getMsg_KBE()) != null)
		//    {
		//        Ctx::$mInstance->mMKBEMainEntry->gameapp->pushBuffer(ret->dynBuffer->buffer, ret->dynBuffer->size);
		//    }
		//}

		// KBEngine 引擎逻辑处理
		//Ctx::$mInstance->mMKBEMainEntry->FixedUpdate();

		// 每一帧的游戏逻辑处理
		Ctx::$mInstance->mProcessSys->ProcessNextFrame();
		// 日志处理
		Ctx::$mInstance->mLogSys->updateLog();

		if (MacroDef::ENABLE_PROFILE)
		{
			Ctx::$mInstance->mProfiler->exit("EngineLoop::MainLoop");
		}
	}

	public function fixedUpdate()
	{
		Ctx::$mInstance->mProcessSys->ProcessNextFixedFrame();
	}

	// 循环执行完成后，再次
	public function postUpdate()
	{
		Ctx::$mInstance->mProcessSys->ProcessNextLateFrame();
	}
}
?>