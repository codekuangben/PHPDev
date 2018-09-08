<?php

namespace SDK\Lib;

class CallFuncObjectBase
{
	protected $mThis;

	public function __construct()
	{
		$this->mThis = null;
	}

	public function setPThisAndHandle($pThis, $handle, $param)
	{
		$this->mThis = $pThis;
	}

	public function setThisAndHandleNoParam($pThis, $handle)
	{

	}

	public function clear()
	{
		$this->mThis = null;
	}

	public function isValid()
	{
		return false;
	}

	public function call()
	{

	}
}

?>