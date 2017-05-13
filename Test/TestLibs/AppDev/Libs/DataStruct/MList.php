using System.Collections.Generic;

namespace SDK.Lib
{
/**
 * @brief 对系统 List 的封装
 */
public class MList<T>
{
	//public delegate int CompareFunc(T left, T right);

	protected List<T> mList;
	protected int mUniqueId;       // 唯一 Id ，调试使用
	protected int mEleTotal;       // 元素总数

	protected Dictionary<T, int> mDic;    // 为了加快查找速度，当前 Element 到索引映射
	protected bool mIsSpeedUpFind;  // 是否加快查询，这个只适用于元素在列表中是唯一的，例如引用之类的，如果有相同的，就会有问题，注意了
	protected bool mIsOpKeepSort;           // 操作的时候是否保持排序

	public MList()
	{
		$this->mEleTotal = 0;
		$this->mList = new List<T>();
		$this->mIsSpeedUpFind = false;
		$this->mIsOpKeepSort = false;
	}

	public MList(int capacity)
	{
		$this->mList = new List<T>(capacity);
		$this->mEleTotal = capacity;
	}

	public void setIsSpeedUpFind(bool value)
	{
		$this->mIsSpeedUpFind = value;

		if($this->mIsSpeedUpFind)
		{
			$this->mDic = new Dictionary<T, int>();
		}
	}

	public void setIsOpKeepSort(bool value)
	{
		$this->mIsOpKeepSort = value;
	}

	public T[] ToArray()
	{
		return $this->mList.ToArray();
	}

	public List<T> list()
	{
		return $this->mList;
	}

	public int uniqueId
	{
		get
		{
			return $this->mUniqueId;
		}
		set
		{
			$this->mUniqueId = value;
		}
	}

	public List<T> buffer
	{
		get
		{
			return $this->mList;
		}
	}

	public int size
	{
		get
		{
			// 频繁获取这个字段比较耗时
			//return $this->mList.Count;
			return $this->mEleTotal;
		}
	}

	public void Add(T item)
	{
		$this->mList.Add(item);
		$this->mEleTotal += 1;

		if ($this->mIsSpeedUpFind)
		{
			$this->mDic[item] = $this->Count() - 1;
		}
	}

	// 主要是 Add 一个 float 类型的 Vector3
	public void Add(T item_1, T item_2, T item_3)
	{
		$this->mList.Add(item_1);
		$this->mEleTotal += 1;
		$this->mList.Add(item_2);
		$this->mEleTotal += 1;
		$this->mList.Add(item_3);
		$this->mEleTotal += 1;

		if ($this->mIsSpeedUpFind)
		{
			$this->mDic[item_1] = $this->Count() - 3;
			$this->mDic[item_2] = $this->Count() - 2;
			$this->mDic[item_3] = $this->Count() - 1;
		}
	}

	// 主要是 Add 一个 float 类型的 UV
	public void Add(T item_1, T item_2)
	{
		$this->mList.Add(item_1);
		$this->mEleTotal += 1;
		$this->mList.Add(item_2);
		$this->mEleTotal += 1;

		if ($this->mIsSpeedUpFind)
		{
			$this->mDic[item_1] = $this->Count() - 2;
			$this->mDic[item_2] = $this->Count() - 1;
		}
	}

	// 主要是 Add 一个 byte 类型的 Color32
	public void Add(T item_1, T item_2, T item_3, T item_4)
	{
		$this->mList.Add(item_1);
		$this->mEleTotal += 1;
		$this->mList.Add(item_2);
		$this->mEleTotal += 1;
		$this->mList.Add(item_3);
		$this->mEleTotal += 1;
		$this->mList.Add(item_4);
		$this->mEleTotal += 1;

		if ($this->mIsSpeedUpFind)
		{
			$this->mDic[item_1] = $this->Count() - 4;
			$this->mDic[item_2] = $this->Count() - 3;
			$this->mDic[item_3] = $this->Count() - 2;
			$this->mDic[item_4] = $this->Count() - 1;
		}
	}

	public void push(T item)
	{
		$this->mList.Add(item);
		$this->mEleTotal += 1;

		if ($this->mIsSpeedUpFind)
		{
			$this->mDic[item] = $this->Count() - 1;
		}
	}

	public bool Remove(T item)
	{
		if ($this->mIsSpeedUpFind)
		{
			return $this->effectiveRemove(item);
		}
		else
		{
			bool ret = $this->mList.Remove(item);

			if(ret)
			{
				$this->mEleTotal -= 1;
			}
			return ret;
		}
	}

	public T this[int index]
	{
		get
		{
			return $this->mList[index];
		}
		set
		{
			if ($this->mIsSpeedUpFind)
			{
				$this->mDic[value] = index;
			}

			$this->mList[index] = value;
		}
	}

	public T get(int index)
	{
		return $this->mList[index];
	}

	public void set(int index, T value)
	{
		$this->mList[index] = value;
	}

	public void Clear()
	{
		$this->mList.Clear();
		$this->mEleTotal = 0;

		if ($this->mIsSpeedUpFind)
		{
			$this->mDic.Clear();
		}
	}

	public int Count()
	{
		//return $this->mList.Count;
		return $this->mEleTotal;
	}

	public int length()
	{
		//return $this->mList.Count;
		return $this->mEleTotal;
	}

	public void setLength(int value)
	{
		$this->mList.Capacity = value;
	}

	public void RemoveAt(int index)
	{
		if ($this->mIsSpeedUpFind)
		{
			$this->effectiveRemove($this->mList[index]);
		}
		else
		{
			if (index < $this->Count())
			{
				$this->mList.RemoveAt(index);
				$this->mEleTotal -= 1;
			}
		}
	}

	public int IndexOf(T item)
	{
		if ($this->mIsSpeedUpFind)
		{
			if ($this->mDic.ContainsKey(item))
			{
				return $this->mDic[item];
			}
			else
			{
				return -1;
			}
		}
		else
		{
			return $this->mList.IndexOf(item);
		}
	}

	public void Insert(int index, T item)
	{
		if (index <= $this->Count())
		{
			$this->mList.Insert(index, item);
			$this->mEleTotal += 1;

			if ($this->mIsSpeedUpFind)
			{
				$this->mDic[item] = index;

				$this->updateIndex(index + 1);
			}
		}
		else
		{

		}
	}

	public bool Contains(T item)
	{
		if ($this->mIsSpeedUpFind)
		{
			return $this->mDic.ContainsKey(item);
		}
		else
		{
			return $this->mList.Contains(item);
		}
	}

	public void Sort(System.Comparison<T> comparer)
	{
		$this->mList.Sort(comparer);
	}

	public void merge(MList<T> appendList)
	{
		if(appendList != null)
		{
			foreach(T item in appendList.list())
			{
				$this->mList.Add(item);
				$this->mEleTotal += 1;

				if ($this->mIsSpeedUpFind)
				{
					$this->mDic[item] = $this->Count() - 1;
				}
			}
		}
	}

	// 快速移除元素
	protected bool effectiveRemove(T item)
	{
		bool ret = false;

		if ($this->mDic.ContainsKey(item))
		{
			ret = true;

			int idx = $this->mDic[item];
			$this->mDic.Remove(item);

			if (idx == $this->Count() - 1)    // 如果是最后一个元素，直接移除
			{
				$this->mList.RemoveAt(idx);
				$this->mEleTotal -= 1;
			}
			else
			{
				if (!$this->mIsOpKeepSort)
				{
					$this->mList[idx] = $this->mList[$this->Count() - 1];
					$this->mList.RemoveAt($this->Count() - 1);
					$this->mDic[$this->mList[idx]] = idx;
					$this->mEleTotal -= 1;
				}
				else
				{
					$this->mList.RemoveAt(idx);
					$this->mEleTotal -= 1;
					$this->updateIndex(idx);
				}
			}
		}

		return ret;
	}

	protected void updateIndex(int idx)
	{
		int len = $this->Count();

		while(idx < len)
		{
			$this->mDic[$this->mList[idx]] = idx;

			++idx;
		}
	}
}
}