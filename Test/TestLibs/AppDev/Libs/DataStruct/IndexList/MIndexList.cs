using System.Collections.Generic;

namespace SDK.Lib
{
/**
 * @brief MIndexList ，元素是保存一个在列表中的索引的，减少 Key 比较，加快查找
 */
public class MIndexList<T> where T : IndexItemBase, new()
{
	protected List<T> mList;
	protected int mUniqueId;       // 唯一 Id ，调试使用
	protected int mEleTotal;       // 元素总数
	protected bool mIsOpKeepSort;           // 操作的时候是否保持排序

	public MIndexList()
	{
		this.mEleTotal = 0;
		this.mList = new List<T>();
		this.mIsOpKeepSort = false;
	}

	public MIndexList(int capacity)
	{
		this.mList = new List<T>(capacity);
		this.mEleTotal = capacity;
	}

	public void setIsOpKeepSort(bool value)
	{
		this.mIsOpKeepSort = value;
	}

	public T[] ToArray()
	{
		return this.mList.ToArray();
	}

	public List<T> list()
	{
		return this.mList;
	}

	public int uniqueId
	{
		get
		{
			return this.mUniqueId;
		}
		set
		{
			this.mUniqueId = value;
		}
	}

	public List<T> buffer
	{
		get
		{
			return this.mList;
		}
	}

	public int size
	{
		get
		{
			// 频繁获取这个字段比较耗时
			//return this.mList.Count;
			return this.mEleTotal;
		}
	}

	public void Add(T item)
	{
		this.mList.Add(item);
		this.mEleTotal += 1;
	}

	// 主要是 Add 一个 float 类型的 Vector3
	public void Add(T item_1, T item_2, T item_3)
	{
		this.Add(item_1);
		this.Add(item_2);
		this.Add(item_3);
	}

	// 主要是 Add 一个 float 类型的 UV
	public void Add(T item_1, T item_2)
	{
		this.Add(item_1);
		this.Add(item_2);
	}

	// 主要是 Add 一个 byte 类型的 Color32
	public void Add(T item_1, T item_2, T item_3, T item_4)
	{
		this.Add(item_1);
		this.Add(item_2);
		this.Add(item_3);
		this.Add(item_4);
	}

	public void push(T item)
	{
		this.Add(item);
	}

	public bool Remove(T item)
	{
		return this.effectiveRemove(item);
	}

	public T this[int index]
	{
		get
		{
			return this.mList[index];
		}
		set
		{
			this.set(index, value);
		}
	}

	public T get(int index)
	{
		return this.mList[index];
	}

	public void set(int index, T value)
	{
		if(null != this.mList[index])
		{
			this.mList[index].resetIndex();
		}

		this.mList[index] = value;
		this.mList[index].setIndex(index);
	}

	public void Clear()
	{
		int index = 0;
		int listLen = this.mEleTotal;

		while(index < listLen)
		{
			this.mList[index].resetIndex();

			index += 1;
		}

		this.mList.Clear();
		this.mEleTotal = 0;
	}

	public int Count()
	{
		//return this.mList.Count;
		return this.mEleTotal;
	}

	public int length()
	{
		//return this.mList.Count;
		return this.mEleTotal;
	}

	public void setLength(int value)
	{
		this.mList.Capacity = value;
	}

	public void RemoveAt(int index)
	{
		if (index < this.Count())
		{
			this.mList[index].resetIndex();
			this.mList.RemoveAt(index);
			this.mEleTotal -= 1;
		}
	}

	public int IndexOf(T item)
	{
		if(item.getIndex() < this.Count())
		{
			return item.getIndex();
		}

		return -1;
	}

	public void Insert(int index, T item)
	{
		if (index <= this.Count())
		{
			this.mList.Insert(index, item);
			item.setIndex(index);
			this.mEleTotal += 1;
			this.updateIndex(index + 1);
		}
	}

	public bool Contains(T item)
	{
		return item.getIndex() != -1;
	}

	public void Sort(System.Comparison<T> comparer)
	{
		this.mList.Sort(comparer);
	}

	public void merge(MList<T> appendList)
	{
		if(appendList != null)
		{
			foreach(T item in appendList.list())
			{
				this.mList.Add(item);
				item.setIndex(this.mEleTotal);
				this.mEleTotal += 1;
			}
		}
	}

	// 快速移除元素
	protected bool effectiveRemove(T item)
	{
		bool ret = false;

		int idx = item.getIndex();

		if (-1 != idx)
		{
			ret = true;

			if (idx == this.Count() - 1)    // 如果是最后一个元素，直接移除
			{
				this.RemoveAt(idx);
			}
			else
			{
				if (!this.mIsOpKeepSort)
				{
					this.mList[idx] = this.mList[this.Count() - 1];
					this.RemoveAt(this.Count() - 1);
				}
				else
				{
					this.RemoveAt(idx);
					this.updateIndex(idx);
				}
			}
		}

		return ret;
	}

	protected void updateIndex(int idx)
	{
		int len = this.Count();

		while(idx < len)
		{
			this.mList[idx].setIndex(idx);

			++idx;
		}
	}
}
}