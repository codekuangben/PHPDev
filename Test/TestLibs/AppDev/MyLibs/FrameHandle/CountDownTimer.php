<?php

namespace MyLibs;

/**
 * @brief 倒计时定时器
 */
class CountDownTimer extends TimerItemBase
{
	public function __construct()
	{
		
	}
	
	public function setTotalTime($value)
	{
		parent::setTotalTime($value);
		$this->mCurRunTime = $value;
	}

	public function getRunTime()
	{
		return $this->mTotalTime - $this->mCurRunTime;
	}

	// 如果要获取剩余的倒计时时间，使用 getLeftCallTime
	public function getLeftRunTime()
	{
		return $this->mCurRunTime;
	}

	public function OnTimer($delta)
	{
		if ($this->mDisposed)
		{
			return;
		}

		$this->mCurRunTime -= $delta;
		if($this->mCurRunTime < 0)
		{
			$this->mCurRunTime = 0;
		}
		$this->mIntervalLeftTime += $delta;

		if ($this->mIsInfineLoop)
		{
			$this->checkAndDisp();
		}
		else
		{
			if ($this->mCurRunTime <= 0)
			{
				$this->disposeAndDispatch();
			}
			else
			{
				$this->checkAndDisp();
			}
		}
	}

	public function reset()
	{
		$this->mCurRunTime = $this->mTotalTime;
		$this->mCurCallTime = 0;
		$this->mIntervalLeftTime = 0;
		$this->mDisposed = false;
	}
}

?>