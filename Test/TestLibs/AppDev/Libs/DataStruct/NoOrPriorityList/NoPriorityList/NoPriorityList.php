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
	    $this->mIsSpeedUpFind = $value;

		if ($this->mIsSpeedUpFind)
		{
			$this->mDic = new MDictionary();
		}
	}

	public function setIsOpKeepSort($value)
	{

	}

	public function clear()
	{
		$this->mNoPriorityProcessObjectList->clear();

		if($this->mIsSpeedUpFind)
		{
			$this->mDic->clear();
		}
	}

	public function count()
	{
		return $this->mNoPriorityProcessObjectList->count();
	}

	public function get($index)
	{
		$ret = null;

		if($index < $this->count())
		{
		    $ret = $this->mNoPriorityProcessObjectList->get($index);
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
				$listLen = $this->mNoPriorityProcessObjectList->count();

				while ($index < $listLen)
				{
					if ($item == $this->mNoPriorityProcessObjectList->get($index))
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
				Ctx::$msInstance->mLogSys->log("NoPriorityList::contains, failed", LogTypeId::eLogNoPriorityListCheck);
			}
		}

		return $ret;
	}

	public function removeAt($index)
	{
		if ($this->mIsSpeedUpFind)
		{
			$this->effectiveRemove($this->mNoPriorityProcessObjectList[$index]);
		}
		else
		{
			$this->mNoPriorityProcessObjectList->removeAt($index);
		}
	}

	public function getIndexByNoPriorityObject($priorityObject)
	{
		$retIndex = -1;

		$index = 0;
		$listLen = $this->mNoPriorityProcessObjectList->count();

		while ($index < $listLen)
		{
			if ($this->mNoPriorityProcessObjectList->get($index) == priorityObject)
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
			if (!$this->contains($noPriorityObject))
			{
				$this->mNoPriorityProcessObjectList->add($noPriorityObject);

				if ($this->mIsSpeedUpFind)
				{
					$this->mDic->add(noPriorityObject, $this->mNoPriorityProcessObjectList->count() - 1);
				}
			}
		}
		else
		{
			if (MacroDef::ENABLE_LOG)
			{
				Ctx::$msInstance->mLogSys->log("NoPriorityList::addNoPriorityObject, failed", LogTypeId::eLogNoPriorityListCheck);
			}
		}
	}

	public function removeNoPriorityObject($noPriorityObject)
	{
		if (null != $noPriorityObject)
		{
			if ($this->contains($noPriorityObject))
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
						$this->mNoPriorityProcessObjectList->removeAt($index);
					}
				}
			}
		}
		else
		{
			if (MacroDef::ENABLE_LOG)
			{
				Ctx::$msInstance->mLogSys->log("NoPriorityList::addNoPriorityObject, failed", LogTypeId::eLogNoPriorityListCheck);
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

		if ($this->mDic->containsKey($item))
		{
			$ret = true;

			$idx = $this->mDic[$item];
			$this->mDic->remove($item);

			if ($idx == $this->mNoPriorityProcessObjectList->count() - 1)    // 如果是最后一个元素，直接移除
			{
				$this->mNoPriorityProcessObjectList->removeAt($idx);
			}
			else
			{
				$this->mNoPriorityProcessObjectList->set($idx, $this->mNoPriorityProcessObjectList->get($this->mNoPriorityProcessObjectList->count() - 1));
				$this->mNoPriorityProcessObjectList->removeAt($this->mNoPriorityProcessObjectList->count() - 1);
				$this->mDic->add($this->mNoPriorityProcessObjectList->get(idx), $idx);
			}
		}

		return ret;
	}

	protected function updateIndex($idx)
	{
		$listLen = $this->mNoPriorityProcessObjectList->count();

		while ($idx < $listLen)
		{
			$this->mDic->add($this->mNoPriorityProcessObjectList->get($idx), $idx);

			++$idx;
		}
	}
}

?>