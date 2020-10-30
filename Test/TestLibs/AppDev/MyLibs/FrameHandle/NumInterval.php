<?php

namespace MyLibs\FrameHandle;

/**
 * @brief 数字间隔
 */
class NumInterval
{
	protected $mTotalValue;
	protected $mCurValue;

	protected $mNumIntervalMode;

	public function __construct()
	{
		$this->mTotalValue = 0;
		$this->mCurValue = 0;

		$this->mNumIntervalMode = NumIntervalMode::eNIM_Inc;
	}

	public function setTotalValue($value)
	{
		$this->mTotalValue = $value;
	}

	public function setCurValue($value)
	{
		$this->mCurValue = $value;
	}

	public function reset()
	{
		if (NumIntervalMode::eNIM_Inc == $this->mNumIntervalMode)
		{
			$this->mCurValue = 0;
		}
		else
		{
			$this->mCurValue = $this->mTotalValue;
		}
	}

	public function canExec($delta)
	{
		$ret = false;

		if (NumIntervalMode::eNIM_Inc == $this->mNumIntervalMode)
		{
			$this->mCurValue += $delta;

			if ($this->mCurValue <= $this->mTotalValue)
			{
				$ret = true;
			}
		}
		else
		{
			$this->mCurValue -= $delta;

			if ($this->mCurValue >= 0)
			{
				$ret = true;
			}
		}

		return $ret;
	}
}

?>