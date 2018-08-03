<?php

namespace SDK\Lib;

class LogSys
{
	protected $mAsyncLogList;              // 这个是多线程访问的
	protected $mAsyncWarnList;            // 这个是多线程访问的
	protected $mAsyncErrorList;          // 这个是多线程访问的

	protected $mTmpStr;

	protected $mLogDeviceList;
	protected $mEnableLogTypeList;

	protected $mEnableLog;    // 全局开关
	protected $mIsOutStack;     // 是否显示堆栈信息
	protected $mIsOutTimeStamp;   // 是否有时间戳

	// 构造函数仅仅是初始化变量，不涉及逻辑
	public function __construct()
	{
		$this->mAsyncLogList = new LockList("Logger_asyncLogList");
		$this->mAsyncWarnList = new LockList("Logger_asyncWarnList");
		$this->mAsyncErrorList = new LockList("Logger_asyncErrorList");
		$this->mLogDeviceList = new MList();

		$this->mEnableLogTypeList = new MList<LogTypeId>[(int)LogColor.eLC_Count];

		$this->mEnableLogTypeList[(int)LogColor.eLC_LOG] = new MList();
		//$this->mEnableLogTypeList[(int)LogColor.eLC_LOG].Add(LogTypeId.eLogCommon);
		//$this->mEnableLogTypeList[(int)LogColor.eLC_LOG].Add(LogTypeId.eLogResLoader);
		//$this->mEnableLogTypeList[(int)LogColor.eLC_LOG].Add(LogTypeId.eLogLocalFile);
		//$this->mEnableLogTypeList[(int)LogColor.eLC_LOG].Add(LogTypeId.eLogTestRL);
		//$this->mEnableLogTypeList[(int)LogColor.eLC_LOG].Add(LogTypeId.eLogAcceleration);
		//$this->mEnableLogTypeList[(int)LogColor.eLC_LOG].Add(LogTypeId.eLogUnityCB);

		//$this->mEnableLogTypeList[(int)LogColor.eLC_LOG].Add(LogTypeId.eLogSplitMergeEmit);
		//$this->mEnableLogTypeList[(int)LogColor.eLC_LOG].Add(LogTypeId.eLogSceneInterActive);
		//$this->mEnableLogTypeList[(int)LogColor.eLC_LOG].Add(LogTypeId.eLogKBE);
		//$this->mEnableLogTypeList[(int)LogColor.eLC_LOG].Add(LogTypeId.eLogScene);
		//$this->mEnableLogTypeList[(int)LogColor.eLC_LOG].Add(LogTypeId.eLogBeingMove);
		//$this->mEnableLogTypeList[(int)LogColor.eLC_LOG].Add(LogTypeId.eLogEventRemove);
		//$this->mEnableLogTypeList[(int)LogColor.eLC_LOG].Add(LogTypeId.eLogMusicBug);
		//$this->mEnableLogTypeList[(int)LogColor.eLC_LOG].Add(LogTypeId.eLogLoadBug);
		//$this->mEnableLogTypeList[(int)LogColor.eLC_LOG].Add(LogTypeId.eLogMergeBug);
		//$this->mEnableLogTypeList[(int)LogColor.eLC_LOG].Add(LogTypeId.eLogEatBug);
		//$this->mEnableLogTypeList[(int)LogColor.eLC_LOG].Add(LogTypeId.eLogSimHitBullet);
		//$this->mEnableLogTypeList[(int)LogColor.eLC_LOG].Add(LogTypeId.eLogTwoDTerrain);
		//$this->mEnableLogTypeList[(int)LogColor.eLC_LOG].Add(LogTypeId.eLogPriorityListCheck);
		//$this->mEnableLogTypeList[(int)LogColor.eLC_LOG].Add(LogTypeId.eLogNoPriorityListCheck);
		//$this->mEnableLogTypeList[(int)LogColor.eLC_LOG].Add(LogTypeId.eLogPosSyn);
		//$this->mEnableLogTypeList[(int)LogColor.eLC_LOG].Add(LogTypeId.eLogPlaneError);
		//$this->mEnableLogTypeList[(int)LogColor.eLC_LOG].Add(LogTypeId.eLogDownload);
		//$this->mEnableLogTypeList[(int)LogColor.eLC_LOG].Add(LogTypeId.eLogAutoUpdate);
		//$this->mEnableLogTypeList[(int)LogColor.eLC_LOG].Add(LogTypeId.eLogProfileDebug);
		$this->mEnableLogTypeList[(int)LogColor.eLC_LOG].Add(LogTypeId.eLogProfile);

		// 编辑器日志
		//$this->mEnableLogTypeList[(int)LogColor.eLC_LOG].Add(LogTypeId.eLogEditorBuildPlayer);

		$this->mEnableLogTypeList[(int)LogColor.eLC_WARN] = new MList();

		$this->mEnableLogTypeList[(int)LogColor.eLC_ERROR] = new MList();
		//$this->mEnableLogTypeList[(int)LogColor.eLC_ERROR].Add(LogTypeId.eLogLoadBug);
		$this->mEnableLogTypeList[(int)LogColor.eLC_ERROR].Add(LogTypeId.eErrorDownload);

		$this->mEnableLog = new MList();
		$this->mEnableLog.Add(MacroDef.ENABLE_LOG);
		$this->mEnableLog.Add(MacroDef.ENABLE_WARN);
		$this->mEnableLog.Add(MacroDef.ENABLE_ERROR);

		$this->mIsOutStack = new MList();
		$this->mIsOutStack.Add(false);
		$this->mIsOutStack.Add(false);
		$this->mIsOutStack.Add(false);

		$this->mIsOutTimeStamp = new MList();
		$this->mIsOutTimeStamp.Add(false);
		$this->mIsOutTimeStamp.Add(false);
		$this->mIsOutTimeStamp.Add(false);
	}

	// 初始化逻辑处理
	public function init()
	{
		$this->registerDevice();
		$this->registerFileLogDevice();
	}

	// 析构
	public function dispose()
	{
		$this->closeDevice();
	}

	public function setEnableLog($value)
	{
		$this->mEnableLog.set(LogColor::eLC_LOG, value);
	}

	public function setEnableWarn($value)
	{
	    $this->mEnableLog.set(LogColor::eLC_WARN, $value);
	}

	public function setEnableError($value)
	{
	    $this->mEnableLog.set(LogColor::eLC_ERROR, $value);
	}

	protected function registerDevice()
	{
		$logDevice = null;

		if (MacroDef::ENABLE_WINLOG)
		{
		    $logDevice = new WinLogDevice();
		    $logDevice->initDevice();
		    $this->mLogDeviceList.Add($logDevice);
		}

		if (MacroDef::ENABLE_NETLOG)
		{
		    $logDevice = new NetLogDevice();
		    $logDevice->initDevice();
		    $this->mLogDeviceList.Add($logDevice);
		}
	}

	// 注册文件日志，因为需要账号，因此需要等待输入账号后才能注册，可能多次注册
	public function registerFileLogDevice()
	{
		if (MacroDef.ENABLE_FILELOG)
		{
			unRegisterFileLogDevice();

			LogDeviceBase logDevice = null;
			logDevice = new FileLogDevice();
			(logDevice as FileLogDevice).fileSuffix = Ctx.mInstance.mDataPlayer.mAccountData.m_account;
			logDevice.initDevice();
			$this->mLogDeviceList.Add(logDevice);
		}
	}

	protected function unRegisterFileLogDevice()
	{
		foreach(var item in mLogDeviceList.list())
		{
			if(typeof(FileLogDevice) == item.GetType())
			{
				item.closeDevice();
				$this->mLogDeviceList.Remove(item);
				break;
			}
		}
	}

	protected function isInFilter(LogTypeId logTypeId, LogColor logColor)
	{
		if ($this->mEnableLog[(int)logColor])
		{
			if ($this->mEnableLogTypeList[(int)logColor].Contains(logTypeId))
			{
				return true;
			}

			return false;
		}

		return false;
	}
	
	public function log(string message, LogTypeId logTypeId = LogTypeId.eLogCommon)
	{
		if (isInFilter(logTypeId, LogColor.eLC_LOG))
		{
			if($this->mIsOutTimeStamp[(int)LogColor.eLC_LOG])
			{
				message = string.Format("{0}: {1}", UtilSysLibWrap.getFormatTime(), message);
			}

			if ($this->mIsOutStack[(int)LogColor.eLC_LOG])
			{
				System.Diagnostics.StackTrace stackTrace = new System.Diagnostics.StackTrace(true);
				string traceStr = stackTrace.ToString();
				message = string.Format("{0}\n{1}", message, traceStr);
			}

			if (MThread.isMainThread())
			{
				$this->logout(message, LogColor.eLC_LOG);
			}
			else
			{
				$this->asyncLog(message);
			}
		}
	}

	public function warn(string message, LogTypeId logTypeId = LogTypeId.eLogCommon)
	{
		if (isInFilter(logTypeId, LogColor.eLC_WARN))
		{
			if ($this->mIsOutTimeStamp[(int)LogColor.eLC_WARN])
			{
				message = string.Format("{0}: {1}", UtilSysLibWrap.getFormatTime(), message);
			}

			if ($this->mIsOutStack[(int)LogColor.eLC_WARN])
			{
				System.Diagnostics.StackTrace stackTrace = new System.Diagnostics.StackTrace(true);
				string traceStr = stackTrace.ToString();
				message = string.Format("{0}\n{1}", message, traceStr);
			}

			if (MThread.isMainThread())
			{
				$this->logout(message, LogColor.eLC_WARN);
			}
			else
			{
				$this->asyncWarn(message);
			}
		}
	}

	public function error(string message, LogTypeId logTypeId = LogTypeId.eLogCommon)
	{
		if (isInFilter(logTypeId, LogColor.eLC_ERROR))
		{
			if ($this->mIsOutTimeStamp[(int)LogColor.eLC_ERROR])
			{
				message = string.Format("{0}: {1}", UtilSysLibWrap.getFormatTime(), message);
			}

			if ($this->mIsOutStack[(int)LogColor.eLC_ERROR])
			{
				System.Diagnostics.StackTrace stackTrace = new System.Diagnostics.StackTrace(true);
				string traceStr = stackTrace.ToString();
				message = string.Format("{0}\n{1}", message, traceStr);
			}

			if (MThread.isMainThread())
			{
				$this->logout(message, LogColor.eLC_ERROR);
			}
			else
			{
				$this->asyncError(message);
			}
		}
	}

	// 多线程日志
	protected function asyncLog(string message)
	{
		mAsyncLogList.Add(message);
	}

	// 多线程日志
	protected function asyncWarn(string message)
	{
		$this->mAsyncWarnList.Add(message);
	}

	// 多线程日志
	protected function asyncError(string message)
	{
		$this->mAsyncErrorList.Add(message);
	}

	public function logout(string message, LogColor type = LogColor.eLC_LOG)
	{
		if (MacroDef.THREAD_CALLCHECK)
		{
			MThread.needMainThread();
		}

		int idx = 0;
		int len = $this->mLogDeviceList.Count();
		LogDeviceBase logDevice = null;

		while (idx < len)
		{
			logDevice = $this->mLogDeviceList[idx];
			logDevice.logout(message, type);

			++idx;
		}
	}

	public function updateLog()
	{
		if (MacroDef.THREAD_CALLCHECK)
		{
			MThread.needMainThread();
		}

		while (($this->mTmpStr = mAsyncLogList.RemoveAt(0)) != default(string))
		{
			$this->logout(mTmpStr, LogColor.eLC_LOG);
		}

		while (($this->mTmpStr = mAsyncWarnList.RemoveAt(0)) != default(string))
		{
			$this->logout(mTmpStr, LogColor.eLC_WARN);
		}

		while (($this->mTmpStr = mAsyncErrorList.RemoveAt(0)) != default(string))
		{
			$this->logout(mTmpStr, LogColor.eLC_ERROR);
		}
	}

	protected function closeDevice()
	{
		foreach (LogDeviceBase logDevice in mLogDeviceList.list())
		{
			logDevice.closeDevice();
		}
	}
}

?>