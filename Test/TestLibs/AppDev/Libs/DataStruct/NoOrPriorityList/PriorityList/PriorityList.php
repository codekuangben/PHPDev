<?php

namespace SDK\Lib;

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
		$this->mPrioritySort = PrioritySort.ePS_Great;
		$this->mIsSpeedUpFind = false;
		$this->mIsOpKeepSort = false;
	}

	public function setIsSpeedUpFind($value)
	{
		$this->mIsSpeedUpFind = value;

		if ($this->mIsSpeedUpFind)
		{
			$this->mDic = new MDictionary();
		}
	}

	public function setIsOpKeepSort($value)
	{
		$this->mIsOpKeepSort = value;
	}

	public function Clear()
	{
		$this->mPriorityProcessObjectList.Clear();

		if($this->mIsSpeedUpFind)
		{
			$this->mDic.Clear();
		}
	}

	public function Count()
	{
		return $this->mPriorityProcessObjectList.Count();
	}

	public function get($index)
	{
		$ret = null;

		if(index < $this->Count())
		{
			$ret = $this->mPriorityProcessObjectList.get(index).mPriorityObject;
		}

		return $ret;
	}

	public function getPriority($index)
	{
		$ret = 0;

		if ($index < $this->Count())
		{
			$ret = $this->mPriorityProcessObjectList.get(index).mPriority;
		}

		return $ret;
	}

	public function Contains($item)
	{
		$ret = false;

		if (null != $item)
		{
			if ($this->mIsSpeedUpFind)
			{
				$ret = $this->mDic.ContainsKey($item);
			}
			else
			{
				$index = 0;
				$listLen = $this->mPriorityProcessObjectList.Count();

				while ($index < $listLen)
				{
					if (item == $this->mPriorityProcessObjectList.get($index).mPriorityObject)
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
			if (MacroDef.ENABLE_LOG)
			{
				Ctx.mInstance.mLogSys.log("PriorityList::Contains, failed", LogTypeId.eLogPriorityListCheck);
			}
		}

		return ret;
	}

	public function RemoveAt($index)
	{
		if ($this->mIsSpeedUpFind)
		{
			$this->effectiveRemove($this->mPriorityProcessObjectList[$index].mPriorityObject);
		}
		else
		{
			$this->mPriorityProcessObjectList.RemoveAt($index);
		}
	}

	public function getIndexByPriority($priority)
	{
		$retIndex = -1;

		$index = 0;
		$listLen = $this->mPriorityProcessObjectList.Count();

		while ($index < $listLen)
		{
			if (PrioritySort.ePS_Less == $this->mPrioritySort)
			{
				if ($this->mPriorityProcessObjectList.get($index).mPriority >= $priority)
				{
					$retIndex = $index;
					break;
				}
			}
			else if (PrioritySort.ePS_Great == $this->mPrioritySort)
			{
				if ($this->mPriorityProcessObjectList.get(index).mPriority <= $priority)
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
		$listLen = $this->mPriorityProcessObjectList.Count();

		while ($index < $listLen)
		{
			if ($this->mPriorityProcessObjectList.get($index).mPriorityObject == $priorityObject)
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
			if (!$this->Contains($priorityObject))
			{
				$priorityProcessObject = null;
				$priorityProcessObject = new PriorityProcessObject();

				$priorityProcessObject->mPriorityObject = $priorityObject;
				$priorityProcessObject->mPriority = $priority;

				if (!$this->mIsOpKeepSort)
				{
				    $this->mPriorityProcessObjectList.Add(PriorityProcessObjectpriorityProcessObject);

					if ($this->mIsSpeedUpFind)
					{
						$this->mDic.Add(priorityObject, $this->mPriorityProcessObjectList.Count() - 1);
					}
				}
				else
				{
					$index = $this->getIndexByPriority(priority);

					if (-1 == $index)
					{
						$this->mPriorityProcessObjectList.Add($priorityProcessObject);

						if ($this->mIsSpeedUpFind)
						{
							$this->mDic.Add($priorityObject, $this->mPriorityProcessObjectList.Count() - 1);
						}
					}
					else
					{
						$this->mPriorityProcessObjectList.Insert($index, $priorityProcessObject);

						if ($this->mIsSpeedUpFind)
						{
							$this->mDic.Add($priorityObject, $index);
							$this->updateIndex($index + 1);
						}
					}
				}
			}
		}
		else
		{
			if (MacroDef.ENABLE_LOG)
			{
				Ctx.mInstance.mLogSys.log("PriorityList::addPriorityObject, failed", LogTypeId.eLogPriorityListCheck);
			}
		}
	}

	public function removePriorityObject($priorityObject)
	{
		if ($this->Contains($priorityObject))
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
					$this->mPriorityProcessObjectList.RemoveAt($index);
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

		if ($this->mDic.ContainsKey($item))
		{
			$ret = true;

			$index = $this->mDic[$item];
			$this->mDic.Remove($item);

			if ($index == $this->mPriorityProcessObjectList.Count() - 1)    // 如果是最后一个元素，直接移除
			{
				$this->mPriorityProcessObjectList.RemoveAt($index);
			}
			else
			{
				// 这样移除会使优先级顺序改变
				if (!$this->mIsOpKeepSort)
				{
					$this->mPriorityProcessObjectList.set($index, $this->mPriorityProcessObjectList.get($this->mPriorityProcessObjectList.Count() - 1));
					$this->mPriorityProcessObjectList.RemoveAt($this->mPriorityProcessObjectList.Count() - 1);
					$this->mDic.Add($this->mPriorityProcessObjectList.get(index).mPriorityObject, $index);
				}
				else
				{
					$this->mPriorityProcessObjectList.RemoveAt($index);
					$this->updateIndex($index);
				}
			}
		}

		return ret;
	}

	protected function updateIndex($index)
	{
		$listLen = $this->mPriorityProcessObjectList.Count();

		while ($index < $listLen)
		{
			$this->mDic.Add($this->mPriorityProcessObjectList.get($index).mPriorityObject, $index);

			++$index;
		}
	}
}

?>