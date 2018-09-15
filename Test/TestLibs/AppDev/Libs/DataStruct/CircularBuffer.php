<?php

/**
*@brief 环形缓冲区，不支持多线程写操作
*/
namespace SDK\Lib;

/**
 * @brief 浪费一个自己，这样判断也好判断，并且索引也不用减 1 ，因此浪费一个字节
 * @brief 判空: mFirst == mLast
 * @brief 判满: mFirst == (mLast + 1) % len
 */
class CircularBuffer
{
	protected $mDynBuffer;
	protected $mFirst;             // 当前缓冲区数据的第一个索引
	protected $mLast;              // 当前缓冲区数据的最后一个索引的后面一个索引，浪费一个字节
	protected $mTmpBA;        // 临时数据

	public function __construct($initCapacity = BufferCV::INIT_CAPACITY, $maxCapacity = BufferCV::MAX_CAPACITY)
	{
		$this->mDynBuffer = new DynBuffer($initCapacity, $maxCapacity);

		$this->mFirst = 0;
		$this->mLast = 0;

		$this->mTmpBA = new ByteBuffer();
	}

	public function getFirst()
	{
		return $this->mFirst;
	}

	public function getLast()
	{
		return $this->mLast;
	}

	public function getBuffer()
	{
		return $this->mDynBuffer->mBuffer;
	}

	public function getSize()
	{
		return $this->mDynBuffer->mSize;
	}
	
	public function setSize()
	{
		$this->mDynBuffer->setSize($value);
	}

	public function isLinearized()
	{
		if ($this->size == 0)
		{
			return true;
		}

		return $this->mFirst < $this->mLast;
	}

	public function empty()
	{
		return $this->mDynBuffer->mSize == 0;
	}

	public function capacity()
	{
		return $this->mDynBuffer->mCapacity;
	}

	public function full()
	{ 
		return $this->capacity() == $this->getSize();
	}

	// 清空缓冲区
	public function clear()
	{
		$this->mDynBuffer->mSize = 0;
		$this->mFirst = 0;
		$this->mLast = 0;
	}

	/**
	 * @brief 将数据尽量按照存储地址的从小到大排列
	 */
	public function linearize()
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
			$tmp = array();
			UtilList::setCapacity($tmp, $this->mLast);
			UtilList::Copy($this->mDynBuffer->mBuffer, 0, $tmp, 0, mLast);  // 拷贝一段内存空间中的数据到 tmp
			UtilList::Copy($this->mDynBuffer->mBuffer, $this->mFirst, $this->mDynBuffer->mBuffer, 0, $this->mDynBuffer->mCapacity - $this->mFirst);  // 拷贝第一段数据到 0 索引位置
			UtilList::Copy(tmp, 0, $this->mDynBuffer->mBuffer, $this->mDynBuffer->mCapacity - $this->mFirst, $this->mLast);      // 拷贝第二段数据到缓冲区

			$this->mFirst = 0;
			$this->mLast = $this->size;
		}
	}

	/**
	 * @brief 更改存储内容空间大小
	 */
	protected function setCapacity($newCapacity) 
	{
		if (newCapacity == $this->capacity())
		{
			return;
		}
		if (newCapacity < $this->size)       // 不能分配比当前已经占有的空间还小的空间
		{
			return;
		}
		
		$tmpbuff = array();   // 分配新的空间
		UtilList::setCapacity($tmpbuff, $newCapacity);
		
		if ($this->isLinearized()) // 如果是在一段内存空间
		{
			// 已经是线性空间了仍然将数据移动到索引 0 的位置
		    UtilList::Copy($this->mDynBuffer->mBuffer, $this->mFirst, tmpbuff, 0, $this->mDynBuffer->mSize);
		}
		else    // 如果在两端内存空间
		{
		    UtilList::Copy($this->mDynBuffer->mBuffer, $this->mFirst, $tmpbuff, 0, $this->mDynBuffer->mCapacity - $this->mFirst);
		    UtilList::Copy($this->mDynBuffer->mBuffer, 0, $tmpbuff, $this->mDynBuffer->mCapacity - $this->mFirst, $this->mLast);
		}

		$this->mFirst = 0;
		$this->mLast = mDynBuffer->mSize;
		$this->mDynBuffer->mCapacity = $newCapacity;
		$this->mDynBuffer->mBuffer = $tmpbuff;
	}

	/**
	 *@brief 能否添加 num 长度的数据
	 */
	protected function canAddData($num)
	{
		if ($this->mDynBuffer->mCapacity - $this->mDynBuffer->mSize > $num) // 浪费一个字节，不用 >= ，使用 > 
		{
			return true;
		}

		return false;
	}

	/**
	 *@brief 向存储空尾部添加一段内容
	 */
	public function pushBackArr($items, $start, $len)
	{
		if (!$this->canAddData(len)) // 存储空间必须要比实际数据至少多 1
		{
			$closeSize = DynBufResizePolicy->getCloseSize(len + $this->mDynBuffer->mSize, $this->mDynBuffer->mCapacity, $this->mDynBuffer->mMaxCapacity);
			$this->setCapacity($closeSize);
		}

		if ($this->isLinearized())
		{
			if (len <= ($this->mDynBuffer->mCapacity - $this->mLast))
			{
				UtilList::Copy(items, start, $this->mDynBuffer->mBuffer, $this->mLast, $len);
			}
			else
			{
			    UtilList::Copy($items, $start, $this->mDynBuffer->mBuffer, $this->mLast, mDynBuffer->mCapacity - $this->mLast);
			    UtilList::Copy($items, $this->mDynBuffer->mCapacity - $this->mLast, $this->mDynBuffer->mBuffer, 0, $len - ($this->mDynBuffer->mCapacity - $this->mLast));
			}
		}
		else
		{
		    UtilList::Copy($items, $start, $this->mDynBuffer->mBuffer, $this->mLast, $len);
		}

		$this->mLast += $len;
		$this->mLast %= $this->mDynBuffer->mCapacity;

		$this->mDynBuffer->mSize += $len;
	}

	public function pushBackBA($byteBuffer)
	{
		//pushBack(bu->dynBuff->buffer, bu->position, bu->bytesAvailable);
	    $this->pushBackArr($byteBuffer->getDynBuffer()->getBuffer(), 0, $byteBuffer->getLength());
	}

	/**
	 *@brief 向存储空头部添加一段内容
	 */
	protected function pushFrontArr($byteArray)
	{
	    $byteLength = UtilList::count($byteArray);
	    
	    if (!$this->canAddData($byteLength)) // 存储空间必须要比实际数据至少多 1
		{
		    $closeSize = DynBufResizePolicy::getCloseSize($byteLength + $this->mDynBuffer->mSize, $this->mDynBuffer->mCapacity, $this->mDynBuffer->mMaxCapacity);
		    $this->setCapacity($closeSize);
		}

		if ($this->isLinearized())
		{
		    if ($byteLength <= $this->mFirst)
			{
			    UtilList::Copy($byteArray, 0, $this->mDynBuffer->mBuffer, $this->mFirst - items->Length, $byteLength);
			}
			else
			{
			    UtilList::Copy($byteArray, $byteLength - $this->mFirst, $this->mDynBuffer->mBuffer, 0, $this->mFirst);
			    UtilList::Copy($byteArray, 0, $this->mDynBuffer->mBuffer, $this->mDynBuffer->mCapacity - ($byteLength - $this->mFirst), $byteLength - $this->mFirst);
			}
		}
		else
		{
		    UtilList::Copy($byteArray, 0, $this->mDynBuffer->mBuffer, $this->mFirst - $byteLength, $byteLength);
		}

		if (items->Length <= $this->mFirst)
		{
		    $this->mFirst -= $byteLength;
		}
		else
		{
		    $this->mFirst = $this->mDynBuffer->mCapacity - ($byteLength - $this->mFirst);
		}

		$this->mDynBuffer->mSize += $byteLength;
	}

	/**
	 * @brief 从 CB 中读取内容，并且将数据删除
	 */
	public function popFrontBA($byteBuffer, $len)
	{
	    $this->frontBA($byteBuffer, $len);
	    $this->popFrontLen($len);
	}

	// 仅仅是获取数据，并不删除
	public function frontBA($byteBuffer, $len)
	{
	    $byteBuffer->clear();          // 设置数据为初始值

	    if ($this->mDynBuffer->mSize >= $len)          // 头部占据 4 个字节
		{
			if ($this->isLinearized())      // 在一段连续的内存
			{
			    $byteBuffer->writeBytes($this->mDynBuffer->mBuffer, $this->mFirst, $len);
			}
			else if ($this->mDynBuffer->mCapacity - $this->mFirst >= $len)
			{
			    $byteBuffer->writeBytes($this->mDynBuffer->mBuffer, $this->mFirst, $len);
			}
			else
			{
			    $byteBuffer->writeBytes($this->mDynBuffer->mBuffer, $this->mFirst, $this->mDynBuffer->mCapacity - $this->mFirst);
			    $byteBuffer->writeBytes($this->mDynBuffer->mBuffer, 0, $len - ($this->mDynBuffer->mCapacity - $this->mFirst));
			}
		}

		$byteBuffer->setPos(0);        // 设置数据读取起始位置
	}

	/**
	 * @brief 从 CB 头部删除数据
	 */
	public function popFrontLen($len)
	{
		if ($this->isLinearized())  // 在一段连续的内存
		{
		    $this->mFirst += $len;
		}
		else if ($this->mDynBuffer->mCapacity - $this->mFirst >= $len)
		{
		    $this->mFirst += $len;
		}
		else
		{
		    $this->mFirst = $len - ($this->mDynBuffer->mCapacity - $this->mFirst);
		}

		$this->mDynBuffer->mSize -= $len;
	}

	// 向自己尾部添加一个 CircularBuffer
	public function pushBackCB($circularBuffer)
	{
	    if($this->mDynBuffer->mCapacity - $this->mDynBuffer->mSize < $circularBuffer->getSize())
		{
		    $closeSize = DynBufResizePolicy->getCloseSize($circularBuffer->getSize() + $this->mDynBuffer->mSize, $this->mDynBuffer->mCapacity, $this->mDynBuffer->mMaxCapacity);
			$this->setCapacity($closeSize);
		}

		//$this->mSize += rhv->size;
		//$this->mLast = $this->mSize;

		//mTmpBA->clear();
		$circularBuffer->frontBA($this->mTmpBA, $circularBuffer->getSize());
		$this->pushBackBA($this->mTmpBA);

		//if (rhv->isLinearized()) // 如果是在一段内存空间
		//{
		//    Array->Copy(rhv->buff, rhv->first, mBuffer, 0, rhv->size);
		//}
		//else    // 如果在两端内存空间
		//{
		//    Array::Copy(rhv->buff, rhv->first, mBuffer, 0, rhv->capacity() - rhv->first);
		//    Array::Copy(mBuffer, 0, mBuffer, rhv->capacity() - rhv->first, rhv->last);
		//}
		//rhv->clear();
	}
}

?>