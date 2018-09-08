<?php

namespace SDK\Lib;

/**
 * @brief 动态增长的缓冲区，不是环形的，从 0 开始增长的
 */
class DynBuffer
{
	public $mCapacity;         // 分配的内存空间大小，单位大小是字节
	public $mMaxCapacity;      // 最大允许分配的存储空间大小 
	public $mSize;              // 存储在当前缓冲区中的数量
	public $mBuffer;            // 当前环形缓冲区

	public function __construct($initCapacity = 1 * 1024/*DataCV.INIT_CAPACITY*/, $maxCapacity = 8 * 1024 * 1024/*DataCV.MAX_CAPACITY*/)      // mono 模板类中使用常亮报错， vs 可以
	{
		$this->mMaxCapacity = maxCapacity;
		$this->mCapacity = initCapacity;
		$this->mSize = 0;
		$this->mBuffer = array();
		UtilList::setCapacity($this->mBuffer, $this->mCapacity);
	}

	public function getBuffer()
	{
		return $this->mBuffer;
	}
	
	public function setBuffer($value)
	{
	    $this->mBuffer = $value;
	    $this->mCapacity = UtilList::count($this->mBuffer);
	}

	public function getMaxCapacity()
	{
	    return $this->mMaxCapacity;
	}
	
	public function setMaxCapacity($value)
	{
	    $this->mMaxCapacity = $value;
	}

	public function getCapacity()
	{
		return $this->mCapacity;
	}
	
	public function setCapacity($value)
	{
	    if ($value == $this->mCapacity)
		{
			return;
		}
		if ($value < $this->getSize())       // 不能分配比当前已经占有的空间还小的空间
		{
			return;
		}
		
		$tmpbuff = array();   // 分配新的空间
		UtilList::setCapacity($tmpbuff, $value);
		UtilList::Copy($this->mBuffer, 0, $tmpbuff, 0, $this->mSize);  // 这个地方是 mSize 还是应该是 mCapacity，如果是 CircleBuffer 好像应该是 mCapacity，如果是 ByteBuffer ，好像应该是 mCapacity。但是 DynBuffer 只有 ByteBuffer 才会使用这个函数，因此使用 mSize 就行了，但是如果使用 mCapacity 也没有问题
		
		$this->mBuffer = $tmpbuff;
		$this->mCapacity = $value;
	}

	public function getSize()
	{
		return $this->mSize;
	}
	
	public function setSize($value)
	{
		if ($value > $this->getCapacity())
		{
		    $this->extendDeltaCapicity($value - $this->getSize());
		}
		
		$this->mSize = $value;
	}

	public function extendDeltaCapicity($delta)
	{
	    $this->setCapacity(DynBufResizePolicy.getCloseSize($this->getSize() + $delta, $this->getCapacity(), $this->getMaxCapacity()));
	}
}

?>