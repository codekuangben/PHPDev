<?php

namespace MyLibs;

/**
 * @brief MIndexList ，元素是保存一个在列表中的索引的，减少 Key 比较，加快查找
 */
class MIndexList
{
    // IndexItemBase
	protected $mList;
	protected $mUniqueId;       // 唯一 Id ，调试使用
	protected $mEleTotal;       // 元素总数
	protected $mIsOpKeepSort;           // 操作的时候是否保持排序

	public function __construct($capacity = 0)
	{
	    $this->mEleTotal = $capacity;
	    $this->mList = new MList($capacity);
		$this->mIsOpKeepSort = false;
	}

	public function setIsOpKeepSort($value)
	{
		$this->mIsOpKeepSort = $value;
	}

	public function ToArray()
	{
	    return $this->mList->ToArray();
	}

	public function list()
	{
		return $this->mList;
	}

	public function getUniqueId()
	{
		return $this->mUniqueId;
	}
	
	public function setUniqueId($value)
	{
	    $this->mUniqueId = $value;
	}

	public function getBuffer()
	{
		return $this->mList;
	}

	public function getSize()
    {
		// 频繁获取这个字段比较耗时
        //return $this->mList->Count;
		return $this->mEleTotal;
	}

	public function Add($item)
	{
	    $this->mList->Add($item);
		$this->mEleTotal += 1;
	}

	// 主要是 Add 一个 float 类型的 Vector3
	public function AddA($item_1, $item_2, $item_3)
	{
		$this->Add($item_1);
		$this->Add($item_2);
		$this->Add($item_3);
	}

	// 主要是 Add 一个 float 类型的 UV
	public function AddB($item_1, $item_2)
	{
		$this->Add($item_1);
		$this->Add($item_2);
	}

	// 主要是 Add 一个 byte 类型的 Color32
	public function AddC($item_1, $item_2, $item_3, $item_4)
	{
		$this->Add($item_1);
		$this->Add($item_2);
		$this->Add($item_3);
		$this->Add($item_4);
	}

	public function push($item)
	{
		$this->Add($item);
	}

	public function Remove($item)
	{
		return $this->effectiveRemove($item);
	}

	public function get($index)
	{
		return $this->mList[$index];
	}

	public function set($index, $value)
	{
		if(null != $this->mList[$index])
		{
			$this->mList[$index]->resetIndex();
		}

		$this->mList[$index] = $value;
		$this->mList[$index]->setIndex($index);
	}

	public function Clear()
	{
		$index = 0;
		$listLen = $this->mEleTotal;

		while($index < $listLen)
		{
			$this->mList[$index]->resetIndex();

			$index += 1;
		}

		$this->mList->Clear();
		$this->mEleTotal = 0;
	}

	public function Count()
	{
		//return $this->mList->Count;
		return $this->mEleTotal;
	}

	public function length()
	{
		//return $this->mList->Count;
		return $this->mEleTotal;
	}

	public function setLength($value)
	{
		$this->mList->Capacity = $value;
	}

	public function RemoveAt($index)
	{
		if ($index < $this->Count())
		{
			$this->mList[$index]->resetIndex();
			$this->mList->RemoveAt($index);
			$this->mEleTotal -= 1;
		}
	}

	public function IndexOf($item)
	{
		if($item->getIndex() < $this->Count())
		{
			return $item->getIndex();
		}

		return -1;
	}

	public function Insert($index, $item)
	{
		if ($index <= $this->Count())
		{
			$this->mList->Insert($index, $item);
			$item->setIndex($index);
			$this->mEleTotal += 1;
			$this->updateIndex($index + 1);
		}
	}

	public function Contains($item)
	{
		return $item->getIndex() != -1;
	}

	public function Sort($comparer)
	{
		$this->mList->Sort($comparer);
	}

	public function merge($appendList)
	{
		if($appendList != null)
		{
		    foreach($appendList->list() as $item)
			{
				$this->mList->Add($item);
				$item->setIndex($this->mEleTotal);
				$this->mEleTotal += 1;
			}
		}
	}

	// 快速移除元素
	protected function effectiveRemove($item)
	{
		$ret = false;

		$idx = $item->getIndex();

		if (-1 != $idx)
		{
			$ret = true;

			if ($idx == $this->Count() - 1)    // 如果是最后一个元素，直接移除
			{
				$this->RemoveAt($idx);
			}
			else
			{
				if (!$this->mIsOpKeepSort)
				{
					$this->mList[$idx] = $this->mList[$this->Count() - 1];
					$this->RemoveAt($this->Count() - 1);
				}
				else
				{
					$this->RemoveAt($idx);
					$this->updateIndex($idx);
				}
			}
		}

		return $ret;
	}

	protected function updateIndex($idx)
	{
		$len = $this->Count();

		while($idx < $len)
		{
			$this->mList[$idx]->setIndex($idx);

			++$idx;
		}
	}
}

?>