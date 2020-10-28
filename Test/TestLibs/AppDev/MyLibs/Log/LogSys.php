<?php

namespace MyLibs;

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
		//$this->mEnableLogTypeList[(int)LogColor::eLC_LOG]->add(LogTypeId::eLogCommon);
		//$this->mEnableLogTypeList[(int)LogColor::eLC_LOG]->add(LogTypeId::eLogLocalFile);
		//$this->mEnableLogTypeList[(int)LogColor::eLC_LOG]->add(LogTypeId::eLogEventRemove);
		//$this->mEnableLogTypeList[(int)LogColor::eLC_LOG]->add(LogTypeId::eLogPriorityListCheck);
		//$this->mEnableLogTypeList[(int)LogColor::eLC_LOG]->add(LogTypeId::eLogNoPriorityListCheck);
		//$this->mEnableLogTypeList[(int)LogColor::eLC_LOG]->add(LogTypeId::eLogPosSyn);
		//$this->mEnableLogTypeList[(int)LogColor::eLC_LOG]->add(LogTypeId::eLogProfileDebug);
		$this->mEnableLogTypeList[LogColor::eLC_LOG]->add(LogTypeId::eLogProfile);

		$this->mEnableLogTypeList[LogColor::eLC_WARN] = new MList();

		$this->mEnableLogTypeList[LogColor::eLC_ERROR] = new MList();
		//$this->mEnableLogTypeList[(int)LogColor::eLC_ERROR]->add(LogTypeId::eLogLoadBug);

		$this->mEnableLog = new MList();
		$this->mEnableLog->add(MacroDef::ENABLE_LOG);
		$this->mEnableLog->add(MacroDef::ENABLE_WARN);
		$this->mEnableLog->add(MacroDef::ENABLE_ERROR);

		$this->mIsOutStack = new MList();
		$this->mIsOutStack->add(false);
		$this->mIsOutStack->add(false);
		$this->mIsOutStack->add(false);

		$this->mIsOutTimeStamp = new MList();
		$this->mIsOutTimeStamp->add(false);
		$this->mIsOutTimeStamp->add(false);
		$this->mIsOutTimeStamp->add(false);
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
		$this->mEnableLog->set(LogColor::eLC_LOG, value);
	}

	public function setEnableWarn($value)
	{
	    $this->mEnableLog->set(LogColor::eLC_WARN, $value);
	}

	public function setEnableError($value)
	{
	    $this->mEnableLog->set(LogColor::eLC_ERROR, $value);
	}

	protected function registerDevice()
	{
		$logDevice = null;

		if (MacroDef::ENABLE_WINLOG)
		{
		    $logDevice = new WinLogDevice();
		    $logDevice->initDevice();
		    $this->mLogDeviceList->add($logDevice);
		}

		if (MacroDef::ENABLE_NETLOG)
		{
		    $logDevice = new NetLogDevice();
		    $logDevice->initDevice();
		    $this->mLogDeviceList->add($logDevice);
		}
	}

	// 注册文件日志，因为需要账号，因此需要等待输入账号后才能注册，可能多次注册
	public function registerFileLogDevice()
	{
		if (MacroDef::ENABLE_FILELOG)
		{
			$this->unRegisterFileLogDevice();

			$logDevice = null;
			$logDevice = new FileLogDevice();
			$logDevice->setFileSuffix("AcountTest");
			$logDevice->initDevice();
			$this->mLogDeviceList->add(logDevice);
		}
	}

	protected function unRegisterFileLogDevice()
	{
	    $index = 0;
	    $listLen = $this->mLogDeviceList->count();
	    $item = null;
	    
	    while($index < $listLen)
		{
		    $item = $this->mLogDeviceList->get($index);
		    
		    if(LogDeviceId::eFileLogDevice == $item->getLogDeviceId())
			{
				$item->closeDevice();
				$this->mLogDeviceList->remove($item);
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
			if ($this->mEnableLogTypeList[$logColor]->contains($logTypeId))
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
				$message = UtilStr::Format("{0}: {1}", UtilSysLibWrap::getFormatTime(), $message);
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

			if (MThread::isMainThread())
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
				$message = UtilStr::Format("{0}: {1}", UtilSysLibWrap::getFormatTime(), $message);
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

			if (MThread::isMainThread())
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
		$this->mAsyncLogList->add($message);
	}

	// 多线程日志
	protected function asyncWarn($message)
	{
		$this->mAsyncWarnList->add($message);
	}

	// 多线程日志
	protected function asyncError($message)
	{
		$this->mAsyncErrorList->add($message);
	}

	public function logout($message, $type = LogColor::eLC_LOG)
	{
		if (MacroDef::THREAD_CALLCHECK)
		{
			MThread::needMainThread();
		}

		$index = 0;
		$listLen = $this->mLogDeviceList->count();
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
		if (MacroDef::THREAD_CALLCHECK)
		{
			MThread::needMainThread();
		}

		while (($this->mTmpStr = $this->mAsyncLogList->removeAt(0)) != UtilStr::msDefaultStr)
		{
		    $this->logout($this->mTmpStr, LogColor::eLC_LOG);
		}

		while (($this->mTmpStr = $this->mAsyncWarnList->removeAt(0)) != UtilStr::msDefaultStr)
		{
		    $this->logout($this->mTmpStr, LogColor::eLC_WARN);
		}

		while (($this->mTmpStr = $this->mAsyncErrorList->removeAt(0)) != UtilStr::msDefaultStr)
		{
		    $this->logout($this->mTmpStr, LogColor::eLC_ERROR);
		}
	}

	protected function closeDevice()
	{
	    $index = 0;
	    $listLen = $this->mLogDeviceList->count();
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