<?php

namespace MyLibs;

/**
 * @brief 当需要管理的对象可能在遍历中间添加的时候，需要这个管理器
 */
class DelayNoPriorityHandleMgrBase extends DelayNoOrPriorityHandleMgrBase
{
	public function __construct()
	{
		$this->mDeferredAddQueue = new NoPriorityList();
		$this->mDeferredAddQueue->setIsSpeedUpFind(true);
		$this->mDeferredDelQueue = new NoPriorityList();
		$this->mDeferredDelQueue->setIsSpeedUpFind(true);
	}

	public function init()
	{
		parent::init();
	}

	public function dispose()
	{
		parent::dispose();
	}
}

?>