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
		if(mDynBuffer.size + delta > mDynBuffer.capacity)
		{
			return false;
		}

		return true;
	}

	// 读取检查
	protected function canRead($delta)
	{
		if (mPos + delta > mDynBuffer.size)
		{
			return false;
		}

		return true;
	}

	protected function extendDeltaCapicity($delta)
	{
		mDynBuffer.extendDeltaCapicity(delta);
	}

	protected function advPos($num)
	{
		$this->mPos += $num;
	}

	protected function advPosAndLen($num)
	{
		mPos += num;
		length = mPos;
	}

	public function incPosDelta($delta)        // 添加 pos delta 数量
	{
		mPos += delta;
	}

	public function decPosDelta($delta)     // 减少 pos delta 数量
	{
		mPos -= delta;
	}

	public function readInt8(&$tmpByte)
	{
		if (canRead(sizeof(char)))
		{
		    $tmpByte = $this->mDynBuffer->getBuffer()[$this->mPos];
			advPos(sizeof(char));
		}

		return $this;
	}

	public function readUnsignedInt8(&$tmpByte)
	{
		if (canRead(sizeof(byte)))
		{
			$tmpByte = $this->mDynBuffer->getBuffer()[(int)mPos];
			advPos(sizeof(byte));
		}

		return $this;
	}

	public function readInt16(&$tmpShort)
	{
		if (canRead(sizeof(short)))
		{
			tmpShort = MBitConverter.ToInt16($this->mDynBuffer->getBuffer(), (int)mPos, mEndian);

			advPos(sizeof(short));
		}

		return this;
	}

	public function readUnsignedInt16(&$tmpUshort)
	{
		if (canRead(sizeof(ushort)))
		{
			tmpUshort = MBitConverter.ToUInt16($this->mDynBuffer->getBuffer(), (int)mPos, mEndian);

			advPos(sizeof(ushort));
		}

		return this;
	}

	public function readInt32(&$tmpInt)
	{
		if (canRead(sizeof(int)))
		{
			tmpInt = MBitConverter.ToInt32($this->mDynBuffer->getBuffer(), (int)mPos, mEndian);

			advPos(sizeof(int));
		}

		return this;
	}

	public function readUnsignedInt32(&$tmpUint)
	{
		if (canRead(sizeof(uint)))
		{
			// 如果字节序和本地字节序不同，需要转换
			tmpUint = MBitConverter.ToUInt32($this->mDynBuffer->getBuffer(), (int)mPos, mEndian);

			advPos(sizeof(uint));
		}

		return this;
	}

	public function readInt64(&$tmpLong)
	{
		if (canRead(sizeof(long)))
		{
			tmpLong = MBitConverter.ToInt64($this->mDynBuffer->getBuffer(), (int)mPos, mEndian);

			advPos(sizeof(long));
		}

		return this;
	}

	public function readUnsignedInt64(&$tmpUlong)
	{
		if (canRead(sizeof(ulong)))
		{
			tmpUlong = MBitConverter.ToUInt64($this->mDynBuffer->getBuffer(), (int)mPos, mEndian);

			advPos(sizeof(ulong));
		}

		return this;
	}

	public function readFloat(&$tmpFloat)
	{
		if (canRead(sizeof(float)))
		{
			if (mEndian != SystemEndian::msLocalEndian)
			{
				Array.Copy($this->mDynBuffer->getBuffer(), (int)mPos, mReadFloatBytes, 0, sizeof(float));
				Array.Reverse(mReadFloatBytes, 0, sizeof(float));
				tmpFloat = unpack("f", mReadFloatBytes);
			}
			else
			{
				tmpFloat = System.BitConverter.ToSingle($this->mDynBuffer->getBuffer(), (int)mPos);
			}

			advPos(sizeof(float));
		}

		return this;
	}

	public function readDouble(&$tmpDouble)
	{
		if (canRead(sizeof(double)))
		{
			if (mEndian != SystemEndian.msLocalEndian)
			{
				Array.Copy($this->mDynBuffer->getBuffer(), (int)mPos, mReadDoubleBytes, 0, sizeof(double));
				Array.Reverse(mReadDoubleBytes, 0, sizeof(double));
				tmpDouble = System.BitConverter.ToDouble(mReadDoubleBytes, (int)mPos);
			}
			else
			{
				tmpDouble = System.BitConverter.ToDouble($this->mDynBuffer->getBuffer(), (int)mPos);
			}

			advPos(sizeof(double));
		}

		return this;
	}

	public function readMultiByte(&$tmpStr, uint len, MEncode gkCharSet)
	{
		Encoding charSet = UtilSysLibWrap.convGkEncode2EncodingEncoding(gkCharSet);

		// 如果是 unicode ，需要大小端判断
		if (canRead(len))
		{
			tmpStr = charSet.GetString($this->mDynBuffer->getBuffer(), (int)mPos, (int)len);
			advPos(len);
		}

		return this;
	}

	// 这个是字节读取，没有大小端的区别
	public function readBytes(ref byte[] tmpBytes, uint len)
	{
		if (canRead(len))
		{
			Array.Copy($this->mDynBuffer->getBuffer(), (int)mPos, tmpBytes, 0, (int)len);
			advPos(len);
		}

		return this;
	}

	// 如果要使用 writeInt8 ，直接使用 writeMultiByte 这个函数
	public function writeInt8(char value)
	{
		if (!canWrite(sizeof(char)))
		{
			extendDeltaCapicity(sizeof(char));
		}
		$this->mDynBuffer->getBuffer()[mPos] = (byte)value;
		advPosAndLen(sizeof(char));
	}

	public function writeUnsignedInt8(byte value)
	{
		if (!canWrite(sizeof(byte)))
		{
			extendDeltaCapicity(sizeof(byte));
		}
		$this->mDynBuffer->getBuffer()[mPos] = value;
		advPosAndLen(sizeof(byte));
	}

	public function writeInt16 (short value)
	{
		if (!canWrite(sizeof(short)))
		{
			extendDeltaCapicity(sizeof(short));
		}
		
		MBitConverter.GetBytes(value, $this->mDynBuffer->getBuffer(), (int)mPos, mEndian);

		advPosAndLen(sizeof(short));
	}

	public function writeUnsignedInt16(ushort value)
	{
		if (!canWrite(sizeof(ushort)))
		{
			extendDeltaCapicity(sizeof(ushort));
		}

		MBitConverter.GetBytes(value, $this->mDynBuffer->getBuffer(), (int)mPos, mEndian);

		advPosAndLen(sizeof(ushort));
	}

	public function writeInt32(int value)
	{
		if (!canWrite(sizeof(int)))
		{
			extendDeltaCapicity(sizeof(int));
		}

		MBitConverter.GetBytes(value, $this->mDynBuffer->getBuffer(), (int)mPos, mEndian);

		advPosAndLen(sizeof(int));
	}

	public function writeUnsignedInt32 (uint value, bool bchangeLen = true)
	{
		if (!canWrite(sizeof(uint)))
		{
			extendDeltaCapicity(sizeof(uint));
		}

		MBitConverter.GetBytes(value, $this->mDynBuffer->getBuffer(), (int)mPos, mEndian);

		if (bchangeLen)
		{
			advPosAndLen(sizeof(uint));
		}
		else
		{
			advPos(sizeof(uint));
		}
	}

	public function writeInt64(long value)
	{
		if (!canWrite(sizeof(long)))
		{
			extendDeltaCapicity(sizeof(long));
		}

		MBitConverter.GetBytes(value, $this->mDynBuffer->getBuffer(), (int)mPos, mEndian);

		advPosAndLen(sizeof(long));
	}

	public function writeUnsignedInt64(ulong value)
	{
		if (!canWrite(sizeof(ulong)))
		{
			extendDeltaCapicity(sizeof(ulong));
		}

		MBitConverter.GetBytes(value, $this->mDynBuffer->getBuffer(), (int)mPos, mEndian);

		advPosAndLen(sizeof(ulong));
	}

	public function writeFloat(float value)
	{
		if (!canWrite(sizeof(float)))
		{
			extendDeltaCapicity(sizeof(float));
		}

		mWriteFloatBytes = System.BitConverter.GetBytes(value);
		if (mEndian != SystemEndian.msLocalEndian)
		{
			Array.Reverse(mWriteFloatBytes);
		}
		Array.Copy(mWriteFloatBytes, 0, $this->mDynBuffer->getBuffer(), mPos, sizeof(float));

		advPosAndLen(sizeof(float));
	}

	public function writeDouble(double value)
	{
		if (!canWrite(sizeof(double)))
		{
			extendDeltaCapicity(sizeof(double));
		}

		mWriteDoubleBytes = System.BitConverter.GetBytes(value);
		if (mEndian != SystemEndian.msLocalEndian)
		{
			Array.Reverse(mWriteDoubleBytes);
		}
		Array.Copy(mWriteDoubleBytes, 0, $this->mDynBuffer->getBuffer(), mPos, sizeof(double));

		advPosAndLen(sizeof(double));
	}

	// 写入字节， bchangeLen 是否改变长度
	public function writeBytes(byte[] value, uint start, uint len, bool bchangeLen = true)
	{
		if (len > 0)            // 如果有长度才写入
		{
			if (!canWrite(len))
			{
				extendDeltaCapicity(len);
			}
			Array.Copy(value, start, $this->mDynBuffer->getBuffer(), mPos, len);
			if (bchangeLen)
			{
				advPosAndLen(len);
			}
			else
			{
				advPos(len);
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
				Array.Copy(charSet.GetBytes(value), 0, $this->mDynBuffer->getBuffer(), mPos, num);
				// 后面补齐 0 
				Array.Clear($this->mDynBuffer->getBuffer(), (int)(mPos + num), len - num);
			}
			else
			{
				Array.Copy(charSet.GetBytes(value), 0, $this->mDynBuffer->getBuffer(), mPos, len);
			}
			advPosAndLen((uint)len);
		}
		else
		{
			if (!canWrite((uint)len))
			{
				extendDeltaCapicity((uint)len);
			}

			advPosAndLen((uint)len);
		}
	}

	// 替换已经有的一段数据
	protected function replace(byte[] srcBytes, uint srcStartPos = 0, uint srclen_ = 0, uint destStartPos = 0, uint destlen_ = 0)
	{
		uint lastLeft = length - destStartPos - destlen_;        // 最后一段的长度
		length = destStartPos + srclen_ + lastLeft;      // 设置大小，保证足够大小空间

		position = destStartPos + srclen_;
		if (lastLeft > 0)
		{
			writeBytes($this->mDynBuffer->getBuffer(), destStartPos + destlen_, lastLeft, false);          // 这个地方自己区域覆盖自己区域，可以保证自己不覆盖自己区域
		}

		position = destStartPos;
		writeBytes(srcBytes, srcStartPos, srclen_, false);
	}

	public function insertUnsignedInt32(uint value)
	{
		length += sizeof(int);       // 扩大长度
		writeUnsignedInt32(value);     // 写入
	}

	public function readUnsignedLongByOffset(ref ulong tmpUlong, uint offset)
	{
		position = offset;
		readUnsignedInt64(ref tmpUlong);
		return this;
	}

	// 写入 EOF 结束符
	public function end()
	{
		$this->mDynBuffer->getBuffer()[$this->length] = 0;
	}

	public function readBoolean(&$tmpBool)
	{
		if (canRead(sizeof(bool)))
		{
			tmpBool = System.BitConverter.ToBoolean($this->mDynBuffer->getBuffer(), (int)mPos);
			advPos(sizeof(bool));
		}

		return this;
	}
}

?>