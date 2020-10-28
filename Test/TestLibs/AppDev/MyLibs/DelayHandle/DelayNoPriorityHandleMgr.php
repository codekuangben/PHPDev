<?php

namespace MyLibs;

/**
 * @brief 延迟优先级处理管理器
 */
class DelayNoPriorityHandleMgr extends DelayNoOrPriorityHandleMgr
{
	public function __construct()
	{
		$this->mDeferredAddQueue = new NoPriorityList();
		$this->mDeferredAddQueue->setIsSpeedUpFind(true);
		$this->mDeferredDelQueue = new NoPriorityList();
		$this->mDeferredDelQueue->setIsSpeedUpFind(true);

		$this->mNoOrPriorityList = new NoPriorityList();
		$this->mNoOrPriorityList->setIsSpeedUpFind(true);
	}

	public function init()
	{
		parent::init();
	}

	public function dispose()
	{
		parent::dispose();
	}

	public function addNoPriorityObject($priorityObject)
	{
		$this->addNoOrPriorityObject($priorityObject);
	}

	public function removeNoPriorityObject($tickObj)
	{
		$this->removeNoOrPriorityObject($tickObj);
	}
}

?>