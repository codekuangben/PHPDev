<?php

namespace SDK\Lib;

class LoopDepth
{
	private $mLoopDepth;         // 是否在循环中，支持多层嵌套，就是循环中再次调用循环
	private $mIncHandle;     // 增加处理器
	private $mDecHandle;     // 减少处理器
	private $mZeroHandle;    // 减少到 0 处理器

	public function __construct()
	{
		$this->mLoopDepth = 0;
		$this->mIncHandle = null;
		$this->mDecHandle = null;
		$this->mZeroHandle = null;
	}

	public function setIncHandle($pThis, $value)
	{
		if(null == $this->mIncHandle)
		{
			$this->mIncHandle = new CallFuncObjectNoParam();
		}

		$this->mIncHandle.setThisAndHandleNoParam(pThis, value);
	}

	public function setDecHandle($pThis, $value)
	{
		if (null == $this->mDecHandle)
		{
			$this->mDecHandle = new CallFuncObjectNoParam();
		}

		$this->mDecHandle.setThisAndHandleNoParam(pThis, value);
	}

	public function setZeroHandle($pThis, $value)
	{
		if (null == $this->mZeroHandle)
		{
			$this->mZeroHandle = new CallFuncObjectNoParam();
		}

		$this->mZeroHandle.setThisAndHandleNoParam(pThis, value);
	}

	public function incDepth()
	{
		++$this->mLoopDepth;

		if(null != $this->mIncHandle)
		{
			$this->mIncHandle.call();
		}
	}

	public function decDepth()
	{
		--$this->mLoopDepth;

		if (null != $this->mDecHandle)
		{
			$this->mDecHandle.call();
		}

		if(0 == $this->mLoopDepth)
		{
			if (null != $this->mZeroHandle)
			{
				$this->mZeroHandle.call();
			}
		}

		if($this->mLoopDepth < 0)
		{
			$this->mLoopDepth = 0;
			// 错误，不对称
			UnityEngine.Debug.LogError("LoopDepth::decDepth, Error !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!");
		}
	}

	public function isInDepth()
	{
		return $this->mLoopDepth > 0;
	}
}

?>