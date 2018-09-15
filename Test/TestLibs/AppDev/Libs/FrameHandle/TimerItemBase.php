<?php

namespace SDK\Lib;

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
	public $mTimerDisp;       // 定时器分发
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
		$this->mTimerDisp = new TimerFunctionObject();
		$this->mDisposed = false;
		$this->mIsContinuous = false;
	}

	public function setFuncObject($handle)
	{
		$this->mTimerDisp.setFuncObject($handle);
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
				$this->disposeAndDisp();
			}
			else
			{
				$this->checkAndDisp();
			}
		}
	}

	public function disposeAndDisp()
	{
		if ($this->mIsContinuous)
		{
			$this->continueDisposeAndDisp();
		}
		else
		{
			$this->discontinueDisposeAndDisp();
		}
	}

	protected function continueDisposeAndDisp()
	{
		$this->mDisposed = true;

		while ($this->mIntervalLeftTime >= $this->mInternal && $this->mCurCallTime < $this->mTotalTime)
		{
			$this->mCurCallTime = $this->mCurCallTime + $this->mInternal;
			$this->mIntervalLeftTime = $this->mIntervalLeftTime - $this->mInternal;
			$this->onPreCallBack();

			if ($this->mTimerDisp.isValid())
			{
				$this->mTimerDisp.call($this);
			}
		}
	}

	protected function discontinueDisposeAndDisp()
	{
		$this->mDisposed = true;
		$this->mCurCallTime = $this->mTotalTime;
		$this->onPreCallBack();

		if ($this->mTimerDisp.isValid())
		{
			$this->mTimerDisp.call($this);
		}
	}

	public function checkAndDisp()
	{
		if($this->mIsContinuous)
		{
			$this->continueCheckAndDisp();
		}
		else
		{
			$this->discontinueCheckAndDisp();
		}
	}

	// 连续的定时器
	protected function continueCheckAndDisp()
	{
		while ($this->mIntervalLeftTime >= $this->mInternal)
		{
			// 这个地方 m_curCallTime 肯定会小于 m_totalTime，因为在调用这个函数的外部已经进行了判断
			$this->mCurCallTime = $this->mCurCallTime + $this->mInternal;
			$this->mIntervalLeftTime = $this->mIntervalLeftTime - $this->mInternal;
			$this->onPreCallBack();

			if ($this->mTimerDisp.isValid())
			{
				$this->mTimerDisp.call($this);
			}
		}
	}

	// 不连续的定时器
	protected function discontinueCheckAndDisp()
	{
		if ($this->mIntervalLeftTime >= $this->mInternal)
		{
			// 这个地方 m_curCallTime 肯定会小于 m_totalTime，因为在调用这个函数的外部已经进行了判断
			$this->mCurCallTime = $this->mCurCallTime + (((int)($this->mIntervalLeftTime / $this->mInternal)) * $this->mInternal);
			$this->mIntervalLeftTime = $this->mIntervalLeftTime % $this->mInternal;   // 只保留余数
			$this->onPreCallBack();

			if ($this->mTimerDisp.isValid())
			{
				$this->mTimerDisp.call($this);
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
		Ctx::$mInstance->mTimerMgr->addTimer($this);
	}

	public function stopTimer()
	{
		Ctx::$mInstance->mTimerMgr->removeTimer($this);
	}
}

?>