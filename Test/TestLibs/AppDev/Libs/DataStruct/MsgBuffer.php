<?php

namespace SDK\Lib;

class MsgBuffer
{
	protected $mCircularBuffer;  // 环形缓冲区

	protected $mHeaderBA;            // 主要是用来分析头的大小
	protected $mMsgBodyBA;           // 返回的字节数组

	public function __construct($initCapacity = BufferCV.INIT_CAPACITY, $maxCapacity = BufferCV.MAX_CAPACITY)
	{
		$this->mCircularBuffer = new CircularBuffer(initCapacity, maxCapacity);
		$this->mHeaderBA = new ByteBuffer();
		$this->mMsgBodyBA = new ByteBuffer();
	}

	public function getHeaderBA()
	{
		return $this->mHeaderBA;
	}

	public function getMsgBodyBA()
	{
		return $this->mMsgBodyBA;
	}

	public function getCircularBuffer()
	{
		return $this->mCircularBuffer;
	}

	// 设置网络字节序
	public function setEndian($endian)
	{
		$this->mHeaderBA.setEndian($endian);
		$this->mMsgBodyBA.setEndian($endian);
	}

	/**
	 * @brief 检查 CB 中是否有一个完整的消息
	 */
	protected function checkHasMsg()
	{
		$this->mCircularBuffer.frontBA($this->mHeaderBA, MsgCV::HEADER_SIZE);  // 将数据读取到 mHeaderBA
		$msglen = 0;
		$this->mHeaderBA.readUnsignedInt32($this->msglen);
		
		if (MacroDef::MSG_COMPRESS)
		{
			if (($msglen & MsgCV::PACKET_ZIP) > 0)         // 如果有压缩标志
			{
				$msglen &= (~MsgCV::PACKET_ZIP);         // 去掉压缩标志位
			}
		}
		if ($msglen <= $this->mCircularBuffer.size - MsgCV::HEADER_SIZE)
		{
			return true;
		}
		else
		{
			return false;
		}
	}

	/**
	 * @brief 获取前面的第一个完整的消息数据块
	 */
	public function popFront()
	{
		$ret = false;
		
		if ($this->mCircularBuffer.size > MsgCV::HEADER_SIZE)         // 至少要是 DataCV.HEADER_SIZE 大小加 1 ，如果正好是 DataCV.HEADER_SIZE ，那只能说是只有大小字段，没有内容
		{
			$this->mCircularBuffer.frontBA($this->mHeaderBA, MsgCV::HEADER_SIZE);  // 如果不够整个消息的长度，还是不能去掉消息头的
			$msglen = 0;
			$this->mHeaderBA.readUnsignedInt32($this->msglen);
			
			if (MacroDef.MSG_COMPRESS)
			{
				if (($msglen & MsgCV.PACKET_ZIP) > 0)         // 如果有压缩标志
				{
					$msglen &= (~MsgCV.PACKET_ZIP);         // 去掉压缩标志位
				}
			}

			if (msglen <= $this->mCircularBuffer.size - MsgCV::HEADER_SIZE)
			{
				$this->mCircularBuffer.popFrontLen(MsgCV::HEADER_SIZE);
				$this->mCircularBuffer.popFrontBA($this->mMsgBodyBA, msglen);
				$ret = true;
			}
		}

		if ($this->mCircularBuffer->empty())     // 如果已经清空，就直接重置
		{
			$this->mCircularBuffer->clear();    // 读写指针从头开始，方式写入需要写入两部分
		}

		return ret;
	}

	/**
	 * @brief KBEngine 引擎消息处理
	 */
	public function popFrontAll()
	{
		$ret = false;

		if (!$this->mCircularBuffer->empty())
		{
			$ret = true;
			$this->mCircularBuffer.linearize();
			$this->mCircularBuffer.popFrontBA($this->mMsgBodyBA, $this->mCircularBuffer.size);
			$this->mCircularBuffer.clear();
		}

		return $ret;
	}
}

?>