<?php

namespace SDK\Lib;

/**
 * @brief 时间段
 */
public class TimeSegment
{
	protected float mSegment;
	protected float mInverseSegment;
	protected float mTotalTime;
	protected float mCurTime;

	public TimeSegment()
	{
		$this->mSegment = 1;
		$this->mInverseSegment = 1;
		$this->mTotalTime = 0;
		$this->mCurTime = 0;
	}

	public void setSegment(float value)
	{
		$this->mSegment = value;
		$this->mInverseSegment = 1 / $this->mSegment;
	}

	public void setTotalTime(float value)
	{
		$this->mTotalTime = value;
	}

	public void setCurTime(float value)
	{
		$this->mCurTime = value;
	}

	// 当前是否满足间隔条件
	public bool canExec(float delta)
	{
		bool ret = false;

		$this->mTotalTime += delta;
		$this->mCurTime += delta;

		if ($this->mCurTime < $this->mSegment)
		{
			ret = true;
		}

		return ret;
	}

	// 获取剩余百分比
	public float getLeftPercent()
	{
		float percent = 0;

		if($this->mCurTime < $this->mSegment)
		{
			percent = ($this->mSegment - $this->mCurTime) * $this->mInverseSegment;
		}

		return percent;
	}
}

?>