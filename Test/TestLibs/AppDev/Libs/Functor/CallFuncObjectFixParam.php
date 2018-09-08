<?php

namespace SDK\Lib;

class CallFuncObjectFixParam extends CallFuncObjectBase
{
	protected $mHandle;
	protected $mParam;

	public function __construct()
	{
		$this->mHandle = null;
		$this->mParam = null;
	}

	public function clear()
	{
		$this->mThis = null;
	}

	public function isValid()
	{
		if (null != $this->mHandle)
		{
			return true;
		}
		else
		{
			return false;
		}
	}

	public function setPThisAndHandle($pThis, $handle, $param)
	{
		parent::setPThisAndHandle($pThis, $handle, $param);

		$this->mHandle = handle;
		$this->mParam = param;
	}

	public function call()
	{
		if (null != $this->mHandle)
		{
			if(null != $this->mThis)
			{
				call_user_func(array($this->mThis, $this->mHandle), $this->mParam);
			}
			else
			{
				call_user_func($this->mHandle, $this->mParam);
			}
		}
	}
}

?>