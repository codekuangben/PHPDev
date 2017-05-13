﻿<?php

namespace SDK\Lib;

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
		$this->mLoopDepth = new LoopDepth();
		$this->mLoopDepth->setZeroHandle(null, $this->processDelayObjects);
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
			if (!$this->mDeferredAddQueue->Contains($delayObject))        // 如果添加列表中没有
			{
				if ($this->mDeferredDelQueue->Contains($delayObject))     // 如果已经添加到删除列表中
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
			if (!$this->mDeferredDelQueue->Contains($delayObject))
			{
				if ($this->mDeferredAddQueue->Contains($delayObject))    // 如果已经添加到删除列表中
				{
					$this->mDeferredAddQueue->removeNoOrPriorityObject($delayObject);
				}

				$delayObject->setClientDispose(true);
				
				$this->mDeferredDelQueue->addNoOrPriorityObject($delayObject);
			}
		}
	}

	private function processDelayObjects()
	{
		$idx = 0;
		// len 是 Python 的关键字
		$elemLen = 0;

		if (!$this->mLoopDepth->isInDepth())       // 只有全部退出循环后，才能处理添加删除
		{
			if ($this->mDeferredAddQueue->Count() > 0)
			{
				$idx = 0;
				$elemLen = $this->mDeferredAddQueue->Count();

				while($idx < $elemLen)
				{
					$this->addObject($this->mDeferredAddQueue->get($idx));

					$idx += 1;
				}

				$this->mDeferredAddQueue->Clear();
			}

			if ($this->mDeferredDelQueue->Count() > 0)
			{
				$idx = 0;
				$elemLen = $this->mDeferredDelQueue->Count();

				while($idx < $elemLen)
				{
					$this->removeObject($this->mDeferredDelQueue->get($idx));

					$idx += 1;
				}

				$this->mDeferredDelQueue->Clear();
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