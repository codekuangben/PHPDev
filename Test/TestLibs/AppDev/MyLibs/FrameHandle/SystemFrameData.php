<?php

namespace MyLibs\FrameHandle;

class SystemFrameData
{
	protected $mTotalFrameCount;       // 总帧数
	protected $mCurFrameCount;         // 当前帧数
	protected $mCurTime;          // 当前一秒内时间
	protected $mFps;                // 帧率
	
	public function __construct()
	{
		
	}

	public function getTotalFrameCount()
	{
		return $this->mTotalFrameCount;
	}

	public function nextFrame($delta)
	{
		$this->mTotalFrameCount += 1;
		$this->mCurFrameCount += 1;
		$this->mCurTime += $delta;

		if($this->mCurTime > 1.0)
		{
			$this->mFps = (int)($this->mCurFrameCount / $this->mCurTime);
			$this->mCurFrameCount = 0;
			$this->mCurTime = 0;
		}
	}
}

?>