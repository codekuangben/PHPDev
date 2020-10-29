<?php

namespace MyLibs;

class AddOnceAndCallOnceEventDispatch extends EventDispatch
{
    public function __construct()
    {
        parent::__construct();
    }
    
	public function addEventHandle($eventListener, $eventHandle, $eventId = 0)
	{
		if (!$this->isExistEventHandle($eventListener, $eventHandle, $eventId))
		{
			parent::addEventHandle($eventListener, $eventHandle, $eventId);
		}
	}

	public function dispatchEvent($dispatchObject)
	{
		parent::dispatchEvent($dispatchObject);

		$this->clearEventHandle();
	}
}

?>