<?php

namespace SDK\Lib;

class AddOnceAndCallOnceEventDispatch extends EventDispatch
{
	public function addEventHandle($pThis, $handle, $eventId = 0)
	{
		if (!$this->isExistEventHandle($pThis, $handle, $eventId))
		{
			parent::addEventHandle($pThis, $handle, $eventId);
		}
	}

	public function dispatchEvent($dispatchObject)
	{
		parent::dispatchEvent($dispatchObject);

		$this->clearEventHandle();
	}
}

?>