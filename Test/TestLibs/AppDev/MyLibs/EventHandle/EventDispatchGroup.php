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
	public function addEventDispatch($groupId, $eventDispatch)
	{
		if (!$this->mGroupID2DispatchDic->containsKey($groupId))
		{
		    $this->mGroupID2DispatchDic->add($groupId, $eventDispatch);
		}
	}

	public function addEventHandle($groupId, $eventListener, $eventHandle)
	{
		// 如果没有就创建一个
		if (!$this->mGroupID2DispatchDic->containsKey($groupId))
		{
			$this->addEventDispatch($groupId, new EventDispatch());
		}

		$this->mGroupID2DispatchDic->value($groupId)->addEventHandle($eventListener, $eventHandle);
	}

	public function removeEventHandle($groupId, $eventListener, $eventHandle)
	{
		if ($this->mGroupID2DispatchDic->containsKey($groupId))
		{
		    $this->mGroupID2DispatchDic->value($groupId)->removeEventHandle($eventListener, $eventHandle);

			// 如果已经没有了
		    if (!$this->mGroupID2DispatchDic.value($groupId)->hasEventHandle())
			{
				$this->mGroupID2DispatchDic->remove($groupId);
			}
		}
		else
		{
		 
		}
	}

	public function dispatchEvent($groupId, $dispatchObject)
	{
		$this->mLoopDepth->incDepth();

		if ($this->mGroupID2DispatchDic->containsKey($groupId))
		{
		    $this->mGroupID2DispatchDic->value($groupId)->dispatchEvent($dispatchObject);
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

	public function clearGroupEventHandle($groupId)
	{
		if (!$this->mLoopDepth->isInDepth())
		{
			if ($this->mGroupID2DispatchDic->containsKey($groupId))
			{
			    $this->mGroupID2DispatchDic->value($groupId)->clearEventHandle();
				$this->mGroupID2DispatchDic->remove($groupId);
			}
			else
			{
			 
			}
		}
		else
		{
		 
		}
	}

	public function hasEventHandle($groupId)
	{
		if($this->mGroupID2DispatchDic->containsKey($groupId))
		{
			return $this->mGroupID2DispatchDic->value($groupId)->hasEventHandle();
		}

		return false;
	}
}

?>