<?php

namespace SDK\Lib;

/**
 * @brief 对系统 List 的封装
 */
class MList
{
	protected $mList;
	protected $mUniqueId;       // 唯一 Id ，调试使用
	protected $mEleTotal;       // 元素总数

	protected $mDic;    // 为了加快查找速度，当前 Element 到索引映射
	protected $mIsSpeedUpFind;  // 是否加快查询，这个只适用于元素在列表中是唯一的，例如引用之类的，如果有相同的，就会有问题，注意了
	protected $mIsOpKeepSort;           // 操作的时候是否保持排序

	public function __construct()
	{
		$this->mEleTotal = 0;
		$this->mList = array();
		$this->mIsSpeedUpFind = false;
		$this->mIsOpKeepSort = false;
	}

	public function __construct($capacity)
	{
		$this->mList = array();
		$this->mEleTotal = capacity;
	}

	public function setIsSpeedUpFind($value)
	{
		$this->mIsSpeedUpFind = value;

		if($this->mIsSpeedUpFind)
		{
			$this->mDic = new MDictionary();
		}
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

	public function getSize()
	{
		// 频繁获取这个字段比较耗时
		//return $this->mList.Count;
		return $this->mEleTotal;
	}

	public function Add($item)
	{
		$this->mList.Add($item);
		$this->mEleTotal += 1;

		if ($this->mIsSpeedUpFind)
		{
			$this->mDic[$item] = $this->Count() - 1;
		}
	}

	// 主要是 Add 一个 float 类型的 Vector3
	public function Add($item_1, $item_2, $item_3)
	{
		$this->mList.Add($item_1);
		$this->mEleTotal += 1;
		$this->mList.Add($item_2);
		$this->mEleTotal += 1;
		$this->mList.Add($item_3);
		$this->mEleTotal += 1;

		if ($this->mIsSpeedUpFind)
		{
			$this->mDic[$item_1] = $this->Count() - 3;
			$this->mDic[$item_2] = $this->Count() - 2;
			$this->mDic[$item_3] = $this->Count() - 1;
		}
	}

	// 主要是 Add 一个 float 类型的 UV
	public function Add($item_1, $item_2)
	{
		$this->mList.Add($item_1);
		$this->mEleTotal += 1;
		$this->mList.Add($item_2);
		$this->mEleTotal += 1;

		if ($this->mIsSpeedUpFind)
		{
			$this->mDic[item_1] = $this->Count() - 2;
			$this->mDic[item_2] = $this->Count() - 1;
		}
	}

	// 主要是 Add 一个 byte 类型的 Color32
	public function Add($item_1, $item_2, $item_3, $item_4)
	{
		$this->mList.Add($item_1);
		$this->mEleTotal += 1;
		$this->mList.Add($item_2);
		$this->mEleTotal += 1;
		$this->mList.Add($item_3);
		$this->mEleTotal += 1;
		$this->mList.Add($item_4);
		$this->mEleTotal += 1;

		if ($this->mIsSpeedUpFind)
		{
			$this->mDic[$item_1] = $this->Count() - 4;
			$this->mDic[$item_2] = $this->Count() - 3;
			$this->mDic[$item_3] = $this->Count() - 2;
			$this->mDic[$item_4] = $this->Count() - 1;
		}
	}

	public function push($item)
	{
		$this->mList.Add($item);
		$this->mEleTotal += 1;

		if ($this->mIsSpeedUpFind)
		{
			$this->mDic[item] = $this->Count() - 1;
		}
	}

	public function Remove($item)
	{
		if ($this->mIsSpeedUpFind)
		{
			return $this->effectiveRemove($item);
		}
		else
		{
			$ret = $this->mList.Remove($item);

			if($ret)
			{
				$this->mEleTotal -= 1;
			}
			return $ret;
		}
	}

	public function get($index)
	{
		return $this->mList[index];
	}

	public function set($index, $value)
	{
		$this->mList[$index] = value;
	}

	public function Clear()
	{
		$this->mList->Clear();
		$this->mEleTotal = 0;

		if ($this->mIsSpeedUpFind)
		{
			$this->mDic->Clear();
		}
	}

	public function Count()
	{
		//return $this->mList.Count;
		return $this->mEleTotal;
	}

	public function length()
	{
		//return $this->mList.Count;
		return $this->mEleTotal;
	}

	public function setLength($value)
	{
		$this->mList->Capacity = $value;
	}

	public function RemoveAt($index)
	{
		if ($this->mIsSpeedUpFind)
		{
			$this->effectiveRemove($this->mList[$index]);
		}
		else
		{
			if ($index < $this->Count())
			{
				$this->mList->RemoveAt($index);
				$this->mEleTotal -= 1;
			}
		}
	}

	public function IndexOf($item)
	{
		if ($this->mIsSpeedUpFind)
		{
			if ($this->mDic.ContainsKey($item))
			{
				return $this->mDic[$item];
			}
			else
			{
				return -1;
			}
		}
		else
		{
			return $this->mList->IndexOf($item);
		}
	}

	public function Insert($index, $item)
	{
		if (index <= $this->Count())
		{
			$this->mList.Insert($index, $item);
			$this->mEleTotal += 1;

			if ($this->mIsSpeedUpFind)
			{
				$this->mDic[$item] = $index;

				$this->updateIndex($index + 1);
			}
		}
		else
		{

		}
	}

	public function Contains($item)
	{
		if ($this->mIsSpeedUpFind)
		{
			return $this->mDic.ContainsKey($item);
		}
		else
		{
			return $this->mList.Contains($item);
		}
	}

	public function Sort($comparer)
	{
		$this->mList->Sort(comparer);
	}

	public function merge($appendList)
	{
		if($appendList != null)
		{
			foreach ($item as $appendList->list())
			{
				$this->mList.Add($item);
				$this->mEleTotal += 1;

				if ($this->mIsSpeedUpFind)
				{
					$this->mDic[$item] = $this->Count() - 1;
				}
			}
		}
	}

	// 快速移除元素
	protected function effectiveRemove($item)
	{
		$ret = false;

		if ($this->mDic.ContainsKey($item))
		{
			$ret = true;

			$idx = $this->mDic[$item];
			$this->mDic->Remove($item);

			if (idx == $this->Count() - 1)    // 如果是最后一个元素，直接移除
			{
				$this->mList.RemoveAt($idx);
				$this->mEleTotal -= 1;
			}
			else
			{
				if (!$this->mIsOpKeepSort)
				{
					$this->mList[$idx] = $this->mList[$this->Count() - 1];
					$this->mList->RemoveAt($this->Count() - 1);
					$this->mDic[$this->mList[$idx]] = $idx;
					$this->mEleTotal -= 1;
				}
				else
				{
					$this->mList.RemoveAt($idx);
					$this->mEleTotal -= 1;
					$this->updateIndex($idx);
				}
			}
		}

		return ret;
	}

	protected function updateIndex($idx)
	{
		$len = $this->Count();

		while($idx < $len)
		{
			$this->mDic[$this->mList[$idx]] = $idx;

			++$idx;
		}
	}
}

?>