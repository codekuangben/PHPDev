<?php

namespace SDK\Lib;

/**
 * @brief 一次事件分发，分发一次就清理
 */
class CallOnceEventDispatch extends EventDispatch
{
	public function __construct()
	{
	    Parent::__construct();
	}

	public function dispatchEvent($dispatchObject)
	{
		parent::dispatchEvent($dispatchObject);

		$this->clearEventHandle();
	}
}

?>