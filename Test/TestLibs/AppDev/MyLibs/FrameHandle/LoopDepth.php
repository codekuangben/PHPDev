<?php

namespace MyLibs;

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

	public function setIncHandle($eventListener, $value)
	{
		if(null == $this->mIncHandle)
		{
		    $this->mIncHandle = new EventDispatchFunctionObject();
		}

		$this->mIncHandle->setFuncObject($eventListener, $value);
	}

	public function setDecHandle($eventListener, $value)
	{
		if (null == $this->mDecHandle)
		{
		    $this->mDecHandle = new EventDispatchFunctionObject();
		}

		$this->mDecHandle->setFuncObject($eventListener, $value);
	}

	public function setZeroHandle($eventListener, $value)
	{
		if (null == $this->mZeroHandle)
		{
		    $this->mZeroHandle = new EventDispatchFunctionObject();
		}

		$this->mZeroHandle->setFuncObject($eventListener, $value);
	}

	public function incDepth()
	{
		++$this->mLoopDepth;

		if(null != $this->mIncHandle)
		{
			$this->mIncHandle->call();
		}
	}

	public function decDepth()
	{
		--$this->mLoopDepth;

		if (null != $this->mDecHandle)
		{
		    $this->mDecHandle->call(null);
		}

		if(0 == $this->mLoopDepth)
		{
			if (null != $this->mZeroHandle)
			{
			    $this->mZeroHandle->call(null);
			}
		}

		if($this->mLoopDepth < 0)
		{
			$this->mLoopDepth = 0;
			// 错误，不对称
			throw \ErrorException("LoopDepth::decDepth, Error !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!");
		}
	}

	public function isInDepth()
	{
		return $this->mLoopDepth > 0;
	}
}

?>