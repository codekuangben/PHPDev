<?php

namespace SDK\Lib;

/**
 * @brief 优先级队列
 */
class NoPriorityList implements INoOrPriorityList
{
	protected $mNoPriorityProcessObjectList;  // 优先级对象列表

	protected $mDic;       // 查找字典
	protected $mIsSpeedUpFind;      // 是否开启查找

	public function __construct()
	{
		$this->mNoPriorityProcessObjectList = new MList();
		$this->mIsSpeedUpFind = false;
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

	}

	public function Clear()
	{
		$this->mNoPriorityProcessObjectList.Clear();

		if($this->mIsSpeedUpFind)
		{
			$this->mDic.Clear();
		}
	}

	public function Count()
	{
		return $this->mNoPriorityProcessObjectList.Count();
	}

	public function get($index)
	{
		$ret = null;

		if(index < $this->Count())
		{
			$ret = $this->mNoPriorityProcessObjectList.get(index);
		}

		return ret;
	}

	public function Contains($item)
	{
		$ret = false;

		if (null != $item)
		{
			if ($this->mIsSpeedUpFind)
			{
				$ret = $this->mDic.ContainsKey(item);
			}
			else
			{
				$index = 0;
				$listLen = $this->mNoPriorityProcessObjectList.Count();

				while (index < listLen)
				{
					if (item == $this->mNoPriorityProcessObjectList.get(index))
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
				Ctx.mInstance.mLogSys.log("NoPriorityList::Contains, failed", LogTypeId.eLogNoPriorityListCheck);
			}
		}

		return ret;
	}

	public function RemoveAt($index)
	{
		if ($this->mIsSpeedUpFind)
		{
			$this->effectiveRemove($this->mNoPriorityProcessObjectList[$index]);
		}
		else
		{
			$this->mNoPriorityProcessObjectList.RemoveAt($index);
		}
	}

	public function getIndexByNoPriorityObject($priorityObject)
	{
		$retIndex = -1;

		$index = 0;
		$listLen = $this->mNoPriorityProcessObjectList.Count();

		while ($index < $listLen)
		{
			if ($this->mNoPriorityProcessObjectList.get($index) == priorityObject)
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
		return $this->getIndexByNoPriorityObject($priorityObject);
	}

	public function addNoPriorityObject($noPriorityObject)
	{
		if (null != $noPriorityObject)
		{
			if (!$this->Contains($noPriorityObject))
			{
				$this->mNoPriorityProcessObjectList.Add($noPriorityObject);

				if ($this->mIsSpeedUpFind)
				{
					$this->mDic.Add(noPriorityObject, $this->mNoPriorityProcessObjectList.Count() - 1);
				}
			}
		}
		else
		{
			if (MacroDef.ENABLE_LOG)
			{
				Ctx.mInstance.mLogSys.log("NoPriorityList::addNoPriorityObject, failed", LogTypeId.eLogNoPriorityListCheck);
			}
		}
	}

	public function removeNoPriorityObject($noPriorityObject)
	{
		if (null != $noPriorityObject)
		{
			if ($this->Contains($noPriorityObject))
			{
				if ($this->mIsSpeedUpFind)
				{
					$this->effectiveRemove($noPriorityObject);
				}
				else
				{
					$index = $this->getIndexByNoPriorityObject($noPriorityObject);

					if (-1 != $index)
					{
						$this->mNoPriorityProcessObjectList.RemoveAt($index);
					}
				}
			}
		}
		else
		{
			if (MacroDef.ENABLE_LOG)
			{
				Ctx.mInstance.mLogSys.log("NoPriorityList::addNoPriorityObject, failed", LogTypeId.eLogNoPriorityListCheck);
			}
		}
	}

	public function addNoOrPriorityObject($noPriorityObject, $priority = 0.0)
	{
		$this->addNoPriorityObject($noPriorityObject);
	}

	public function removeNoOrPriorityObject($noPriorityObject)
	{
		$this->removeNoPriorityObject($noPriorityObject);
	}

	// 快速移除元素
	protected function effectiveRemove($item)
	{
		$ret = false;

		if ($this->mDic.ContainsKey($item))
		{
			$ret = true;

			$idx = $this->mDic[$item];
			$this->mDic.Remove($item);

			if ($idx == $this->mNoPriorityProcessObjectList.Count() - 1)    // 如果是最后一个元素，直接移除
			{
				$this->mNoPriorityProcessObjectList.RemoveAt($idx);
			}
			else
			{
				$this->mNoPriorityProcessObjectList.set($idx, $this->mNoPriorityProcessObjectList.get($this->mNoPriorityProcessObjectList.Count() - 1));
				$this->mNoPriorityProcessObjectList.RemoveAt($this->mNoPriorityProcessObjectList.Count() - 1);
				$this->mDic.Add($this->mNoPriorityProcessObjectList.get(idx), $idx);
			}
		}

		return ret;
	}

	protected function updateIndex($idx)
	{
		$listLen = $this->mNoPriorityProcessObjectList.Count();

		while ($idx < $listLen)
		{
			$this->mDic.Add($this->mNoPriorityProcessObjectList.get($idx), $idx);

			++$idx;
		}
	}
}

?>