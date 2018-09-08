<?php

namespace SDK\Lib;

/**
 *@brief ByteBuffer 功能
 *@url http://www.codeweblog.com/php-bytebuffer/
 */
class ByteBuffer implements IDispatchObject
{
    public const msCharMemByteNum = 1;      // char 类型内存占用字节数
    public const msShortIntMemByteNum = 4;  // int 类型内存占用字节数
    public const msIntMemByteNum = 4;       // int 类型内存占用字节数
    public const msFloatMemByteNum = 4;     // float 类型内存占用字节数
    public const msDoubleMemByteNum = 4;    // double 类型内存占用字节数
    public const msLongIntMemByteNum = 4;   // long 类型内存占用字节数
    
	// 读写临时缓存，这个如果是单线程其实可以共享的
	public $mWriteFloatBytes = null;
	public $mWriteDoubleBytes = null;
	
	public $mReadFloatBytes = null;
	public $mReadDoubleBytes = null;

	protected $mDynBuffer;
	protected $mPos;          // 当前可以读取的位置索引
	protected $mEndian;          // 大端小端

	protected $mPadBytes;

	public function __construct($initCapacity = BufferCV::INIT_CAPACITY, $maxCapacity = BufferCV.MAX_CAPACITY, $endian = MEndian::eLITTLE_ENDIAN)
	{
		$this->mEndian = $endian;        // 缓冲区默认是小端的数据，因为服务器是 linux 的
		$this->mDynBuffer = new DynBuffer($initCapacity, $maxCapacity);
		
		$this->mReadFloatBytes = "0000";
		$this->mReadDoubleBytes = "00000000";
    }

	public function getDynBuffer()
	{
		return $this->mDynBuffer;
	}

	public function getBytesAvailable()
	{
		return ($this->mDynBuffer.getSize() - $this->mPos);
	}

	public function getEndian()
	{
		return $this->mEndian;
	}
	
	public function setEndian($value)
	{
	    $this->mEndian = $value;
	}

	public function setEndian($end)
	{
		$this->mEndian = $end;
	}

	public function getLength()
	{
		return $this->mDynBuffer->getSize();
	}
	
	public function setLength($value)
	{
	    $this->mDynBuffer.setSize($value);
	}

	public function setPos($pos)
	{
	    $this->mPos = pos;
	}

	public function getPos()
	{
	    return $this->mPos;
	}

	public function clear()
	{
	    $this->mPos = 0;
	    $this->mDynBuffer->setSize(0);
	}

	// 检查是否有足够的大小可以扩展
	protected function canWrite($delta)
	{
	    if($this->mDynBuffer->getSize() + delta > $this->mDynBuffer->getCapacity())
		{
			return false;
		}

		return true;
	}

	// 读取检查
	protected function canRead($delta)
	{
	    if ($this->mPos + delta > $this->mDynBuffer->getSize())
		{
			return false;
		}

		return true;
	}

	protected function extendDeltaCapicity($delta)
	{
	    $this->mDynBuffer.extendDeltaCapicity(delta);
	}

	protected function advPos($num)
	{
		$this->mPos += $num;
	}

	protected function advPosAndLen($num)
	{
	    $this->mPos += $num;
	    $this->setlength($this->mPos);
	}

	public function incPosDelta($delta)        // 添加 pos delta 数量
	{
	    $this->mPos += $delta;
	}

	public function decPosDelta($delta)     // 减少 pos delta 数量
	{
	    $this->mPos -= $delta;
	}

	public function readInt8(&$tmpByte)
	{
	    if ($this->canRead(sizeof(char)))
		{
		    $tmpByte = $this->mDynBuffer->getBuffer()[$this->mPos];
		    $this->advPos(sizeof(char));
		}

		return $this;
	}

	public function readUnsignedInt8(&$tmpByte)
	{
	    if ($this->canRead(ByteBuffer::msCharMemByteNum))
		{
		    $tmpByte = $this->mDynBuffer->getBuffer()[$this->mPos];
			$this->advPos(sizeof(byte));
		}

		return $this;
	}

	public function readInt16(&$tmpShort)
	{
	    if ($this->canRead(ByteBuffer::msShortIntMemByteNum))
		{
		    $tmpShort = MBitConverter::ToInt16($this->mDynBuffer->getBuffer(), $this->mPos, $this->mEndian);

		    $this->advPos(ByteBuffer::msShortIntMemByteNum);
		}

		return this;
	}

	public function readUnsignedInt16(&$tmpUshort)
	{
	    if ($this->canRead(ByteBuffer::msShortIntMemByteNum))
		{
		    $tmpUshort = MBitConverter::ToUInt16($this->mDynBuffer->getBuffer(), $this->mPos, $this->mEndian);

		    $this->advPos(ByteBuffer::msShortIntMemByteNum);
		}

		return this;
	}

	public function readInt32(&$tmpInt)
	{
	    if ($this->canRead(ByteBuffer::msIntMemByteNum))
		{
		    $tmpInt = MBitConverter::ToInt32($this->mDynBuffer->getBuffer(), $this->mPos, $this->mEndian);

		    $this->advPos(ByteBuffer::msIntMemByteNum);
		}

		return this;
	}

	public function readUnsignedInt32(&$tmpUint)
	{
	    if ($this->canRead(ByteBuffer::msIntMemByteNum))
		{
			// 如果字节序和本地字节序不同，需要转换
		    $tmpUint = MBitConverter::ToUInt32($this->mDynBuffer->getBuffer(), $this->mPos, $this->mEndian);

		    $this->advPos(ByteBuffer::msIntMemByteNum);
		}

		return this;
	}

	public function readInt64(&$tmpLong)
	{
	    if ($this->canRead(ByteBuffer::msLongIntMemByteNum))
		{
		    $tmpLong = MBitConverter::ToInt64($this->mDynBuffer->getBuffer(), $this->mPos, $this->mEndian);

		    $this->advPos(ByteBuffer::msLongIntMemByteNum);
		}

		return this;
	}

	public function readUnsignedInt64(&$tmpUlong)
	{
	    if ($this->canRead(ByteBuffer::msLongIntMemByteNum))
		{
		    $tmpUlong = MBitConverter::ToUInt64($this->mDynBuffer->getBuffer(), $this->mPos, $this->mEndian);

		    $this->advPos(ByteBuffer::msLongIntMemByteNum);
		}

		return this;
	}

	public function readFloat(&$tmpFloat)
	{
	    if ($this->canRead(ByteBuffer::msFloatMemByteNum))
		{
		    if ($this->mEndian != SystemEndian::msLocalEndian)
			{
			    UtilList::Copy($this->mDynBuffer->getBuffer(), $this->mPos, $this->mReadFloatBytes, 0, ByteBuffer::msFloatMemByteNum);
			    UtilList::Reverse($this->mReadFloatBytes, 0, ByteBuffer::msFloatMemByteNum);
			    $tmpFloat = unpack("f", $this->mReadFloatBytes);
			}
			else
			{
			    UtilList::Copy($this->mDynBuffer->getBuffer(), $this->mPos, $this->mReadFloatBytes, 0, ByteBuffer::msFloatMemByteNum);
			    $tmpFloat = unpack("f", $this->mReadFloatBytes);
			}

			$this->advPos(ByteBuffer::msFloatMemByteNum);
		}

		return this;
	}

	public function readDouble(&$tmpDouble)
	{
	    if ($this->canRead(ByteBuffer::msDoubleMemByteNum))
		{
			if (mEndian != SystemEndian.msLocalEndian)
			{
			    UtilList::Copy($this->mDynBuffer->getBuffer(), $this->mPos, $this->mReadDoubleBytes, 0, ByteBuffer::msDoubleMemByteNum);
			    UtilList::Reverse($this->mReadDoubleBytes, 0, ByteBuffer::msDoubleMemByteNum);
			    $tmpDouble = unpack("f", $this->$this->mReadDoubleBytes);
			}
			else
			{
			    UtilList::Copy($this->mDynBuffer->getBuffer(), $this->mPos, $this->mReadDoubleBytes, 0, ByteBuffer::msDoubleMemByteNum);
			    $tmpDouble = unpack("f", $this->$this->mReadDoubleBytes);
			}

			$this->advPos(ByteBuffer::msDoubleMemByteNum);
		}

		return this;
	}

	public function readMultiByte(&$tmpStr, $len, $encode)
	{
	    $charSetStr = UtilSysLibWrap.convEncode2NativeEncodeStr($encode);
	    $tmpBytes = "";

		// 如果是 unicode ，需要大小端判断
		if ($this->canRead($len))
		{
		    UtilList::CopyToStr($this->mDynBuffer->getBuffer(), $this->mPos, $tmpBytes, 0, (int)len);
		    $tmpStr = iconv(MEncodeStr::eUTF8Str, $charSetStr, $tmpBytes);
			$this->advPos($len);
		}

		return this;
	}

	// 这个是字节读取，没有大小端的区别
	public function readBytes(&$tmpBytes, $len)
	{
	    if ($this->canRead($len))
		{
		    UtilList::CopyToStr($this->mDynBuffer->getBuffer(), $this->mPos, $tmpBytes, 0, (int)len);
		    $this->advPos($len);
		}

		return this;
	}

	// 如果要使用 writeInt8 ，直接使用 writeMultiByte 这个函数
	public function writeInt8($value)
	{
	    if (!$this->canWrite(ByteBuffer::msCharMemByteNum))
		{
		    $this->extendDeltaCapicity(ByteBuffer::msCharMemByteNum);
		}
		
		$this->mDynBuffer->getBuffer()[$this->mPos] = (int)$value;
		$this->advPosAndLen(ByteBuffer::msCharMemByteNum);
	}

	public function writeUnsignedInt8($value)
	{
	    if (!$this->canWrite(ByteBuffer::msCharMemByteNum))
		{
		    $this->extendDeltaCapicity(ByteBuffer::msCharMemByteNum);
		}
		
		$this->mDynBuffer->getBuffer()[$this->mPos] = $value;
		$this->advPosAndLen(sizeof(byte));
	}

	public function writeInt16 ($value)
	{
	    if (!$this->canWrite(ByteBuffer::msShortIntMemByteNum))
		{
		    $this->extendDeltaCapicity(ByteBuffer::msShortIntMemByteNum);
		}
		
		MBitConverter.GetBytes($value, $this->mDynBuffer->getBuffer(), $this->mPos, $this->mEndian);

		$this->advPosAndLen(ByteBuffer::msShortIntMemByteNum);
	}

	public function writeUnsignedInt16($value)
	{
	    if (!$this->canWrite(ByteBuffer::msShortIntMemByteNum))
		{
		    $this->extendDeltaCapicity(ByteBuffer::msShortIntMemByteNum);
		}

		MBitConverter::GetBytes($value, $this->mDynBuffer->getBuffer(), $this->mPos, $this->mEndian);

		$this->advPosAndLen(ByteBuffer::msShortIntMemByteNum);
	}

	public function writeInt32($value)
	{
	    if (!$this->canWrite(ByteBuffer::msIntMemByteNum))
		{
		    $this->extendDeltaCapicity(ByteBuffer::msIntMemByteNum);
		}

		MBitConverter::GetBytes(value, $this->mDynBuffer->getBuffer(), $this->mPos, $this->mEndian);

		$this->advPosAndLen(ByteBuffer::msIntMemByteNum);
	}

	public function writeUnsignedInt32 ($value, $bchangeLen = true)
	{
	    if (!$this->canWrite(ByteBuffer::msIntMemByteNum))
		{
		    $this->extendDeltaCapicity(ByteBuffer::msIntMemByteNum);
		}

		MBitConverter::GetBytes($value, $this->mDynBuffer->getBuffer(), $this->mPos, $this->mEndian);

		if (bchangeLen)
		{
		    $this->advPosAndLen(ByteBuffer::msIntMemByteNum);
		}
		else
		{
		    $this->advPos(ByteBuffer::msIntMemByteNum);
		}
	}

	public function writeInt64($value)
	{
	    if (!$this->canWrite(ByteBuffer::msLongIntMemByteNum))
		{
		    $this->extendDeltaCapicity(ByteBuffer::msLongIntMemByteNum);
		}

		MBitConverter::GetBytes($value, $this->mDynBuffer->getBuffer(), $this->mPos, $this->mEndian);

		$this->advPosAndLen(ByteBuffer::msLongIntMemByteNum);
	}

	public function writeUnsignedInt64($value)
	{
	    if (!$this->canWrite(ByteBuffer::msLongIntMemByteNum))
		{
		    $this->extendDeltaCapicity(ByteBuffer::msLongIntMemByteNum);
		}

		MBitConverter::GetBytes($value, $this->mDynBuffer->getBuffer(), $this->mPos, $this->mEndian);

		$this->advPosAndLen(ByteBuffer::msLongIntMemByteNum);
	}

	public function writeFloat($value)
	{
	    if (!$this->canWrite(ByteBuffer::msFloatMemByteNum))
		{
		    $this->extendDeltaCapicity(ByteBuffer::msFloatMemByteNum);
		}

		$this->mWriteFloatBytes = pack("f", value);
		
		if ($this->mEndian != SystemEndian::msLocalEndian)
		{
		    UtilList::Reverse($this->mWriteFloatBytes);
		}
		UtilList.Copy($this->mWriteFloatBytes, 0, $this->mDynBuffer->getBuffer(), $this->mPos, ByteBuffer::msFloatMemByteNum);

		$this->advPosAndLen(ByteBuffer::msFloatMemByteNum);
	}

	public function writeDouble($value)
	{
	    if (!$this->canWrite(ByteBuffer::msDoubleMemByteNum))
		{
		    $this->extendDeltaCapicity(ByteBuffer::msDoubleMemByteNum);
		}

		$this->mWriteDoubleBytes = System.BitConverter.GetBytes(value);
		
		if ($this->mEndian != SystemEndian::msLocalEndian)
		{
		    UtilList::Reverse($this->mWriteDoubleBytes);
		}
		
		UtilList::Copy($this->mWriteDoubleBytes, 0, $this->mDynBuffer->getBuffer(), $this->mPos, ByteBuffer::msDoubleMemByteNum);

		$this->advPosAndLen(ByteBuffer::msDoubleMemByteNum);
	}

	// 写入字节， bchangeLen 是否改变长度
	public function writeBytes($value, $start, $len, $bchangeLen = true)
	{
		if ($len > 0)            // 如果有长度才写入
		{
		    if (!$this->canWrite($len))
			{
			    $this->extendDeltaCapicity($len);
			}
			UtilList::Copy($value, $start, $this->mDynBuffer->getBuffer(), $this->mPos, $len);
			
			if (bchangeLen)
			{
			    $this->advPosAndLen($len);
			}
			else
			{
			    $this->advPos($len);
			}
		}
	}

	// 写入字符串
	//public function writeMultiByte($value, $encode, $len)
	//{
	//    $charSetStr = UtilSysLibWrap.convEncode2NativeEncodeStr($encode);
	//	$num = 0;

	//	if (null != $value)
	//	{
	//		num = $charSet.GetByteCount($value);

	//		if (0 == $len)
	//		{
	//			$len = $num;
	//		}

	//		if (!$this->canWrite((uint)$len))
	//		{
	//			$this->extendDeltaCapicity((uint)$len);
	//		}

	//		if ($num < $len)
	//		{
	//		    UtilList.Copy(charSet.GetBytes(value), 0, $this->mDynBuffer->getBuffer(), $this->mPos, num);
				// 后面补齐 0 
	//			Array.clear($this->mDynBuffer->getBuffer(), $this->mPos + num, len - num);
	//		}
	//		else
	//		{
	//		    Array.Copy(charSet.GetBytes(value), 0, $this->mDynBuffer->getBuffer(), $this->mPos, len);
	//		}
			
	//		$this->advPosAndLen($len);
	//	}
	//	else
	//	{
	//	    if (!$this->canWrite($len))
	//		{
	//		    $this->extendDeltaCapicity($len);
	//		}

	//		$this->advPosAndLen($len);
	//	}
	//}

	// 替换已经有的一段数据
	protected function replace($srcBytes, $srcStartPos = 0, $srclen_ = 0, $destStartPos = 0, $destlen_ = 0)
	{
		$lastLeft = length - destStartPos - destlen_;        // 最后一段的长度
		$this->setLength($destStartPos + $srclen_ + $lastLeft);      // 设置大小，保证足够大小空间

		$this->setPos($destStartPos + $srclen_);
		
		if ($lastLeft > 0)
		{
		    $this->writeBytes($this->mDynBuffer->getBuffer(), $destStartPos + $destlen_, $lastLeft, false);          // 这个地方自己区域覆盖自己区域，可以保证自己不覆盖自己区域
		}

		$this->setPos($destStartPos);
		$this->writeBytes($srcBytes, $srcStartPos, $srclen_, false);
	}

	public function insertUnsignedInt32($value)
	{
	    $this->setLength($this->getLength() + ByteBuffer::msIntMemByteNum);       // 扩大长度
	    $this->writeUnsignedInt32($value);     // 写入
	}

	public function readUnsignedLongByOffset(&$tmpUlong, $offset)
	{
	    $this->setPos(offset);
	    $this->readUnsignedInt64($tmpUlong);
		return this;
	}

	// 写入 EOF 结束符
	public function end()
	{
		$this->mDynBuffer->getBuffer()[$this->length] = 0;
	}

	public function readBoolean(&$tmpBool)
	{
	    if ($this->canRead(ByteBuffer::msCharMemByteNum))
		{
		    $tmpBool = System.BitConverter.ToBoolean($this->mDynBuffer->getBuffer(), $this->mPos);
		    $this->advPos(ByteBuffer::msCharMemByteNum);
		}

		return this;
	}
}

?>