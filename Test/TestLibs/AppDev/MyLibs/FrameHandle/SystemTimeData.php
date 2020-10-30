<?php

namespace MyLibs\FrameHandle;

use MyLibs\FrameWork\Ctx;
use MyLibs\Tools\UtilSysLibWrap;
use MyLibs\Tools\UtilTime;

class SystemTimeData
{
	protected $mPreTime;            // 上一次更新时的秒数
	protected $mCurTime;            // 正在获取的时间
	protected $mDeltaSec;          // 两帧之间的间隔
	protected $mIsFixFrameRate;     // 固定帧率
	protected $mFixFrameRate;      // 固定帧率
	protected $mScale;             // delta 缩放

	protected $mServerBaseTime;     // 服务器时间(毫秒)
	protected $mServerRelTime;      // 相对于基础值的相对值

	// Edit->Project Setting->time
	protected $mFixedTimestep;

	public function __construct()
	{
		$this->mPreTime = 0;
		$this->mCurTime = 0;
		$this->mDeltaSec = 0.0;
		$this->mIsFixFrameRate = false;
		$this->mFixFrameRate = 0.0417;       //  1 / 24;
		$this->mScale = 1;

		$this->mFixedTimestep = 0.02;
		$this->mServerBaseTime = 0;
	}

	public function getDeltaSec()
	{
		return $this->mDeltaSec;
	}
	
	public function setDeltaSec($value)
	{
		$this->mDeltaSec = $value;
	}

	public function getFixedTimestep()
	{
	    if (Ctx::$msIns->mCfg->mIsActorMoveUseFixUpdate)
		{
			return $this->mFixedTimestep;
		}
		else
		{
			return $this->mDeltaSec;
		}
	}

	// 获取固定帧率时间间隔
	public function getFixFrameRateInterval()
	{
		return $this->mFixFrameRate;
	}

	public function getCurTime()
	{
		return $this->mCurTime;
	}
	
	public function setCurTime($value)
	{
		$this->mCurTime = $value;
	}

	public function nextFrame()
	{
		$this->mPreTime = $this->mCurTime;
		$this->mCurTime = UtilSysLibWrap::getUTCSec();

		if ($this->mIsFixFrameRate)
		{
			$this->mDeltaSec = $this->mFixFrameRate;        // 每秒 24 帧
		}
		else
		{
			if ($this->mPreTime != 0)     // 第一帧跳过，因为这一帧不好计算间隔
			{
				$this->mDeltaSec = $this->mCurTime - $this->mPreTime;
			}
			else
			{
				$this->mDeltaSec = $this->mFixFrameRate;        // 每秒 24 帧
			}
		}

		$this->mDeltaSec *= $this->mScale;
	}

	// 服务器传递过来的是毫秒，本地存储的是秒
	public function setServerTime($value)
	{
		$this->mServerBaseTime = $value;
		$this->mServerRelTime = UtilTime::getTimeStamp();
	}

	// 获取服务器毫秒时间
	public function getServerSecTime()
	{
		return getServerMilliSecTime() / 1000.0;
	}

	// 获取服务器秒时间
	public function getServerMilliSecTime()
	{
		return $this->mServerBaseTime + ($this->mCurTime - $this->mServerRelTime);
	}
}

?>