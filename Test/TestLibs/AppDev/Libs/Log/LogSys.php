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

		$this->mEnableLogTypeList = Array();
		
		$index = 0;
		$listLen = LogColor::eLC_Count;
		
		while($index < $listLen)
		{
		    UtilList::add($this->mEnableLogTypeList, NULL);
		    $index += 1;
		}

		$this->mEnableLogTypeList[LogColor::eLC_LOG] = new MList();
		//$this->mEnableLogTypeList[(int)LogColor::eLC_LOG].Add(LogTypeId.eLogCommon);
		//$this->mEnableLogTypeList[(int)LogColor::eLC_LOG].Add(LogTypeId.eLogResLoader);
		//$this->mEnableLogTypeList[(int)LogColor::eLC_LOG].Add(LogTypeId.eLogLocalFile);
		//$this->mEnableLogTypeList[(int)LogColor::eLC_LOG].Add(LogTypeId.eLogTestRL);
		//$this->mEnableLogTypeList[(int)LogColor::eLC_LOG].Add(LogTypeId.eLogAcceleration);
		//$this->mEnableLogTypeList[(int)LogColor::eLC_LOG].Add(LogTypeId.eLogUnityCB);

		//$this->mEnableLogTypeList[(int)LogColor::eLC_LOG].Add(LogTypeId.eLogSplitMergeEmit);
		//$this->mEnableLogTypeList[(int)LogColor::eLC_LOG].Add(LogTypeId.eLogSceneInterActive);
		//$this->mEnableLogTypeList[(int)LogColor::eLC_LOG].Add(LogTypeId.eLogKBE);
		//$this->mEnableLogTypeList[(int)LogColor::eLC_LOG].Add(LogTypeId.eLogScene);
		//$this->mEnableLogTypeList[(int)LogColor::eLC_LOG].Add(LogTypeId.eLogBeingMove);
		//$this->mEnableLogTypeList[(int)LogColor::eLC_LOG].Add(LogTypeId.eLogEventRemove);
		//$this->mEnableLogTypeList[(int)LogColor::eLC_LOG].Add(LogTypeId.eLogMusicBug);
		//$this->mEnableLogTypeList[(int)LogColor::eLC_LOG].Add(LogTypeId.eLogLoadBug);
		//$this->mEnableLogTypeList[(int)LogColor::eLC_LOG].Add(LogTypeId.eLogMergeBug);
		//$this->mEnableLogTypeList[(int)LogColor::eLC_LOG].Add(LogTypeId.eLogEatBug);
		//$this->mEnableLogTypeList[(int)LogColor::eLC_LOG].Add(LogTypeId.eLogSimHitBullet);
		//$this->mEnableLogTypeList[(int)LogColor::eLC_LOG].Add(LogTypeId.eLogTwoDTerrain);
		//$this->mEnableLogTypeList[(int)LogColor::eLC_LOG].Add(LogTypeId.eLogPriorityListCheck);
		//$this->mEnableLogTypeList[(int)LogColor::eLC_LOG].Add(LogTypeId.eLogNoPriorityListCheck);
		//$this->mEnableLogTypeList[(int)LogColor::eLC_LOG].Add(LogTypeId.eLogPosSyn);
		//$this->mEnableLogTypeList[(int)LogColor::eLC_LOG].Add(LogTypeId.eLogPlaneError);
		//$this->mEnableLogTypeList[(int)LogColor::eLC_LOG].Add(LogTypeId.eLogDownload);
		//$this->mEnableLogTypeList[(int)LogColor::eLC_LOG].Add(LogTypeId.eLogAutoUpdate);
		//$this->mEnableLogTypeList[(int)LogColor::eLC_LOG].Add(LogTypeId.eLogProfileDebug);
		$this->mEnableLogTypeList[LogColor::eLC_LOG]->Add(LogTypeId::eLogProfile);

		// 编辑器日志
		//$this->mEnableLogTypeList[(int)LogColor::eLC_LOG].Add(LogTypeId.eLogEditorBuildPlayer);

		$this->mEnableLogTypeList[LogColor::eLC_WARN] = new MList();

		$this->mEnableLogTypeList[LogColor::eLC_ERROR] = new MList();
		//$this->mEnableLogTypeList[(int)LogColor::eLC_ERROR].Add(LogTypeId.eLogLoadBug);
		$this->mEnableLogTypeList[LogColor::eLC_ERROR]->Add(LogTypeId::eErrorDownload);

		$this->mEnableLog = new MList();
		$this->mEnableLog->Add(MacroDef::ENABLE_LOG);
		$this->mEnableLog->Add(MacroDef::ENABLE_WARN);
		$this->mEnableLog->Add(MacroDef::ENABLE_ERROR);

		$this->mIsOutStack = new MList();
		$this->mIsOutStack->Add(false);
		$this->mIsOutStack->Add(false);
		$this->mIsOutStack->Add(false);

		$this->mIsOutTimeStamp = new MList();
		$this->mIsOutTimeStamp->Add(false);
		$this->mIsOutTimeStamp->Add(false);
		$this->mIsOutTimeStamp->Add(false);
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
		    $this->mLogDeviceList->Add($logDevice);
		}

		if (MacroDef::ENABLE_NETLOG)
		{
		    $logDevice = new NetLogDevice();
		    $logDevice->initDevice();
		    $this->mLogDeviceList->Add($logDevice);
		}
	}

	// 注册文件日志，因为需要账号，因此需要等待输入账号后才能注册，可能多次注册
	public function registerFileLogDevice()
	{
		if (MacroDef.ENABLE_FILELOG)
		{
			unRegisterFileLogDevice();

			$logDevice = null;
			$logDevice = new FileLogDevice();
			$logDevice->setFileSuffix("AcountTest");
			$logDevice.initDevice();
			$this->mLogDeviceList.Add(logDevice);
		}
	}

	protected function unRegisterFileLogDevice()
	{
	    $index = 0;
	    $listLen = $this->mLogDeviceList->Count();
	    $item = null;
	    
	    while($index < $listLen)
		{
		    $item = $this->mLogDeviceList->get($index);
		    
		    if(LogDeviceId::eFileLogDevice == $item->getLogDeviceId())
			{
				$item->closeDevice();
				$this->mLogDeviceList.Remove($item);
				break;
			}
			
			$index += 1;
		}
	}

	protected function isInFilter($logTypeId, $logColor)
	{
	    $ret = false;
	    
		if ($this->mEnableLog[$logColor])
		{
			if ($this->mEnableLogTypeList[$logColor].Contains($logTypeId))
			{
			    $ret = true;
			}
		}

		return $ret;
	}
	
	public function log($message, $logTypeId = LogTypeId::eLogCommon)
	{
		if ($this->isInFilter($logTypeId, LogColor::eLC_LOG))
		{
			if($this->mIsOutTimeStamp[LogColor::eLC_LOG])
			{
				$message = UtilStr::Format("{0}: {1}", UtilSysLibWrap.getFormatTime(), $message);
			}

			if ($this->mIsOutStack[LogColor::eLC_LOG])
			{
			    $stackTraceArray =debug_backtrace();
			    unset($stackTraceArray[0]);
			    $traceStr = "";
			    
			    foreach($stackTraceArray as $row)
			    {
			        $traceStr .= $row['file']. ':' . $row['line'] . $row['function'] . "\r\n";
			    }
			    
				$message = UtilStr::Format("{0}\n{1}", $message, $traceStr);
			}

			if (MThread.isMainThread())
			{
				$this->logout($message, LogColor::eLC_LOG);
			}
			else
			{
				$this->asyncLog($message);
			}
		}
	}

	public function warn($message, $logTypeId = LogTypeId::eLogCommon)
	{
		if ($this->isInFilter($logTypeId, LogColor::eLC_WARN))
		{
			if ($this->mIsOutTimeStamp[LogColor::eLC_WARN])
			{
				$message = UtilStr::Format("{0}: {1}", UtilSysLibWrap.getFormatTime(), $message);
			}

			if ($this->mIsOutStack[LogColor::eLC_WARN])
			{
			    $stackTraceArray =debug_backtrace();
			    unset($stackTraceArray[0]);
			    $traceStr = "";
			    
			    foreach($stackTraceArray as $row)
			    {
			        $traceStr .= $row['file']. ':' . $row['line'] . $row['function'] . "\r\n";
			    }
			    
				$message = string.Format("{0}\n{1}", $message, $traceStr);
			}

			if (MThread.isMainThread())
			{
				$this->logout($message, LogColor::eLC_WARN);
			}
			else
			{
				$this->asyncWarn($message);
			}
		}
	}

	public function error($message, $logTypeId = LogTypeId::eLogCommon)
	{
		if ($this->isInFilter($logTypeId, LogColor::eLC_ERROR))
		{
			if ($this->mIsOutTimeStamp[LogColor::eLC_ERROR])
			{
				$message = UtilStr::Format("{0}: {1}", UtilSysLibWrap::getFormatTime(), $message);
			}

			if ($this->mIsOutStack[LogColor::eLC_ERROR])
			{
			    $stackTraceArray =debug_backtrace();
			    unset($stackTraceArray[0]);
			    $traceStr = "";
			    
			    foreach($stackTraceArray as $row)
			    {
			        $traceStr .= $row['file']. ':' . $row['line'] . $row['function'] . "\r\n";
			    }
			    
				$message = string.Format("{0}\n{1}", $message, $traceStr);
			}

			if (MThread::isMainThread())
			{
				$this->logout($message, LogColor::eLC_ERROR);
			}
			else
			{
				$this->asyncError($message);
			}
		}
	}

	// 多线程日志
	protected function asyncLog($message)
	{
		$this->mAsyncLogList.Add($message);
	}

	// 多线程日志
	protected function asyncWarn($message)
	{
		$this->mAsyncWarnList.Add($message);
	}

	// 多线程日志
	protected function asyncError($message)
	{
		$this->mAsyncErrorList.Add($message);
	}

	public function logout($message, $type = LogColor::eLC_LOG)
	{
		if (MacroDef.THREAD_CALLCHECK)
		{
			MThread.needMainThread();
		}

		$index = 0;
		$listLen = $this->mLogDeviceList.Count();
		$logDevice = null;

		while ($index < $listLen)
		{
			$logDevice = $this->mLogDeviceList[$index];
			$logDevice->logout($message, $type);

		    $index += 1;
		}
	}

	public function updateLog()
	{
		if (MacroDef.THREAD_CALLCHECK)
		{
			MThread.needMainThread();
		}

		while (($this->mTmpStr = $this->mAsyncLogList.RemoveAt(0)) != UtilStr::msDefaultStr)
		{
		    $this->logout($this->mTmpStr, LogColor::eLC_LOG);
		}

		while (($this->mTmpStr = mAsyncWarnList.RemoveAt(0)) != UtilStr::msDefaultStr)
		{
		    $this->logout($this->mTmpStr, LogColor::eLC_WARN);
		}

		while (($this->mTmpStr = mAsyncErrorList.RemoveAt(0)) != UtilStr::msDefaultStr)
		{
		    $this->logout($this->mTmpStr, LogColor::eLC_ERROR);
		}
	}

	protected function closeDevice()
	{
	    $index = 0;
	    $listLen = $this->mLogDeviceList.Count();
	    $logDevice = null;
	    
	    while($index < $listLen)
		{
		    $logDevice = $this->mLogDeviceList->get($index);
			$logDevice->closeDevice();
			
			$index += 1;
		}
	}
}

?>