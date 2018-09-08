<?php

/**
* @brief 定时器管理器
*/
namespace SDK\Lib;

class FrameTimerMgr extends DelayPriorityHandleMgrBase
{
	protected $mTimerList;     // 当前所有的定时器列表

	public function __construct()
	{
		$this->mTimerList = new MList();
	}

	public function init()
	{

	}

	public function dispose()
	{

	}

	protected function addObject($delayObject, $priority = 0.0)
	{
		// 检查当前是否已经在队列中
		if (!$this->mTimerList.Contains($delayObject))
		{
			if ($this->isInDepth())
			{
				parent::addObject(delayObject, priority);
			}
			else
			{
				$this->mTimerList.Add($delayObject);
			}
		}
	}

	protected function removeObject($delayObject)
	{
		// 检查当前是否在队列中
		if ($this->mTimerList.Contains($delayObject))
		{
			$delayObject->mDisposed = true;

			if ($this->isInDepth())
			{
				parent::addObject(delayObject);
			}
			else
			{
				$index = 0;
				$listLen = $this->mTimerList->Count();
				$item = null;
				
				while($index < $listLen)
				{
					$item = $this->mTimerList->get($index);
					
					if(UtilSysLibWrap::isAddressEqual($item, $delayObject))
					{
						$this->mTimerList.Remove($item);
						break;
					}
					
					$index += 1;
				}
			}
		}
	}

	public function addFrameTimer($timer, $priority = 0.0)
	{
		$this->addObject(timer, priority);
	}

	public function removeFrameTimer($timer)
	{
		$this->removeObject(timer);
	}

	public function Advance($delta)
	{
		$this->incDepth();

		$index = 0;
		$listLen = $this->mTimerList->Count();
		$timerItem= null;
		
		while($index < $listLen)
		{
			if (!$timerItem->isClientDispose())
			{
				$timerItem->OnFrameTimer();
			}
			if ($timerItem->mDisposed)
			{
				$this->removeObject(timerItem);
			}
			
			$index += 1;
		}

		$this->decDepth();
	}
}

?>