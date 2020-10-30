<?php

namespace MyLibs\FrameHandle;

/**
 * @brief 时间段
 */
class TimeSegment
{
	protected $mSegment;
	protected $mInverseSegment;
	protected $mTotalTime;
	protected $mCurTime;

	public function __construct()
	{
		$this->mSegment = 1;
		$this->mInverseSegment = 1;
		$this->mTotalTime = 0;
		$this->mCurTime = 0;
	}

	public function setSegment($value)
	{
		$this->mSegment = $value;
		$this->mInverseSegment = 1 / $this->mSegment;
	}

	public function setTotalTime($value)
	{
		$this->mTotalTime = $value;
	}

	public function setCurTime($value)
	{
		$this->mCurTime = $value;
	}

	// 当前是否满足间隔条件
	public function canExec($delta)
	{
		$ret = false;

		$this->mTotalTime += $delta;
		$this->mCurTime += $delta;

		if ($this->mCurTime < $this->mSegment)
		{
			$ret = true;
		}

		return $ret;
	}

	// 获取剩余百分比
	public function getLeftPercent()
	{
		$percent = 0;

		if($this->mCurTime < $this->mSegment)
		{
			$percent = ($this->mSegment - $this->mCurTime) * $this->mInverseSegment;
		}

		return $percent;
	}
}

?>