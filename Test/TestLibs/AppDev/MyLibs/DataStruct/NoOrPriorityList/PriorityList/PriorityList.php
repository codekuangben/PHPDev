<?php

namespace MyLibs;

/**
 * @brief 优先级队列
 */
class PriorityList implements INoOrPriorityList
{
	protected $mPriorityProcessObjectList;  // 优先级对象列表
	protected $mPrioritySort;   // 排序方式

	protected $mDic;   // 查找字典
	protected $mIsSpeedUpFind;          // 是否开启查找
	protected $mIsOpKeepSort;           // 操作的时候是否保持排序

	public function __construct()
	{
		$this->mPriorityProcessObjectList = new MList();
		$this->mPrioritySort = PrioritySort::ePS_Great;
		$this->mIsSpeedUpFind = false;
		$this->mIsOpKeepSort = false;
	}

	public function setIsSpeedUpFind($value)
	{
	    $this->mIsSpeedUpFind = $value;

		if ($this->mIsSpeedUpFind)
		{
			$this->mDic = new MDictionary();
		}
	}

	public function setIsOpKeepSort($value)
	{
	    $this->mIsOpKeepSort = $value;
	}

	public function clear()
	{
		$this->mPriorityProcessObjectList->clear();

		if($this->mIsSpeedUpFind)
		{
			$this->mDic->clear();
		}
	}

	public function count()
	{
		return $this->mPriorityProcessObjectList->count();
	}

	public function get($index)
	{
		$ret = null;

		if(index < $this->count())
		{
			$ret = $this->mPriorityProcessObjectList->get($index)->mPriorityObject;
		}

		return $ret;
	}

	public function getPriority($index)
	{
		$ret = 0;

		if ($index < $this->count())
		{
			$ret = $this->mPriorityProcessObjectList->get($index)->mPriority;
		}

		return $ret;
	}

	public function contains($item)
	{
		$ret = false;

		if (null != $item)
		{
			if ($this->mIsSpeedUpFind)
			{
				$ret = $this->mDic->containsKey($item);
			}
			else
			{
				$index = 0;
				$listLen = $this->mPriorityProcessObjectList->count();

				while ($index < $listLen)
				{
					if (item == $this->mPriorityProcessObjectList->get($index)->mPriorityObject)
					{
						$ret = true;
						break;
					}

					++$index;
				}
			}
		}
		else
		{
			if (MacroDef::ENABLE_LOG)
			{
				Ctx::$msIns->mLogSys->log("PriorityList::contains, failed", LogTypeId::eLogPriorityListCheck);
			}
		}

		return ret;
	}

	public function removeAt($index)
	{
		if ($this->mIsSpeedUpFind)
		{
			$this->effectiveRemove($this->mPriorityProcessObjectList->get($index)->mPriorityObject);
		}
		else
		{
			$this->mPriorityProcessObjectList->removeAt($index);
		}
	}

	public function getIndexByPriority($priority)
	{
		$retIndex = -1;

		$index = 0;
		$listLen = $this->mPriorityProcessObjectList->count();

		while ($index < $listLen)
		{
			if (PrioritySort::ePS_Less == $this->mPrioritySort)
			{
				if ($this->mPriorityProcessObjectList->get($index)->mPriority >= $priority)
				{
					$retIndex = $index;
					break;
				}
			}
			else if (PrioritySort::ePS_Great == $this->mPrioritySort)
			{
			    if ($this->mPriorityProcessObjectList->get($index)->mPriority <= $priority)
				{
					$retIndex = $index;
					break;
				}
			}

			++$index;
		}

		return $retIndex;
	}

	public function getIndexByPriorityObject($priorityObject)
	{
		$retIndex = -1;

		$index = 0;
		$listLen = $this->mPriorityProcessObjectList->count();

		while ($index < $listLen)
		{
			if ($this->mPriorityProcessObjectList->get($index)->mPriorityObject == $priorityObject)
			{
				$retIndex = $index;
				break;
			}

			++$index;
		}

		return $retIndex;
	}

	public function getIndexByNoOrPriorityObject($priorityObject)
	{
		return $this->getIndexByPriorityObject($priorityObject);
	}

	public function addPriorityObject($priorityObject, $priority = 0.0)
	{
		if (null != $priorityObject)
		{
			if (!$this->contains($priorityObject))
			{
				$priorityProcessObject = null;
				$priorityProcessObject = new PriorityProcessObject();

				$priorityProcessObject->mPriorityObject = $priorityObject;
				$priorityProcessObject->mPriority = $priority;

				if (!$this->mIsOpKeepSort)
				{
				    $this->mPriorityProcessObjectList->add($priorityProcessObject);

					if ($this->mIsSpeedUpFind)
					{
						$this->mDic->add(priorityObject, $this->mPriorityProcessObjectList->count() - 1);
					}
				}
				else
				{
					$index = $this->getIndexByPriority(priority);

					if (-1 == $index)
					{
						$this->mPriorityProcessObjectList->add($priorityProcessObject);

						if ($this->mIsSpeedUpFind)
						{
							$this->mDic->add($priorityObject, $this->mPriorityProcessObjectList->count() - 1);
						}
					}
					else
					{
						$this->mPriorityProcessObjectList->insert($index, $priorityProcessObject);

						if ($this->mIsSpeedUpFind)
						{
							$this->mDic->add($priorityObject, $index);
							$this->updateIndex($index + 1);
						}
					}
				}
			}
		}
		else
		{
			if (MacroDef::ENABLE_LOG)
			{
				Ctx::$msIns->mLogSys->log("PriorityList::addPriorityObject, failed", LogTypeId::eLogPriorityListCheck);
			}
		}
	}

	public function removePriorityObject($priorityObject)
	{
		if ($this->contains($priorityObject))
		{
			if ($this->mIsSpeedUpFind)
			{
				$this->effectiveRemove($priorityObject);
			}
			else
			{
				$index = $this->getIndexByPriorityObject($priorityObject);

				if(-1 != index)
				{
					$this->mPriorityProcessObjectList->removeAt($index);
				}
			}
		}
	}

	public function addNoOrPriorityObject($noPriorityObject, $priority = 0.0)
	{
		$this->addPriorityObject($noPriorityObject);
	}

	public function removeNoOrPriorityObject($noPriorityObject)
	{
		$this->removePriorityObject($noPriorityObject);
	}

	// 快速移除元素
	protected function effectiveRemove($item)
	{
		$ret = false;

		if ($this->mDic->containsKey($item))
		{
			$ret = true;

			$index = $this->mDic[$item];
			$this->mDic->remove($item);

			if ($index == $this->mPriorityProcessObjectList->count() - 1)    // 如果是最后一个元素，直接移除
			{
				$this->mPriorityProcessObjectList->removeAt($index);
			}
			else
			{
				// 这样移除会使优先级顺序改变
				if (!$this->mIsOpKeepSort)
				{
					$this->mPriorityProcessObjectList->set($index, $this->mPriorityProcessObjectList->get($this->mPriorityProcessObjectList->count() - 1));
					$this->mPriorityProcessObjectList->removeAt($this->mPriorityProcessObjectList->count() - 1);
					$this->mDic->add($this->mPriorityProcessObjectList->get(index)->mPriorityObject, $index);
				}
				else
				{
					$this->mPriorityProcessObjectList->removeAt($index);
					$this->updateIndex($index);
				}
			}
		}

		return ret;
	}

	protected function updateIndex($index)
	{
		$listLen = $this->mPriorityProcessObjectList->count();

		while ($index < $listLen)
		{
			$this->mDic->add($this->mPriorityProcessObjectList->get($index)->mPriorityObject, $index);

			++$index;
		}
	}
}

?>