using System;

/**
*@brief 环形缓冲区，不支持多线程写操作
*/
namespace SDK\Lib;
{
/**
 * @brief 浪费一个自己，这样判断也好判断，并且索引也不用减 1 ，因此浪费一个字节
 * @brief 判空: mFirst == mLast
 * @brief 判满: mFirst == (mLast + 1) % len
 */
public class CircularBuffer
{
	protected DynBuffer<byte> mDynBuffer;
	protected uint mFirst;             // 当前缓冲区数据的第一个索引
	protected uint mLast;              // 当前缓冲区数据的最后一个索引的后面一个索引，浪费一个字节
	protected ByteBuffer mTmpBA;        // 临时数据

	public CircularBuffer(uint initCapacity = BufferCV.INIT_CAPACITY, uint maxCapacity = BufferCV.MAX_CAPACITY)
	{
		$this->mDynBuffer = new DynBuffer<byte>(initCapacity, maxCapacity);

		$this->mFirst = 0;
		$this->mLast = 0;

		$this->mTmpBA = new ByteBuffer();
	}

	public uint first
	{
		get
		{
			return $this->mFirst;
		}
	}

	public uint last
	{
		get
		{
			return $this->mLast;
		}
	}

	public byte[] buffer
	{
		get
		{
			return $this->mDynBuffer.mBuffer;
		}
	}

	public uint size
	{
		get
		{
			return $this->mDynBuffer.mSize;
		}
		set
		{
			$this->mDynBuffer.size = value;
		}
	}

	public bool isLinearized()
	{
		if ($this->size == 0)
		{
			return true;
		}

		return $this->mFirst < $this->mLast;
	}

	public bool empty()
	{
		return $this->mDynBuffer.mSize == 0;
	}

	public uint capacity()
	{
		return $this->mDynBuffer.mCapacity;
	}

	public bool full()
	{ 
		return $this->capacity() == $this->size;
	}

	// 清空缓冲区
	public void clear()
	{
		$this->mDynBuffer.mSize = 0;
		$this->mFirst = 0;
		$this->mLast = 0;
	}

	/**
	 * @brief 将数据尽量按照存储地址的从小到大排列
	 */
	public void linearize()
	{
		if ($this->empty())        // 没有数据
		{
			return;
		}
		if ($this->isLinearized())      // 数据已经是在一块连续的内存空间
		{
			return;
		}
		else
		{
			// 数据在两个不连续的内存空间中
			char[] tmp = new char[mLast];
			Array.Copy($this->mDynBuffer.mBuffer, 0, tmp, 0, mLast);  // 拷贝一段内存空间中的数据到 tmp
			Array.Copy($this->mDynBuffer.mBuffer, $this->mFirst, $this->mDynBuffer.mBuffer, 0, $this->mDynBuffer.mCapacity - $this->mFirst);  // 拷贝第一段数据到 0 索引位置
			Array.Copy(tmp, 0, $this->mDynBuffer.mBuffer, $this->mDynBuffer.mCapacity - $this->mFirst, $this->mLast);      // 拷贝第二段数据到缓冲区

			$this->mFirst = 0;
			$this->mLast = $this->size;
		}
	}

	/**
	 * @brief 更改存储内容空间大小
	 */
	protected void setCapacity(uint newCapacity) 
	{
		if (newCapacity == $this->capacity())
		{
			return;
		}
		if (newCapacity < $this->size)       // 不能分配比当前已经占有的空间还小的空间
		{
			return;
		}
		byte[] tmpbuff = new byte[newCapacity];   // 分配新的空间
		if ($this->isLinearized()) // 如果是在一段内存空间
		{
			// 已经是线性空间了仍然将数据移动到索引 0 的位置
			Array.Copy($this->mDynBuffer.mBuffer, $this->mFirst, tmpbuff, 0, $this->mDynBuffer.mSize);
		}
		else    // 如果在两端内存空间
		{
			Array.Copy($this->mDynBuffer.mBuffer, $this->mFirst, tmpbuff, 0, $this->mDynBuffer.mCapacity - $this->mFirst);
			Array.Copy($this->mDynBuffer.mBuffer, 0, tmpbuff, $this->mDynBuffer.mCapacity - $this->mFirst, $this->mLast);
		}

		$this->mFirst = 0;
		$this->mLast = mDynBuffer.mSize;
		$this->mDynBuffer.mCapacity = newCapacity;
		$this->mDynBuffer.mBuffer = tmpbuff;
	}

	/**
	 *@brief 能否添加 num 长度的数据
	 */
	protected bool canAddData(uint num)
	{
		if ($this->mDynBuffer.mCapacity - $this->mDynBuffer.mSize > num) // 浪费一个字节，不用 >= ，使用 > 
		{
			return true;
		}

		return false;
	}

	/**
	 *@brief 向存储空尾部添加一段内容
	 */
	public void pushBackArr(byte[] items, uint start, uint len)
	{
		if (!$this->canAddData(len)) // 存储空间必须要比实际数据至少多 1
		{
			uint closeSize = DynBufResizePolicy.getCloseSize(len + $this->mDynBuffer.mSize, $this->mDynBuffer.mCapacity, $this->mDynBuffer.mMaxCapacity);
			$this->setCapacity(closeSize);
		}

		if ($this->isLinearized())
		{
			if (len <= ($this->mDynBuffer.mCapacity - $this->mLast))
			{
				Array.Copy(items, start, $this->mDynBuffer.mBuffer, $this->mLast, len);
			}
			else
			{
				Array.Copy(items, start, $this->mDynBuffer.mBuffer, mLast, mDynBuffer.mCapacity - mLast);
				Array.Copy(items, $this->mDynBuffer.mCapacity - $this->mLast, $this->mDynBuffer.mBuffer, 0, len - ($this->mDynBuffer.mCapacity - $this->mLast));
			}
		}
		else
		{
			Array.Copy(items, start, $this->mDynBuffer.mBuffer, $this->mLast, len);
		}

		$this->mLast += len;
		$this->mLast %= $this->mDynBuffer.mCapacity;

		$this->mDynBuffer.mSize += len;
	}

	public void pushBackBA(ByteBuffer bu)
	{
		//pushBack(bu.dynBuff.buffer, bu.position, bu.bytesAvailable);
		$this->pushBackArr(bu.dynBuffer.buffer, 0, bu.length);
	}

	/**
	 *@brief 向存储空头部添加一段内容
	 */
	protected void pushFrontArr(byte[] items)
	{
		if (!canAddData((uint)items.Length)) // 存储空间必须要比实际数据至少多 1
		{
			uint closeSize = DynBufResizePolicy.getCloseSize((uint)items.Length + $this->mDynBuffer.mSize, $this->mDynBuffer.mCapacity, $this->mDynBuffer.mMaxCapacity);
			$this->setCapacity(closeSize);
		}

		if ($this->isLinearized())
		{
			if (items.Length <= mFirst)
			{
				Array.Copy(items, 0, $this->mDynBuffer.mBuffer, $this->mFirst - items.Length, items.Length);
			}
			else
			{
				Array.Copy(items, items.Length - $this->mFirst, $this->mDynBuffer.mBuffer, 0, $this->mFirst);
				Array.Copy(items, 0, $this->mDynBuffer.mBuffer, $this->mDynBuffer.mCapacity - (items.Length - $this->mFirst), items.Length - $this->mFirst);
			}
		}
		else
		{
			Array.Copy(items, 0, $this->mDynBuffer.mBuffer, $this->mFirst - items.Length, items.Length);
		}

		if (items.Length <= mFirst)
		{
			$this->mFirst -= (uint)items.Length;
		}
		else
		{
			$this->mFirst = $this->mDynBuffer.mCapacity - ((uint)items.Length - $this->mFirst);
		}

		$this->mDynBuffer.mSize += (uint)items.Length;
	}

	/**
	 * @brief 从 CB 中读取内容，并且将数据删除
	 */
	public void popFrontBA(ByteBuffer bytearray, uint len)
	{
		$this->frontBA(bytearray, len);
		$this->popFrontLen(len);
	}

	// 仅仅是获取数据，并不删除
	public void frontBA(ByteBuffer bytearray, uint len)
	{
		bytearray.clear();          // 设置数据为初始值

		if ($this->mDynBuffer.mSize >= len)          // 头部占据 4 个字节
		{
			if ($this->isLinearized())      // 在一段连续的内存
			{
				bytearray.writeBytes($this->mDynBuffer.mBuffer, $this->mFirst, len);
			}
			else if ($this->mDynBuffer.mCapacity - $this->mFirst >= len)
			{
				bytearray.writeBytes($this->mDynBuffer.mBuffer, $this->mFirst, len);
			}
			else
			{
				bytearray.writeBytes($this->mDynBuffer.mBuffer, $this->mFirst, $this->mDynBuffer.mCapacity - $this->mFirst);
				bytearray.writeBytes($this->mDynBuffer.mBuffer, 0, len - ($this->mDynBuffer.mCapacity - $this->mFirst));
			}
		}

		bytearray.position = 0;        // 设置数据读取起始位置
	}

	/**
	 * @brief 从 CB 头部删除数据
	 */
	public void popFrontLen(uint len)
	{
		if ($this->isLinearized())  // 在一段连续的内存
		{
			$this->mFirst += len;
		}
		else if ($this->mDynBuffer.mCapacity - $this->mFirst >= len)
		{
			$this->mFirst += len;
		}
		else
		{
			$this->mFirst = len - ($this->mDynBuffer.mCapacity - $this->mFirst);
		}

		$this->mDynBuffer.mSize -= len;
	}

	// 向自己尾部添加一个 CircularBuffer
	public void pushBackCB(CircularBuffer rhv)
	{
		if($this->mDynBuffer.mCapacity - $this->mDynBuffer.mSize < rhv.size)
		{
			uint closeSize = DynBufResizePolicy.getCloseSize(rhv.size + $this->mDynBuffer.mSize, $this->mDynBuffer.mCapacity, $this->mDynBuffer.mMaxCapacity);
			$this->setCapacity(closeSize);
		}

		//$this->mSize += rhv.size;
		//$this->mLast = $this->mSize;

		//mTmpBA.clear();
		rhv.frontBA($this->mTmpBA, rhv.size);
		pushBackBA($this->mTmpBA);

		//if (rhv.isLinearized()) // 如果是在一段内存空间
		//{
		//    Array.Copy(rhv.buff, rhv.first, mBuffer, 0, rhv.size);
		//}
		//else    // 如果在两端内存空间
		//{
		//    Array.Copy(rhv.buff, rhv.first, mBuffer, 0, rhv.capacity() - rhv.first);
		//    Array.Copy(mBuffer, 0, mBuffer, rhv.capacity() - rhv.first, rhv.last);
		//}
		//rhv.clear();
	}
}
}