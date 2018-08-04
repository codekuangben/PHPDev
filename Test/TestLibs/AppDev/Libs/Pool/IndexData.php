<?php

namespace SDK\Lib;

/**
 * @brief 缓存需要的数据，更容易控制需要缓存多少个，以及各种统计信息，但是申请一个 Item 需要一次 RemoveAt 和 Add，而比仅仅使用一个列表多了一次 Add
 */
class IndexData
{
	protected $mTotalNum;
	protected $mInitNum;
	protected $mFreeIndexList;
	protected $mActiveIndexList;
	protected $mItemArray;

	public function __construct()
	{
		$this->mTotalNum = 32;
		$this->mInitNum = 32;

		$this->mItemArray = Array();
		UtilList::setCapacity($this->mItemArray, $this->mInitNum);

		$this->mFreeIndexList = new MList();
		$this->mFreeIndexList.setIsSpeedUpFind(true);
		$this->mFreeIndexList.setIsOpKeepSort(false);

		$this->mActiveIndexList = new MList();
		$this->mActiveIndexList.setIsSpeedUpFind(true);
		$this->mActiveIndexList.setIsOpKeepSort(true);

		$index = 0;
		$listLen = $this->mInitNum;

		while($index < $listLen)
		{
			$this->mFreeIndexList.Add($index);

			$index += 1;
		}
	}

	// 只支持初始化的时候设置
	public function setTotalNum($value)
	{
		$this->mTotalNum = $value;
	}

	public function newItem()
	{
		return null;
	}

	public function newFreeItem()
	{
		$freeIndex = -1;

		// 如果没有 free 数据
		if($this->mFreeIndexList.Count() == 0)
		{
			$expandNum = 0;

			// 大于 0 说明有限制
			if ($this->mTotalNum > 0)
			{
				// 如果还没有增大到最大限制
				if ($this->mInitNum < $this->mTotalNum)
				{
					if (2 * $this->mInitNum <= $this->mTotalNum)
					{
						$expandNum = 2 * $this->mInitNum;
					}
					else
					{
						$expandNum = $this->mTotalNum;
					}
				}
				else
				{
					throw new \Exception("aaa");
				}
			}
			else
			{
				$expandNum = 2 * $this->mInitNum;
			}

			// 重新生成扩大的数组
			$tmpItemArray = $this->mItemArray;
			$this->mItemArray = Array();
			UtilList::setCapacity($this->mItemArray, $expandNum);

			// 将原始数据拷贝到扩大的数组中
			$index = 0;
			$listLen = UtilList::count($tmpItemArray);

			while($index < $listLen)
			{
				$this->mItemArray[$index] = $tmpItemArray[$index];
				$index += 1;
			}

			// free 添加新元素
			$index = UtilList::count($tmpItemArray);
			$listLen = UtilList::count($this->mItemArray);

			while ($index < $listLen)
			{
				$this->mFreeIndexList.Add($index);
				$index += 1;
			}

			$this->mInitNum = $expandNum;
		}

		// 获取 free 元素索引
		$freeIndex = $this->mFreeIndexList.get(0);
		$this->mFreeIndexList.RemoveAt(0);        // 申请需要一次 RemoveAt
		$this->mActiveIndexList.Add(freeIndex);   // 申请需要一次 Add

		// 创建 free 元素
		if (null == $this->mItemArray[$freeIndex])
		{
			$this->mItemArray[$freeIndex] = $this->newItem();
			$this->mItemArray[$freeIndex].setIndex($freeIndex);
		}

		return $this->mItemArray[$freeIndex];
	}

	public function deleteFreeItem($item)
	{
		if(-1 != $item.getIndex())
		{
			$this->mFreeIndexList.Add($item.getIndex());
		}
	}
}

?>