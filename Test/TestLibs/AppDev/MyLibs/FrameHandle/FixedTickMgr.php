<?php

namespace SDK\Lib;

class FixedTickMgr extends TickMgr
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