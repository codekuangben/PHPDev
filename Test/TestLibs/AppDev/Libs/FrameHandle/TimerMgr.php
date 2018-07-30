<?php

/**
* @brief 定时器管理器
*/
namespace SDK\Lib;

class TimerMgr extends DelayPriorityHandleMgrBase
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
				base.addObject($delayObject, $priority);
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
				parent::removeObject($delayObject);
			}
			else
			{
				$index = 0;
				$listLen = $this->mTimerList->Count();
				$item = null;
				
				while($index < $listLen)
				{
					$item = $this->mTimerList->get($index);
					
					if (UtilSysLibWrap::isAddressEqual($item, $delayObject))
					{
						$this->mTimerList->Remove($item);
						break;
					}
					
					$index += 1;
				}
			}
		}
	}

	// 从 Lua 中添加定时器，这种定时器尽量整个定时器周期只与 Lua 通信一次
	public function addTimer($delayObject, $priority = 0.0)
	{
		$this->addObject($delayObject, $priority);
	}

	public function removeTimer($timer)
	{
		$this->removeObject($timer);
	}

	public function Advance($delta)
	{
		$this->incDepth();

		$index = 0;
		$listLen = $this->mTimerList->Count();
		$item = null;
		
		while($index < $listLen)
		{
			$item = $this->mTimerList->get($index);
			
			if (!timerItem.isClientDispose())
			{
				timerItem.OnTimer(delta);
			}

			if (timerItem.mDisposed)        // 如果已经结束
			{
				$this->removeObject(timerItem);
			}
			
			$index += 1;
		}

		$this->decDepth();
	}
}

?>