<?php

namespace MyLibs\FrameWork;

//require_once (dirname(__FILE__) . "/../Common/Prequisites.php");
//require_once (dirname(__FILE__) . "/../../Test/Base/TestMain.php");

use MyLibs\Tools\PlatformDefine;
use MyLibs\Tools\SystemEndian;
use MyLibs\Tools\UtilByte;
use Test\Base\TestMain;
use MyLibs\Base\UniqueStrIdGen;
use MyLibs\Network\CmdDispatch\NetCmdNotify;
use MyLibs\MsgRoute\MsgRouteNotify;
use MyLibs\Pool\PoolSys;
use MyLibs\Task\TaskQueue;
use MyLibs\Task\TaskThreadPool;
use MyLibs\FrameHandle\SystemFrameData;
use MyLibs\FrameHandle\SystemTimeData;
use MyLibs\FrameHandle\TickMgr;
use MyLibs\FrameHandle\TimerMgr;
use MyLibs\FrameHandle\FrameTimerMgr;
use MyLibs\MsgRoute\SysMsgRoute;
use MyLibs\Log\LogSys;
use MyLibs\Pool\IdPoolSys;
use MyLibs\FrameHandle\LogicTickMgr;
use MyLibs\Base\Performance\MProfiler;
use MyLibs\DataBase\DBPdo;

/**
 * @brief 全局数据区
 */
class Ctx
{
	public static $msIns;

	public $mCfg;                       // 整体配置文件
	public $mLogSys;                    // 日志系统

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

	public $mSystemSetting;
	public $mPoolSys;
	public $mTaskQueue;
	public $mTaskThreadPool;
	public $mSystemFrameData;
	public $mSystemTimeData;
	
	public $mGlobalDelegate;
	public $mIdPoolSys;
	public $mUniqueStrIdGen;
	public $mProfiler;
	
	public $mDBPdo;
	public $mTestMain;

	public function __construct()
	{
		
	}

	public static function instance($value = null)
	{
	    if (self::$msIns == null)
		{
		    if(null != $value)
		    {
		        self::$msIns = $value;
		    }
		    else
		    {
		        self::$msIns = new Ctx();
		    }
		}
		
		return Ctx::$msIns;
	}

	protected function _preInit()
	{
		$this->mUniqueStrIdGen = new UniqueStrIdGen("FindEvt", 0);

		PlatformDefine::init();      // 初始化平台相关的定义
		UtilByte::checkEndian();     // 检查系统大端小端
		SystemEndian::init();
		//MThread::getMainThreadId();  // 获取主线程 Id

		$this->mNetCmdNotify = new NetCmdNotify();
		$this->mMsgRouteNotify = new MsgRouteNotify();
		$this->mGlobalDelegate = new GlobalDelegate();
		$this->mSystemSetting = new SystemSetting();
		$this->mPoolSys = new PoolSys();

		$this->mTaskQueue = new TaskQueue("TaskQueue");
		$this->mTaskThreadPool = new TaskThreadPool();
		$this->mSystemFrameData = new SystemFrameData();
		$this->mSystemTimeData = new SystemTimeData();

		$this->mCfg = new Config();
		$this->mProcessSys = new ProcessSys();
		$this->mTickMgr = new TickMgr();

		$this->mTimerMgr = new TimerMgr();
		$this->mFrameTimerMgr = new FrameTimerMgr();
		$this->mShareData = new ShareData();
		$this->mEngineLoop = new EngineLoop();
		$this->mSysMsgRoute = new SysMsgRoute("SysMsgRoute");
		$this->mLogSys = new LogSys();

		$this->mIdPoolSys = new IdPoolSys();
		$this->mLogicTickMgr = new LogicTickMgr();
		$this->mProfiler = new MProfiler();
		$this->mDBPdo = new DBPdo();
	}

	public function _execInit()
	{
		$this->mGlobalDelegate->init();
		$this->mLogSys->init();
		$this->mTickMgr->init();
		//$this->mTaskQueue->mTaskThreadPool = $this->mTaskThreadPool;
		//$this->mTaskThreadPool->initThreadPool(2, $this->mTaskQueue);

		//$this->mDelayTaskMgr->init();
		//$this->mIdPoolSys->init();
		$this->mLogicTickMgr->init();
		//$this->mProfiler->init();

		//if(MacroDef::ENABLE_PROFILE)
		//{
		//    $this->mProfiler->setIsStartProfile(true);
		//}
		
		$this->mDBPdo->init();
	}
	
	public function _postInit()
	{
	    
	}

	public function init()
	{
	    $this->_preInit();
	    $this->_execInit();
	    $this->_postInit();
	}

	public function dispose()
	{
		// 等待网络关闭
		if (null != $this->mNetMgr)
		{
			$this->mNetMgr->dispose();
			$this->mNetMgr = null;
		}
		if (null != $this->mGlobalDelegate)
		{
			$this->mGlobalDelegate->dispose();
			$this->mGlobalDelegate = null;
		}
		if(null != $this->mLogicTickMgr)
		{
			$this->mLogicTickMgr->dispose();
			$this->mLogicTickMgr = null;
		}

		if (null != $this->mDelayTaskMgr)
		{
			$this->mDelayTaskMgr->dispose();
			$this->mDelayTaskMgr = null;
		}
		if (null != $this->mIdPoolSys)
		{
			$this->mIdPoolSys->dispose();
			$this->mIdPoolSys = null;
		}
		if (null != $this->mTickMgr)
		{
			$this->mTickMgr->dispose();
			$this->mTickMgr = null;
		}
		if(null != $this->mProfiler)
		{
			$this->mProfiler->dispose();
			$this->mProfiler = null;
		}
		// 关闭日志设备
		if (null != $this->mLogSys)
		{
			$this->mLogSys->dispose();
			$this->mLogSys = null;
		}
		if(null != $this->mDBPdo)
		{
		    $this->mDBPdo->dispose();
		    $this->mDBPdo = null;
		}
	}
	
	public function run()
	{
	    if(MacroDef::UNIT_TEST)
	    {
	        $this->mTestMain = new TestMain();
	        $this->mTestMain->init();
	        $this->mTestMain->run();
	    }
	}

	public function quitApp()
	{
		$this->dispose();

		// 释放自己
		//msIns = null;
	}
}

?>