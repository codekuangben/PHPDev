<?php

namespace MyLibs\FrameHandle;

/**
 * @brief 时间间隔
 */
class TimeInterval
{
	protected $mInterval;
	protected $mTotalTime;
	protected $mCurTime;

	protected $mTotalExecNum;    // 总共执行次数
	protected $mCurExecNum;      // 执行的次数

	public function __construct()
	{
		$this->mInterval = 1 / 10;    // 每一秒更新 10 次
		$this->mTotalTime = 0;
		$this->mCurTime = 0;

		$this->mTotalExecNum = 0;     // 如果是 0 ，就说明没有限制
		$this->mCurExecNum = 0;
	}

	public function setInterval($value)
	{
		$this->mInterval = $value;
	}

	public function setTotalTime($value)
	{
		$this->mTotalTime = $value;
	}

	public function setCurTime($value)
	{
		$this->mCurTime = $value;
	}

	public function setTotalExecNum($value)
	{
		$this->mTotalExecNum = $value;
	}

	public function setCurExecNum($value)
	{
		$this->mCurExecNum = $value;
	}

	// 当前是否满足间隔条件
	public function canExec($delta)
	{
		$ret = false;

		$this->mTotalTime += $delta;
		$this->mCurTime += $delta;

		if($this->mCurTime >= $this->mInterval)
		{
			if (0 == $this->mTotalExecNum ||
				$this->mCurExecNum < $this->mTotalExecNum)
			{
				$this->mCurTime -= $this->mInterval;
				$ret = true;
			}
			else
			{
				$ret = false;
			}

			$this->mCurExecNum += 1;
		}

		return $ret;
	}

	// 是否执行结束
	public function isExecEnd()
	{
		$ret = false;

		if (0 != $this->mTotalExecNum &&
			$this->mCurExecNum >= $this->mTotalExecNum)
		{
			$ret = true;
		}

		return $ret;
	}
}

?>