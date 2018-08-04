<?php

namespace SDK\Lib;

/**
 *@brief ByteBuffer 功能
 *@url http://www.codeweblog.com/php-bytebuffer/
 */
class ByteBuffer implements IDispatchObject
{
    public const msCharMemByteNum = 1;      // char 类型内存占用字节数
    public const msIntMemByteNum = 4;       // int 类型内存占用字节数
    public const msFloatMemByteNum = 4;     // float 类型内存占用字节数
    public const msLongMemByteNum = 4;      // long 类型内存占用字节数
    
	// 读写临时缓存，这个如果是单线程其实可以共享的
	public $mWriteFloatBytes = null;
	public $mWriteDoubleBytes = null;
	
	public $mReadFloatBytes = null;
	public $mReadDoubleBytes = null;

	protected $mDynBuffer;
	protected $mPos;          // 当前可以读取的位置索引
	protected $mEndian;          // 大端小端

	protected $mPadBytes;

	public function __construct($initCapacity = BufferCV::INIT_CAPACITY, $maxCapacity = BufferCV.MAX_CAPACITY, $endian = EEndian::eLITTLE_ENDIAN)
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
	    $this->mPos += num;
	    $this->setlength($this->mPos);
	}

	public function incPosDelta($delta)        // 添加 pos delta 数量
	{
	    $this->mPos += delta;
	}

	public function decPosDelta($delta)     // 减少 pos delta 数量
	{
	    $this->mPos -= delta;
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
	    if ($this->canRead(sizeof(byte)))
		{
		    $tmpByte = $this->mDynBuffer->getBuffer()[$this->mPos];
			$this->advPos(sizeof(byte));
		}

		return $this;
	}

	public function readInt16(&$tmpShort)
	{
	    if ($this->canRead(sizeof(short)))
		{
		    tmpShort = MBitConverter.ToInt16($this->mDynBuffer->getBuffer(), $this->mPos, $this->mEndian);

			$this->advPos(sizeof(short));
		}

		return this;
	}

	public function readUnsignedInt16(&$tmpUshort)
	{
	    if ($this->canRead(sizeof(ushort)))
		{
		    tmpUshort = MBitConverter.ToUInt16($this->mDynBuffer->getBuffer(), $this->mPos, $this->mEndian);

			$this->advPos(sizeof(ushort));
		}

		return this;
	}

	public function readInt32(&$tmpInt)
	{
	    if ($this->canRead(sizeof(int)))
		{
		    tmpInt = MBitConverter.ToInt32($this->mDynBuffer->getBuffer(), $this->mPos, $this->mEndian);

			$this->advPos(sizeof(int));
		}

		return this;
	}

	public function readUnsignedInt32(&$tmpUint)
	{
	    if ($this->canRead(sizeof(uint)))
		{
			// 如果字节序和本地字节序不同，需要转换
		    tmpUint = MBitConverter.ToUInt32($this->mDynBuffer->getBuffer(), $this->mPos, $this->mEndian);

		    $this->advPos(sizeof(uint));
		}

		return this;
	}

	public function readInt64(&$tmpLong)
	{
	    if ($this->canRead(sizeof(long)))
		{
		    tmpLong = MBitConverter.ToInt64($this->mDynBuffer->getBuffer(), $this->mPos, $this->mEndian);

		    $this->advPos(sizeof(long));
		}

		return this;
	}

	public function readUnsignedInt64(&$tmpUlong)
	{
	    if ($this->canRead(sizeof(ulong)))
		{
		    tmpUlong = MBitConverter.ToUInt64($this->mDynBuffer->getBuffer(), $this->mPos, $this->mEndian);

		    $this->advPos(sizeof(ulong));
		}

		return this;
	}

	public function readFloat(&$tmpFloat)
	{
	    if ($this->canRead(sizeof(float)))
		{
		    if ($this->mEndian != SystemEndian::msLocalEndian)
			{
			    Array.Copy($this->mDynBuffer->getBuffer(), $this->mPos, $this->mReadFloatBytes, 0, sizeof(float));
			    Array.Reverse($this->mReadFloatBytes, 0, sizeof(float));
			    tmpFloat = unpack("f", $this->mReadFloatBytes);
			}
			else
			{
			    tmpFloat = System.BitConverter.ToSingle($this->mDynBuffer->getBuffer(), $this->mPos);
			}

			$this->advPos(sizeof(float));
		}

		return this;
	}

	public function readDouble(&$tmpDouble)
	{
	    if ($this->canRead(sizeof(double)))
		{
			if (mEndian != SystemEndian.msLocalEndian)
			{
			    Array.Copy($this->mDynBuffer->getBuffer(), $this->mPos, $this->mReadDoubleBytes, 0, sizeof(double));
			    Array.Reverse($this->mReadDoubleBytes, 0, sizeof(double));
			    tmpDouble = System.BitConverter.ToDouble($this->mReadDoubleBytes, $this->mPos);
			}
			else
			{
			    tmpDouble = System.BitConverter.ToDouble($this->mDynBuffer->getBuffer(), $this->mPos);
			}

			$this->advPos(sizeof(double));
		}

		return this;
	}

	public function readMultiByte(&$tmpStr, uint len, MEncode gkCharSet)
	{
		Encoding charSet = UtilSysLibWrap.convGkEncode2EncodingEncoding(gkCharSet);

		// 如果是 unicode ，需要大小端判断
		if ($this->canRead(len))
		{
		    tmpStr = charSet.GetString($this->mDynBuffer->getBuffer(), $this->mPos, (int)len);
			$this->advPos(len);
		}

		return this;
	}

	// 这个是字节读取，没有大小端的区别
	public function readBytes(ref byte[] tmpBytes, uint len)
	{
	    if ($this->canRead(len))
		{
		    Array.Copy($this->mDynBuffer->getBuffer(), $this->mPos, tmpBytes, 0, (int)len);
			$this->advPos(len);
		}

		return this;
	}

	// 如果要使用 writeInt8 ，直接使用 writeMultiByte 这个函数
	public function writeInt8(char value)
	{
	    if (!$this->canWrite(sizeof(char)))
		{
		    $this->extendDeltaCapicity(sizeof(char));
		}
		
		$this->mDynBuffer->getBuffer()[$this->mPos] = (byte)value;
		$this->advPosAndLen(sizeof(char));
	}

	public function writeUnsignedInt8(byte value)
	{
	    if (!$this->canWrite(sizeof(byte)))
		{
		    $this->extendDeltaCapicity(sizeof(byte));
		}
		
		$this->mDynBuffer->getBuffer()[$this->mPos] = value;
		$this->advPosAndLen(sizeof(byte));
	}

	public function writeInt16 (short value)
	{
	    if (!$this->canWrite(sizeof(short)))
		{
		    $this->extendDeltaCapicity(sizeof(short));
		}
		
		MBitConverter.GetBytes(value, $this->mDynBuffer->getBuffer(), $this->mPos, $this->mEndian);

		$this->advPosAndLen(sizeof(short));
	}

	public function writeUnsignedInt16(ushort value)
	{
	    if (!$this->canWrite(sizeof(ushort)))
		{
		    $this->extendDeltaCapicity(sizeof(ushort));
		}

		MBitConverter.GetBytes(value, $this->mDynBuffer->getBuffer(), $this->mPos, $this->mEndian);

		$this->advPosAndLen(sizeof(ushort));
	}

	public function writeInt32(int value)
	{
	    if (!$this->canWrite(sizeof(int)))
		{
		    $this->extendDeltaCapicity(sizeof(int));
		}

		MBitConverter.GetBytes(value, $this->mDynBuffer->getBuffer(), $this->mPos, $this->mEndian);

		$this->advPosAndLen(sizeof(int));
	}

	public function writeUnsignedInt32 (uint value, bool bchangeLen = true)
	{
	    if (!$this->canWrite(sizeof(uint)))
		{
		    $this->extendDeltaCapicity(sizeof(uint));
		}

		MBitConverter.GetBytes(value, $this->mDynBuffer->getBuffer(), $this->mPos, $this->mEndian);

		if (bchangeLen)
		{
		    $this->advPosAndLen(sizeof(uint));
		}
		else
		{
		    $this->advPos(sizeof(uint));
		}
	}

	public function writeInt64(long value)
	{
	    if (!$this->canWrite(sizeof(long)))
		{
		    $this->extendDeltaCapicity(sizeof(long));
		}

		MBitConverter.GetBytes(value, $this->mDynBuffer->getBuffer(), $this->mPos, $this->mEndian);

		$this->advPosAndLen(sizeof(long));
	}

	public function writeUnsignedInt64(ulong value)
	{
	    if (!$this->canWrite(sizeof(ulong)))
		{
		    $this->extendDeltaCapicity(sizeof(ulong));
		}

		MBitConverter.GetBytes(value, $this->mDynBuffer->getBuffer(), $this->mPos, $this->mEndian);

		$this->advPosAndLen(sizeof(ulong));
	}

	public function writeFloat(float value)
	{
	    if (!$this->canWrite(sizeof(float)))
		{
		    $this->extendDeltaCapicity(sizeof(float));
		}

		$this->mWriteFloatBytes = System.BitConverter.GetBytes(value);
		if (mEndian != SystemEndian.msLocalEndian)
		{
		    Array.Reverse($this->mWriteFloatBytes);
		}
		Array.Copy(mWriteFloatBytes, 0, $this->mDynBuffer->getBuffer(), $this->mPos, sizeof(float));

		$this->advPosAndLen(sizeof(float));
	}

	public function writeDouble(double value)
	{
	    if (!$this->canWrite(sizeof(double)))
		{
		    $this->extendDeltaCapicity(sizeof(double));
		}

		$this->mWriteDoubleBytes = System.BitConverter.GetBytes(value);
		if (mEndian != SystemEndian.msLocalEndian)
		{
		    Array.Reverse($this->mWriteDoubleBytes);
		}
		Array.Copy($this->mWriteDoubleBytes, 0, $this->mDynBuffer->getBuffer(), $this->mPos, sizeof(double));

		$this->advPosAndLen(sizeof(double));
	}

	// 写入字节， bchangeLen 是否改变长度
	public function writeBytes(byte[] value, uint start, uint len, bool bchangeLen = true)
	{
		if (len > 0)            // 如果有长度才写入
		{
		    if (!$this->canWrite(len))
			{
			    $this->extendDeltaCapicity(len);
			}
			Array.Copy(value, start, $this->mDynBuffer->getBuffer(), $this->mPos, len);
			
			if (bchangeLen)
			{
			    $this->advPosAndLen(len);
			}
			else
			{
			    $this->advPos(len);
			}
		}
	}

	// 写入字符串
	//public void writeMultiByte(string value, Encoding charSet, int len)
	public function writeMultiByte(string value, MEncode gkCharSet, int len)
	{
		Encoding charSet = UtilSysLibWrap.convGkEncode2EncodingEncoding(gkCharSet);
		int num = 0;

		if (null != value)
		{
			//char[] charPtr = value.ToCharArray();
			num = charSet.GetByteCount(value);

			if (0 == len)
			{
				len = num;
			}

			if (!canWrite((uint)len))
			{
				extendDeltaCapicity((uint)len);
			}

			if (num < len)
			{
			    Array.Copy(charSet.GetBytes(value), 0, $this->mDynBuffer->getBuffer(), $this->mPos, num);
				// 后面补齐 0 
				Array.Clear($this->mDynBuffer->getBuffer(), $this->mPos + num, len - num);
			}
			else
			{
			    Array.Copy(charSet.GetBytes(value), 0, $this->mDynBuffer->getBuffer(), $this->mPos, len);
			}
			
			$this->advPosAndLen(len);
		}
		else
		{
		    if (!$this->canWrite(len))
			{
			    $this->extendDeltaCapicity(len);
			}

			$this->advPosAndLen(len);
		}
	}

	// 替换已经有的一段数据
	protected function replace(byte[] srcBytes, uint srcStartPos = 0, uint srclen_ = 0, uint destStartPos = 0, uint destlen_ = 0)
	{
		uint lastLeft = length - destStartPos - destlen_;        // 最后一段的长度
		$this->setLength(destStartPos + srclen_ + lastLeft);      // 设置大小，保证足够大小空间

		$this->setPos(destStartPos + srclen_);
		
		if (lastLeft > 0)
		{
		    $this->writeBytes($this->mDynBuffer->getBuffer(), destStartPos + destlen_, lastLeft, false);          // 这个地方自己区域覆盖自己区域，可以保证自己不覆盖自己区域
		}

		$this->setPos(destStartPos);
		$this->writeBytes(srcBytes, srcStartPos, srclen_, false);
	}

	public function insertUnsignedInt32(uint value)
	{
	    $this->setLength($this->getLength() + sizeof(int));       // 扩大长度
	    $this->writeUnsignedInt32(value);     // 写入
	}

	public function readUnsignedLongByOffset(ref ulong tmpUlong, uint offset)
	{
	    $this->setPos(offset);
	    $this->readUnsignedInt64(ref tmpUlong);
		return this;
	}

	// 写入 EOF 结束符
	public function end()
	{
		$this->mDynBuffer->getBuffer()[$this->length] = 0;
	}

	public function readBoolean(&$tmpBool)
	{
	    if ($this->canRead(sizeof(bool)))
		{
		    tmpBool = System.BitConverter.ToBoolean($this->mDynBuffer->getBuffer(), $this->mPos);
			$this->advPos(sizeof(bool));
		}

		return this;
	}
}

?>