<?php

namespace SDK\Lib;

class CallFuncObjectNoParam extends CallFuncObjectBase
{
	protected $mHandleNoParam;

	public function __construct()
	{

	}

	public function setThisAndHandleNoParam($pThis, $handle)
	{
		$this->mThis = $pThis;
		$this->mHandleNoParam = $handle;
	}

	public function clear()
	{
		$this->mHandleNoParam = null;

		parent::clear();
	}

	public function isValid()
	{
		if (null != $this->mHandleNoParam)
		{
			return true;
		}
		else
		{
			return false;
		}
	}

	public function call()
	{
		if (null != $this->mHandleNoParam)
		{
			$this->mHandleNoParam();
		}
	}
}

?>