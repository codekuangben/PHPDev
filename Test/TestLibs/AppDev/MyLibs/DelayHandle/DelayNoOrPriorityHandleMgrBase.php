<?php

namespace MyLibs\DelayHandle;

use MyLibs\Base\GObject;
use MyLibs\FrameHandle\LoopDepth;

/**
 * @brief 当需要管理的对象可能在遍历中间添加的时候，需要这个管理器
 */
class DelayNoOrPriorityHandleMgrBase extends GObject
{
	protected $mDeferredAddQueue;
	protected $mDeferredDelQueue;

	protected $mLoopDepth;           // 是否在循环中，支持多层嵌套，就是循环中再次调用循环

	public function __construct()
	{
	    Parent::__construct();
	    
		$this->mLoopDepth = new LoopDepth();
		$this->mLoopDepth->setZeroHandle($this, "processDelayObjects");
	}

	public function init()
	{

	}

	public function dispose()
	{

	}

	protected function addObject($delayObject, $priority = 0.0)
	{
		if($this->mLoopDepth->isInDepth())
		{
			if (!$this->mDeferredAddQueue->contains($delayObject))        // 如果添加列表中没有
			{
				if ($this->mDeferredDelQueue->contains($delayObject))     // 如果已经添加到删除列表中
				{
					$this->mDeferredDelQueue->removeNoOrPriorityObject($delayObject);
				}
				
				$this->mDeferredAddQueue->addNoOrPriorityObject($delayObject);
			}
		}
	}

	protected function removeObject($delayObject)
	{
		if ($this->mLoopDepth->isInDepth())
		{
			if (!$this->mDeferredDelQueue->contains($delayObject))
			{
				if ($this->mDeferredAddQueue->contains($delayObject))    // 如果已经添加到删除列表中
				{
					$this->mDeferredAddQueue->removeNoOrPriorityObject($delayObject);
				}

				$delayObject->setClientDispose(true);
				
				$this->mDeferredDelQueue->addNoOrPriorityObject($delayObject);
			}
		}
	}

	public function processDelayObjects($dispatchObject, $eventId)
	{
		$idx = 0;
		// len 是 Python 的关键字
		$elemLen = 0;

		if (!$this->mLoopDepth->isInDepth())       // 只有全部退出循环后，才能处理添加删除
		{
			if ($this->mDeferredAddQueue->count() > 0)
			{
				$idx = 0;
				$elemLen = $this->mDeferredAddQueue->count();

				while($idx < $elemLen)
				{
					$this->addObject($this->mDeferredAddQueue->get($idx));

					$idx += 1;
				}

				$this->mDeferredAddQueue->clear();
			}

			if ($this->mDeferredDelQueue->count() > 0)
			{
				$idx = 0;
				$elemLen = $this->mDeferredDelQueue->count();

				while($idx < $elemLen)
				{
					$this->removeObject($this->mDeferredDelQueue->get($idx));

					$idx += 1;
				}

				$this->mDeferredDelQueue->clear();
			}
		}
	}

	protected function incDepth()
	{
		$this->mLoopDepth->incDepth();
	}

	protected function decDepth()
	{
		$this->mLoopDepth->decDepth();
	}

	protected function isInDepth()
	{
		return $this->mLoopDepth->isInDepth();
	}
}

?>