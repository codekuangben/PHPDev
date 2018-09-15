<?php

namespace SDK\Lib;

class ResEventDispatch extends EventDispatch
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