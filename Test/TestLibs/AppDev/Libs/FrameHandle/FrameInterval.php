<?php

namespace SDK\Lib;

/**
 * @brief 帧间隔
 */
class FrameInterval
{
	protected $mInterval;   // 帧间隔
	protected $mTotalFrame;      // 总共帧
	protected $mCurFrame;	    // 当前帧

	public function __construct()
	{
		$this->mInterval = 1;
		$this->mTotalFrame = 0;
		$this->mCurFrame = 0;
	}

	public function setInterval($value)
	{
		$this->mInterval = $value;
	}

	public function setTotalFrame($value)
	{
		$this->mTotalFrame = $value;
	}

	public function setCurFrame($value)
	{
		$this->mCurFrame = $value;
	}

	public function canExec($delta)
	{
		$ret = false;

		$this->mTotalFrame += $delta;
		$this->mCurFrame += $delta;

		if ($this->mCurFrame >= $this->mInterval)
		{
			$ret = true;
			$this->mCurFrame -= $this->mInterval;
		}

		return $ret;
	}
}

?>