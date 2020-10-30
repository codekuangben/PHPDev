<?php

namespace MyLibs\DelayHandle;

use MyLibs\DataStruct\NoOrPriorityList\NoPriorityList\NoPriorityList;
use MyLibs\DataStruct\NoOrPriorityList\PriorityList\PriorityList;

/**
 * @brief 延迟优先级处理管理器
 */
class DelayPriorityHandleMgr extends DelayNoOrPriorityHandleMgr
{
	public function __construct()
	{
	    parent::__construct();
	    
		$this->mDeferredAddQueue = new NoPriorityList();
		$this->mDeferredAddQueue->setIsSpeedUpFind(true);
		$this->mDeferredDelQueue = new NoPriorityList();
		$this->mDeferredDelQueue->setIsSpeedUpFind(true);

		$this->mNoOrPriorityList = new PriorityList();
		$this->mNoOrPriorityList->setIsSpeedUpFind(true);
		$this->mNoOrPriorityList->setIsOpKeepSort(true);
	}

	public function init()
	{
		parent::init();
	}

	public function dispose()
	{
		parent::dispose();
	}

	public function addPriorityObject($priorityObject, $priority = 0.0)
	{
		$this->addNoOrPriorityObject(priorityObject, priority);
	}

	public function removePriorityObject($tickObj)
	{
		$this->removeNoOrPriorityObject($tickObj);
	}
}

?>