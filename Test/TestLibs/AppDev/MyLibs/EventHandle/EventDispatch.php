<?php

namespace MyLibs;

/**
 * @brief 事件分发，之分发一类事件，不同类型的事件使用不同的事件分发
 * @brief 注意，事件分发缺点就是，可能被调用的对象已经释放，但是没有清掉事件处理器，结果造成空指针
 * @brief 事件添加一个事件 Id ，这个主要是事件分发使用，资源加载那边基本都没有使用这个事件 Id，因为 java7 不支持函数指针，只能传递 java 对象
 */
class EventDispatch extends DelayPriorityHandleMgrBase
{
	protected $mEventId;
	protected $mHandleList;
	protected $mUniqueId;       // 唯一 Id ，调试使用

	public function __construct($eventId_ = 0)
	{
	    Parent::__construct();
	    
		$this->mEventId = $eventId_;
		$this->mHandleList = new MList();
	}

	protected function getHandleList()
	{
		return $this->mHandleList;
	}

	public function getUniqueId()
	{
		return $this->mUniqueId;
	}
	
	public function setUniqueId($value)
	{
		$this->mUniqueId = $value;
		$this->mHandleList->$uniqueId = $this->mUniqueId;
	}

	public function init()
	{

	}

	public function dispose()
	{

	}

	public function addDispatch($dispatch)
	{
	    $this->addObject($dispatch);
	}

	public function removeDispatch($dispatch)
	{
	    $this->removeObject($dispatch);
	}

	// 相同的函数只能增加一次，Lua ，Python 这些语言不支持同时存在几个相同名字的函数，只支持参数可以赋值，因此不单独提供同一个名字不同参数的接口了，但是 java 不支持参数默认值，只能通过重载实现参数默认值，真是悲剧中的悲剧， eventId: 分发事件上层唯一 Id，这样一个事件处理函数可以根据 EventId 处理不同的事件
	public function addEventHandle($pThis, $handle, $eventId = 0)
	{
		if (null != $pThis || null != $handle)
		{
			$funcObject = new EventDispatchFunctionObject();

			if (null != $handle)
			{
				$funcObject->setFuncObject($pThis, $handle, $eventId);
			}

			$this->addDispatch($funcObject);
		}
		else
		{
		    Ctx::$msInstance->mLogSys("EventDispatch::addEventHandle error", LogTypeId::eLogEventDispatch);
		}
	}

	public function removeEventHandle($pThis, $handle, $eventId = 0)
	{
		$idx = 0;
		$elemLen = 0;
		$elemLen = $this->mHandleList->count();

		while ($idx < $elemLen)
		{
			if ($this->mHandleList[$idx]->isEqual($pThis, $handle, $eventId))
			{
				break;
			}

			$idx += 1;
		}

		if ($idx < $this->mHandleList->count())
		{
			$this->removeDispatch($this->mHandleList[$idx]);
		}
		else
		{
		
		}
	}

	protected function addObject($delayObject, $priority = 0.0)
	{
		if ($this->isInDepth())
		{
			parent::addObject($delayObject, $priority);
		}
		else
		{
			// 这个判断说明相同的函数只能加一次，但是如果不同资源使用相同的回调函数就会有问题，但是这个判断可以保证只添加一次函数，值得，因此不同资源需要不同回调函数
			$this->mHandleList->add($delayObject);
		}
	}

	protected function removeObject($delayObject)
	{
		if ($this->isInDepth())
		{
			parent::removeObject($delayObject);
		}
		else
		{
			if (!$this->mHandleList->remove($delayObject))
			{
			
			}
		}
	}

	public function dispatchEvent($dispatchObject)
	{
		//try
		//{
		$this->incDepth();

		$index = 0;
		$listLen = $this->mHandleList->count();
		$handle = null;

		while ($index < $listLen)
		{
		    $handle = $this->mHandleList->get($index);

			if (!$handle->mIsClientDispose)
			{
				$handle->call($dispatchObject);
			}

			$index += 1;
		}

		$this->decDepth();
		//}
		//catch (Exception ex)
		//{
		//    Ctx::$msInstance->mLogSys->catchLog(ex->ToString());
		//}
	}

	public function clearEventHandle()
	{
		if ($this->isInDepth())
		{
			$idx = 0;
			$len = $this->mHandleList->count();
			$item = null;

			while($idx < $len)
			{
				$item = $this->mHandleList[$idx];

				$this->removeDispatch($item);

				++$idx;
			}
		}
		else
		{
			$this->mHandleList->clear();
		}
	}

	// 这个判断说明相同的函数只能加一次，但是如果不同资源使用相同的回调函数就会有问题，但是这个判断可以保证只添加一次函数，值得，因此不同资源需要不同回调函数
	public function isExistEventHandle($pThis, $handle, $eventId)
	{
		$bFinded = false;
		$idx = 0;
		$len = $this->mHandleList->count();
		$item = null;

		while ($idx < $len)
		{
			$item = $this->mHandleList[$idx];

			if ($item->isEqual($pThis, $handle, $eventId))
			{
				$bFinded = true;
				break;
			}

			++$idx;
		}

		return $bFinded;
	}

	public function copyFrom($rhv)
	{
		$idx = 0;
		$len = $this->mHandleList->count();
		$handle = null;

		while ($idx < $len)
		{
			$handle = $this->mHandleList[idx];

			$this->mHandleList->add($handle);

			++$idx;
		}
	}

	public function hasEventHandle()
	{
		return $this->mHandleList->count() > 0;
	}

	public function getEventHandleCount()
	{
		return $this->mHandleList->count();
	}
}

?>