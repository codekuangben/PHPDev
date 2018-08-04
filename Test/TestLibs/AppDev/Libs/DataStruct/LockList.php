<?php

namespace SDK\Lib;

/**
 * @brief 线程安全列表， T 是 Object ，便于使用 Equal 比较地址
 */
class LockList
{
	protected $mDynamicBuffer;
	protected $mVisitMutex;
	protected $mRetItem;

	public function __construct($name, $initCapacity = 32/*DataCV.INIT_ELEM_CAPACITY*/, $maxCapacity = 8 * 1024 * 1024/*DataCV.MAX_CAPACITY*/)
	{
		$this->mDynamicBuffer = new DynBuffer($initCapacity, $maxCapacity);
		$this->mVisitMutex = new MMutex(false, $name);
	}

	public function Count()
	{
		$mlock = new MLock($this->mVisitMutex);
		{
			return $this->mDynamicBuffer->mSize;
		}
	}

	public function get($index) 
	{
		$mlock = new MLock($this->mVisitMutex);
		{
			if ($index< $this->mDynamicBuffer.mSize)
			{
				return $this->mDynamicBuffer->mBuffer[$index];
			}
			else
			{
				return null;
			}
		}
	}

	public function set($index, $item) 
	{
		$mlock = new MLock($this->mVisitMutex);
		{
			$this->mDynamicBuffer->mBuffer->set($index, $value);
		}
	}


	public function Add($item)
	{
		$mlock = new MLock($this->mVisitMutex);
		{
			if ($this->mDynamicBuffer->mSize >= $this->mDynamicBuffer->mCapacity)
			{
				$this->mDynamicBuffer->extendDeltaCapicity(1);
			}

			$this->mDynamicBuffer->mBuffer->set($this->mDynamicBuffer->mSize, item);
			++$this->mDynamicBuffer->mSize;
		}
	}

	public function Remove($item)
	{
		$mlock = new MLock($this->mVisitMutex);
		{
			$idx = 0;
			foreach($this->mDynamicBuffer->mBuffer as $elem)
			{
				if(UtilSysLibWrap::isObjectEqual($item, $elem))       // 地址比较
				{
					$this->RemoveAt($idx);
					return true;
				}

				++$idx;
			}

			return false;
		}
	}

	public function RemoveAt($index)
	{
		$mlock = new MLock($this->mVisitMutex);
		{
			if ($index < $this->mDynamicBuffer->mSize)
			{
				$this->mRetItem = $this->mDynamicBuffer->mBuffer[$index];

				if ($index < $this->mDynamicBuffer->mSize)
				{
					if ($index != $this->mDynamicBuffer->mSize - 1 && 1 != $this->mDynamicBuffer->mSize) // 如果删除不是最后一个元素或者总共就大于一个元素
					{
						UtilList.Copy($this->mDynamicBuffer->mBuffer, $index + 1, $this->mDynamicBuffer->mBuffer, index, $this->mDynamicBuffer->mSize - 1 - $index);
					}

					--$this->mDynamicBuffer->mSize;
				}
			}
			else
			{
				$this->mRetItem = null;
			}

			return $this->mRetItem;
		}
	}

	public function IndexOf($item)
	{
		$mlock = new MLock($this->mVisitMutex);
		{
			$idx = 0;

			foreach ($this->mDynamicBuffer->mBuffer as $item)
			{
				if (UtilSysLibWrap::isObjectEqual($item, $elem))       // 地址比较
				{
					$this->RemoveAt($idx);
					return $idx;
				}

				++$idx;
			}

			return -1;
		}
	}
}

?>