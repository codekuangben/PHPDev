<?php

namespace MyLibs;

/**
 * @brief 对应事件 LateUpdate
 */
class LateTickMgr extends TickMgr
{
	public function __construct()
	{

	}

	protected function onExecAdvance($delta, $tickMode)
	{
		parent::onExecAdvance($delta, $tickMode);
	}
}

?>