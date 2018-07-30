<?php

namespace SDK\Lib;

/**
 * @brief 缓存需要的数据，更容易控制需要缓存多少个，以及各种统计信息，但是申请一个 Item 需要一次 RemoveAt 和 Add，而比仅仅使用一个列表多了一次 Add
 */
public class IndexData
{
	protected int mTotalNum;
	protected int mInitNum;
	protected MList<int> mFreeIndexList;
	protected MList<int> mActiveIndexList;
	protected IndexItemBase[] mItemArray;

	public IndexData()
	{
		$this->mTotalNum = 32;
		$this->mInitNum = 32;

		$this->mItemArray = new IndexItemBase[$this->mInitNum];

		$this->mFreeIndexList = new MList<int>();
		$this->mFreeIndexList.setIsSpeedUpFind(true);
		$this->mFreeIndexList.setIsOpKeepSort(false);

		$this->mActiveIndexList = new MList<int>();
		$this->mActiveIndexList.setIsSpeedUpFind(true);
		$this->mActiveIndexList.setIsOpKeepSort(true);

		int index = 0;
		int listLen = $this->mInitNum;

		while(index < listLen)
		{
			$this->mFreeIndexList.Add(index);

			index += 1;
		}
	}

	// 只支持初始化的时候设置
	public void setTotalNum(int value)
	{
		$this->mTotalNum = value;
	}

	virtual public IndexItemBase newItem()
	{
		return null;
	}

	public IndexItemBase newFreeItem()
	{
		int freeIndex = -1;

		// 如果没有 free 数据
		if($this->mFreeIndexList.Count() == 0)
		{
			int expandNum = 0;

			// 大于 0 说明有限制
			if ($this->mTotalNum > 0)
			{
				// 如果还没有增大到最大限制
				if ($this->mInitNum < $this->mTotalNum)
				{
					if (2 * $this->mInitNum <= $this->mTotalNum)
					{
						expandNum = 2 * $this->mInitNum;
					}
					else
					{
						expandNum = $this->mTotalNum;
					}
				}
				else
				{
					throw new System.Exception("aaa");
				}
			}
			else
			{
				expandNum = 2 * $this->mInitNum;
			}

			// 重新生成扩大的数组
			IndexItemBase[] tmpItemArray = $this->mItemArray;
			$this->mItemArray = new IndexItemBase[expandNum];

			// 将原始数据拷贝到扩大的数组中
			int index = 0;
			int listLen = tmpItemArray.Length;

			while(index < listLen)
			{
				$this->mItemArray[index] = tmpItemArray[index];
				index += 1;
			}

			// free 添加新元素
			index = tmpItemArray.Length;
			listLen = $this->mItemArray.Length;

			while (index < listLen)
			{
				$this->mFreeIndexList.Add(index);
				index += 1;
			}

			$this->mInitNum = expandNum;
		}

		// 获取 free 元素索引
		freeIndex = $this->mFreeIndexList.get(0);
		$this->mFreeIndexList.RemoveAt(0);        // 申请需要一次 RemoveAt
		$this->mActiveIndexList.Add(freeIndex);   // 申请需要一次 Add

		// 创建 free 元素
		if (null == $this->mItemArray[freeIndex])
		{
			$this->mItemArray[freeIndex] = $this->newItem();
			$this->mItemArray[freeIndex].setIndex(freeIndex);
		}

		return $this->mItemArray[freeIndex];
	}

	public void deleteFreeItem(IndexItemBase item)
	{
		if(-1 != item.getIndex())
		{
			$this->mFreeIndexList.Add(item.getIndex());
		}
	}
}

?>