﻿<?php

namespace SDK\Lib;

/**
 * @brief 全局数据区
 */
class Ctx
{
	public static $mInstance;

	public $mNetMgr;                // 网络通信
	public $mCfg;                       // 整体配置文件
	public $mLogSys;                    // 日志系统

	public $mLoginModule;
	public $mGameModule;                 // 游戏系统
	public $mAutoUpdateModule;
	public $mSceneSys;                // 场景系统

	public $mTickMgr;                  // 心跳管理器，正常 Update
	public $mFixedTickMgr;        // 固定间隔心跳管理器, FixedUpdate
	public $mLateTickMgr;          // 固定间隔心跳管理器, LateUpdate
	public $mLogicTickMgr;        // 逻辑心跳管理器
	public $mProcessSys;            // 游戏处理系统

	public $mTimerMgr;                // 定时器系统
	public $mFrameTimerMgr;      // 定时器系统
	public $mEngineLoop;            // 引擎循环
	public $mShareData;              // 共享数据系统
	public $mSysMsgRoute;          // 消息分发
	public $mNetCmdNotify;        // 网络处理器
	public $mMsgRouteNotify;    // RouteMsg 客户端自己消息流程
	public $mModuleSys;             // 模块
	public $mTableSys;                // 表格
	public $mFileSys;                 // 文件系统
	public $mFactoryBuild;        // 生成各种内容，上层只用接口

	public $mSystemSetting;
	public $mPoolSys;
	public $mTaskQueue;
	public $mTaskThreadPool;
	public $mSystemFrameData;
	public $mSystemTimeData;
	
	public $mGcAutoCollect;     // 自动垃圾回收
	public $mMemoryCheck;       // 内存查找
	
	public $mGlobalDelegate;
	public $mIdPoolSys;
	public $mUniqueStrIdGen;
	public $mNetEventHandle;
	public $mProfiler;

	public function __construct()
	{
		
	}

	public static function instance()
	{
		if ($mInstance == null)
		{
			$mInstance = new Ctx();
		}
		
		return $mInstance;
	}

	protected function constructInit()
	{
		$this->mUniqueStrIdGen = new UniqueStrIdGen("FindEvt", 0);

		MFileSys.init();            // 初始化本地文件系统的一些数据
		PlatformDefine.init();      // 初始化平台相关的定义
		UtilByte.checkEndian();     // 检查系统大端小端
		MThread.getMainThreadID();  // 获取主线程 ID

		$this->mNetCmdNotify = new NetCmdNotify();
		$this->mMsgRouteNotify = new MsgRouteNotify();
		$this->mGlobalDelegate = new GlobalDelegate();
		$this->mSystemSetting = new SystemSetting();
		$this->mTimerMsgHandle = new TimerMsgHandle();
		$this->mPoolSys = new PoolSys();

		$this->mTaskQueue = new TaskQueue("TaskQueue");
		$this->mTaskThreadPool = new TaskThreadPool();
		$this->mSystemFrameData = new SystemFrameData();
		$this->mSystemTimeData = new SystemTimeData();

		$this->mGcAutoCollect = new GCAutoCollect();
		$this->mMemoryCheck = new MemoryCheck();
		$this->mCfg = new Config();
		$this->mFactoryBuild = new FactoryBuild();
		$this->mNetMgr = new NetworkMgr();
		$this->mProcessSys = new ProcessSys();
		$this->mTickMgr = new TickMgr();
		$this->mFixedTickMgr = new FixedTickMgr();
		$this->mLateTickMgr = new LateTickMgr();

		$this->mTimerMgr = new TimerMgr();
		$this->mFrameTimerMgr = new FrameTimerMgr();
		$this->mShareData = new ShareData();
		$this->mSceneSys = new SceneSys();
		$this->mEngineLoop = new EngineLoop();
		$this->mSysMsgRoute = new SysMsgRoute("SysMsgRoute");
		$this->mModuleSys = new ModuleSys();
		$this->mTableSys = new TableSys();
		$this->mFileSys = new MFileSys();
		$this->mLogSys = new LogSys();

		$this->mCommonData = new CommonData();
		$this->mDelayTaskMgr = new DelayTaskMgr();
		$this->mIdPoolSys = new IdPoolSys();
		$this->mLogicTickMgr = new LogicTickMgr();
		$this->mNetEventHandle = new NetEventHandle();
		$this->mProfiler = new MProfiler();
	}

	public function logicInit()
	{
		$this->mGlobalDelegate.init();
		$this->mLogSys.init();
		$this->mTickMgr.init();
		$this->mFixedTickMgr.init();
		$this->mLateTickMgr.init();
		$this->mTaskQueue->mTaskThreadPool = $this->mTaskThreadPool;
		$this->mTaskThreadPool.initThreadPool(2, $this->mTaskQueue);
		$this->mSceneSys.init();

		$this->mCommonData.init();
		$this->mDelayTaskMgr.init();
		$this->mIdPoolSys.init();
		$this->mLogicTickMgr.init();
		$this->mNetEventHandle.init();
		$this->mProfiler.init();

		//if(MacroDef.ENABLE_PROFILE)
		//{
		//    $this->mProfiler.setIsStartProfile(true);
		//}

		// 添加事件处理
		if (null != Ctx.mInstance.mLayerMgr.mPath2Go[NotDestroyPath.ND_CV_App])
		{
			//Ctx.mInstance.mCamSys.setUiCamera(Ctx.mInstance.mLayerMgr.mPath2Go[NotDestroyPath.ND_CV_App].AddComponent<UICamera>());
			Ctx.mInstance.mCamSys.setSceneCamera2UICamera();
		}

		$this->addEventHandle();
	}

	public function init()
	{
		// 构造初始化
		constructInit();
		// 逻辑初始化，交叉引用的对象初始化
		logicInit();
	}

	public function dispose()
	{
		// 场景卸载
		if (null != $this->mSceneSys)
		{
			$this->mSceneSys.dispose();
			$this->mSceneSys = null;
		}
		// 等待网络关闭
		if (null != $this->mNetMgr)
		{
			$this->mNetMgr.dispose();
			$this->mNetMgr = null;
		}
		if (null != $this->mGlobalDelegate)
		{
			$this->mGlobalDelegate.dispose();
			$this->mGlobalDelegate = null;
		}
		if (null != $this->mCommonData)
		{
			$this->mCommonData.dispose();
			$this->mCommonData = null;
		}
		if(null != $this->mLogicTickMgr)
		{
			$this->mLogicTickMgr.dispose();
			$this->mLogicTickMgr = null;
		}

		if(null != $this->mNetEventHandle)
		{
			$this->mNetEventHandle.dispose();
			$this->mNetEventHandle = null;
		}
		if (null != $this->mDelayTaskMgr)
		{
			$this->mDelayTaskMgr.dispose();
			$this->mDelayTaskMgr = null;
		}
		if (null != $this->mIdPoolSys)
		{
			$this->mIdPoolSys.dispose();
			$this->mIdPoolSys = null;
		}
		if (null != $this->mTickMgr)
		{
			$this->mTickMgr.dispose();
			$this->mTickMgr = null;
		}
		if (null != $this->mFixedTickMgr)
		{
			$this->mFixedTickMgr.dispose();
			$this->mFixedTickMgr = null;
		}
		if (null != $this->mLateTickMgr)
		{
			$this->mLateTickMgr.dispose();
			$this->mLateTickMgr = null;
		}
		if(null != $this->mProfiler)
		{
			$this->mProfiler.dispose();
			$this->mProfiler = null;
		}
		// 关闭日志设备
		if (null != $this->mLogSys)
		{
			$this->mLogSys.dispose();
			$this->mLogSys = null;
		}
	}

	public function quitApp()
	{
		$this->dispose();

		// 释放自己
		//mInstance = null;
	}
}

?>