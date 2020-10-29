<?php

namespace MyLibs;

class EventDispatchGroup extends GObject
{
	protected $mGroupID2DispatchDic;
	protected $mLoopDepth;       // 是否是在循环遍历中

	public function __construct()
	{
	    Parent::__construct();
	    
		$this->mGroupID2DispatchDic = new MDictionary();
		$this->mLoopDepth = new LoopDepth();
	}

	// 添加分发器
	public function addEventDispatch($groupID, $disp)
	{
		if (!$this->mGroupID2DispatchDic->containsKey(groupID))
		{
			$this->mGroupID2DispatchDic[groupID] = disp;
		}
	}

	public function addEventHandle($groupID, $eventListener, $eventHandle)
	{
		// 如果没有就创建一个
		if (!$this->mGroupID2DispatchDic->containsKey(groupID))
		{
			$this->addEventDispatch(groupID, new EventDispatch());
		}

		$this->mGroupID2DispatchDic[groupID]->addEventHandle($eventListener, $eventHandle);
	}

	public function removeEventHandle($groupID, $eventListener, $eventHandle)
	{
		if ($this->mGroupID2DispatchDic->containsKey($groupID))
		{
			$this->mGroupID2DispatchDic[$groupID]->removeEventHandle($eventListener, $eventHandle);

			// 如果已经没有了
			if (!$this->mGroupID2DispatchDic[$groupID]->hasEventHandle())
			{
				$this->mGroupID2DispatchDic->remove($groupID);
			}
		}
		else
		{
		 
		}
	}

	public function dispatchEvent($groupID, $dispatchObject)
	{
		$this->mLoopDepth->incDepth();

		if ($this->mGroupID2DispatchDic->containsKey($groupID))
		{
			$this->mGroupID2DispatchDic[$groupID]->dispatchEvent($dispatchObject);
		}
		else
		{
		 
		}

		$this->mLoopDepth->decDepth();
	}

	public function clearAllEventHandle()
	{
		if (!$this->mLoopDepth->isInDepth())
		{
			while(list($key, $val)= each($this->mGroupID2DispatchDic))
			{
				$dispatch = $val;
				$dispatch->clearEventHandle();
			}

			$this->mGroupID2DispatchDic->clear();
		}
		else
		{
		
		}
	}

	public function clearGroupEventHandle($groupID)
	{
		if (!$this->mLoopDepth->isInDepth())
		{
			if ($this->mGroupID2DispatchDic->containsKey($groupID))
			{
				$this->mGroupID2DispatchDic[$groupID]->clearEventHandle();
				$this->mGroupID2DispatchDic->remove($groupID);
			}
			else
			{
			 
			}
		}
		else
		{
		 
		}
	}

	public function hasEventHandle($groupID)
	{
		if($this->mGroupID2DispatchDic->containsKey($groupID))
		{
			return $this->mGroupID2DispatchDic[$groupID]->hasEventHandle();
		}

		return false;
	}
}

?>