<?php

namespace MyLibs\FrameHandle;

use MyLibs\FrameWork\Ctx;
use MyLibs\DelayHandle\IDelayHandleItem;
use MyLibs\EventHandle\EventDispatchFunctionObject;
use MyLibs\EventHandle\IDispatchObject;

/**
 * @brief 定时器，这个是不断增长的
 */
class TimerItemBase implements IDelayHandleItem, IDispatchObject
{
	public $mInternal;        // 定时器间隔
	public $mTotalTime;       // 总共定时器时间
	public $mCurRunTime;      // 当前定时器运行的时间
	public $mCurCallTime;     // 当前定时器已经调用的时间
	public $mIsInfineLoop;      // 是否是无限循环
	public $mIntervalLeftTime;     // 定时器调用间隔剩余时间
	public $mTimerEventDispatch;       // 定时器分发
	public $mDisposed;             // 是否已经被释放
	public $mIsContinuous;          // 是否是连续的定时器

	public function __construct()
	{
		$this->mInternal = 1;
		$this->mTotalTime = 1;
		$this->mCurRunTime = 0;
		$this->mCurCallTime = 0;
		$this->mIsInfineLoop = false;
		$this->mIntervalLeftTime = 0;
		$this->mTimerEventDispatch = new EventDispatchFunctionObject();
		$this->mDisposed = false;
		$this->mIsContinuous = false;
	}

	public function setFuncObject($eventListener, $eventHandle)
	{
	    $this->mTimerEventDispatch->setFuncObject($eventListener, $eventHandle);
	}

	public function setTotalTime($value)
	{
		$this->mTotalTime = $value;
	}

	public function getRunTime()
	{
		return $this->mCurRunTime;
	}

	public function getCallTime()
	{
		return $this->mCurCallTime;
	}

	public function getLeftRunTime()
	{
		return $this->mTotalTime - $this->mCurRunTime;
	}

	public function getLeftCallTime()
	{
		return $this->mTotalTime - $this->mCurCallTime;
	}

	// 在调用回调函数之前处理
	protected function onPreCallBack()
	{

	}

	public function OnTimer($delta)
	{
		if ($this->mDisposed)
		{
			return;
		}

		$this->mCurRunTime += $delta;
		if ($this->mCurRunTime > $this->mTotalTime)
		{
			$this->mCurRunTime = $this->mTotalTime;
		}
		$this->mIntervalLeftTime += $delta;

		if ($this->mIsInfineLoop)
		{
			$this->checkAndDisp();
		}
		else
		{
			if ($this->mCurRunTime >= $this->mTotalTime)
			{
				$this->disposeAndDispatch();
			}
			else
			{
				$this->checkAndDisp();
			}
		}
	}

	public function disposeAndDispatch()
	{
		if ($this->mIsContinuous)
		{
			$this->continueDisposeAndDispatch();
		}
		else
		{
			$this->discontinueDisposeAndDispatch();
		}
	}

	protected function continueDisposeAndDispatch()
	{
		$this->mDisposed = true;

		while ($this->mIntervalLeftTime >= $this->mInternal && $this->mCurCallTime < $this->mTotalTime)
		{
			$this->mCurCallTime = $this->mCurCallTime + $this->mInternal;
			$this->mIntervalLeftTime = $this->mIntervalLeftTime - $this->mInternal;
			$this->onPreCallBack();

			if ($this->mTimerEventDispatch->isValid())
			{
				$this->mTimerEventDispatch->call($this);
			}
		}
	}

	protected function discontinueDisposeAndDispatch()
	{
		$this->mDisposed = true;
		$this->mCurCallTime = $this->mTotalTime;
		$this->onPreCallBack();

		if ($this->mTimerEventDispatch->isValid())
		{
			$this->mTimerEventDispatch->call($this);
		}
	}

	public function checkAndDisp()
	{
		if($this->mIsContinuous)
		{
			$this->continueCheckAndDispatch();
		}
		else
		{
			$this->discontinueCheckAndDispatch();
		}
	}

	// 连续的定时器
	protected function continueCheckAndDispatch()
	{
		while ($this->mIntervalLeftTime >= $this->mInternal)
		{
			// 这个地方 m_curCallTime 肯定会小于 m_totalTime，因为在调用这个函数的外部已经进行了判断
			$this->mCurCallTime = $this->mCurCallTime + $this->mInternal;
			$this->mIntervalLeftTime = $this->mIntervalLeftTime - $this->mInternal;
			$this->onPreCallBack();

			if ($this->mTimerEventDispatch->isValid())
			{
				$this->mTimerEventDispatch->call($this);
			}
		}
	}

	// 不连续的定时器
	protected function discontinueCheckAndDispatch()
	{
		if ($this->mIntervalLeftTime >= $this->mInternal)
		{
			// 这个地方 m_curCallTime 肯定会小于 m_totalTime，因为在调用这个函数的外部已经进行了判断
			$this->mCurCallTime = $this->mCurCallTime + (((int)($this->mIntervalLeftTime / $this->mInternal)) * $this->mInternal);
			$this->mIntervalLeftTime = $this->mIntervalLeftTime % $this->mInternal;   // 只保留余数
			$this->onPreCallBack();

			if ($this->mTimerEventDispatch->isValid())
			{
				$this->mTimerEventDispatch->call($this);
			}
		}
	}

	public function reset()
	{
		$this->mCurRunTime = 0;
		$this->mCurCallTime = 0;
		$this->mIntervalLeftTime = 0;
		$this->mDisposed = false;
	}

	public function setClientDispose($isDispose)
	{

	}

	public function isClientDispose()
	{
		return false;
	}

	public function startTimer()
	{
		Ctx::$msIns->mTimerMgr->addTimer($this);
	}

	public function stopTimer()
	{
		Ctx::$msIns->mTimerMgr->removeTimer($this);
	}
}

?>