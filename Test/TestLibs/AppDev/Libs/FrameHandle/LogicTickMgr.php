<?php

namespace SDK\Lib;

/**
 * @brief 逻辑心跳管理器
 */
class LogicTickMgr extends TickMgr
{
	protected $mTimeInterval;

	public function __construct()
	{
		$this->mTimeInterval = new TimeInterval();
	}

	protected function onExecAdvance($delta, $tickMode)
	{
		if($this->mTimeInterval.canExec($delta))
		{
			base.onExecAdvance($delta, $tickMode);
		}
	}
}

?>