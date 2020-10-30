<?php

namespace MyLibs\Thread;

/**
 * @同步使用的 Event
 */
class MEvent
{
	private $mEvent;

	public function __construct($initialState)
	{
		//$this->mEvent = new ManualResetEvent(initialState);
	}

	public function WaitOne()
	{
	    $this->mEvent->WaitOne();
	}

	public function Reset()
	{
	    return $this->mEvent->Reset();
	}

	public function Set()
	{
	    return $this->mEvent->Set();
	}
}

?>