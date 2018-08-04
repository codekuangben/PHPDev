<?php

namespace SDK\Lib;

/**
 *@brief 网络数据缓冲区
 */
class ClientBuffer
{
	protected $mRawBuffer;      // 直接从服务器接收到的原始的数据，可能压缩和加密过
	protected $mMsgBuffer;      // 可以使用的缓冲区
	//protected $mSendTmpBA;   // 发送临时缓冲区，发送的数据都暂时放在这里
	protected $mSendTmpBuffer;  // 发送临时缓冲区，发送的数据都暂时放在这里
	protected $mSocketSendBA;       // 真正发送缓冲区

	protected $mDynBuffer;        // 接收到的临时数据，将要放到 mRawBuffer 中去
	protected $mUnCompressHeaderBA;  // 存放解压后的头的长度
	protected $mSendData;            // 存放将要发送的数据，将要放到 m_sendBuffer 中去
	protected $mTmpData;             // 临时需要转换的数据放在这里
	protected $mTmp1fData;           // 临时需要转换的数据放在这里

	private $mReadMutex;   // 读互斥
	private $mWriteMutex;  // 写互斥

	public function __construct()
	{
		$this->mRawBuffer = new MsgBuffer();
		$this->mMsgBuffer = new MsgBuffer();
		//mSendTmpBA = new ByteBuffer();
		$this->mSendTmpBuffer = new MsgBuffer();
		$this->mSocketSendBA = new ByteBuffer();
		//mSocketSendBA.mId = 1000;

		//mDynBuffer = new DynamicBuffer<byte>(8096);
		$this->mUnCompressHeaderBA = new ByteBuffer();
		$this->mSendData = new ByteBuffer();
		$this->mTmpData = new ByteBuffer(4);
		$this->mTmp1fData = new ByteBuffer(4);

		$this->mReadMutex = new MMutex(false, "ReadMutex");
		$this->mWriteMutex = new MMutex(false, "WriteMutex");
	}

	public function getDynBuffer()
	{
		return $this->mDynBuffer;
	}

	public function getSendTmpBuffer()
	{
		return $this->mSendTmpBuffer;
	}

	public function getSendBuffer()
	{
		return $this->mSocketSendBA;
	}

	public function getSendData()
	{
		return $this->mSendData;
	}

	// 设置 ClientBuffer 字节序
	public function setEndian($endian)
	{
	    $this->mRawBuffer.setEndian($endian);
	    $this->mMsgBuffer.setEndian($endian);

	    $this->mSendTmpBuffer.setEndian($endian);
	    $this->mSocketSendBA.setEndian($endian);

	    $this->mUnCompressHeaderBA.setEndian($endian);
	    $this->mSendData.setEndian($endian);
	    $this->mTmpData.setEndian($endian);
	    $this->mTmp1fData.setEndian($endian);
	}

	public function getRawBuffer()
	{
		return $this->mRawBuffer;
	}

	public function SetRevBufferSize($size)
	{
	    $this->mDynBuffer = new DynBuffer($size);
	}

	public function moveDyn2Raw()
	{
		UtilMsg::formatBytes2Array($this->$this->mDynBuffer->getBuffer(), $this->mDynBuffer->getSize());

		// 接收到一个socket数据，就被认为是一个数据包，这个地方可能会有问题，服务器是这么发送的，只能这么处理，自己写入包的长度
		$this->mTmp1fData.clear();
		$this->mTmp1fData.writeUnsignedInt32($this->mDynBuffer->getSize());      // 填充长度
		$this->mRawBuffer.circularBuffer.pushBackBA($this->mTmp1fData);
		// 写入包的数据
		$this->mRawBuffer.circularBuffer.pushBackArr($this->mDynBuffer->getBuffer(), 0, $this->mDynBuffer->getSize());
	}

	// 自己的消息逻辑
	public function moveRaw2Msg()
	{
	    while ($this->mRawBuffer.popFront())  // 如果有数据
		{
			//UnCompressAndDecryptAllInOne();
		    $this->UnCompressAndDecryptEveryOne();
		}
	}

	public function send($bnet = true)
	{
	    $this->mTmpData.clear();
	    $this->mTmpData.writeUnsignedInt32($this->mSendData->getLength());      // 填充长度

		if (bnet)       // 从 socket 发送出去
		{
		    $mlock = new MLock($this->mWriteMutex);
			{
				//mSendTmpBA.writeUnsignedInt(mSendData.length);                            // 写入头部长度
				//mSendTmpBA.writeBytes(mSendData.dynBuff.buff, 0, mSendData.length);      // 写入内容

			    $this->mSendTmpBuffer.circularBuffer.pushBackBA($this->mTmpData);
			    $this->mSendTmpBuffer.circularBuffer.pushBackBA($this->mSendData);
			}
		}
		else        // 直接放入接收消息缓冲区
		{
			//mTmpData.clear();
			//mTmpData.writeUnsignedInt(mSendData.length);      // 填充长度

		    $this->mMsgBuffer.circularBuffer.pushBackBA($this->mTmpData);              // 保存消息大小字段
		    $this->mMsgBuffer.circularBuffer.pushBackBA($this->mSendData);             // 保存消息大小字段
		}
	}

	public function getMsg()
	{
	    $mlock = new MLock($this->mReadMutex);
		{
		    if ($this->mMsgBuffer.popFront())
			{
			    return $this->mMsgBuffer.msgBodyBA;
			}
		}

		return null;
	}

	// 获取数据，然后压缩加密
	public function getSocketSendData()
	{
	    $this->mSocketSendBA.clear();

		// 获取完数据，就解锁
	    $mlock = new MLock($this->mWriteMutex);
		{
			//mSocketSendBA.writeBytes(mSendTmpBA.dynBuff.buff, 0, (uint)mSendTmpBA.length);
			//mSendTmpBA.clear();
			// 一次全部取出来发送出去
			//mSocketSendBA.writeBytes(mSendTmpBuffer.circularBuffer.buff, 0, (uint)mSendTmpBuffer.circuleBuffer.size);
			//mSendTmpBuffer.circularBuffer.clear();
			// 一次仅仅获取一个消息发送出去，因为每一个消息的长度要填写加密补位后的长度
		    if ($this->mSendTmpBuffer.popFront())     // 弹出一个消息，如果只有一个消息，内部会重置变量
			{
			    $this->mSocketSendBA.writeBytes($this->mSendTmpBuffer.headerBA.dynBuffer.buffer, 0, mSendTmpBuffer.headerBA.length);       // 写入头
			    $this->mSocketSendBA.writeBytes($this->mSendTmpBuffer.msgBodyBA.dynBuffer.buffer, 0, $this->mSendTmpBuffer.msgBodyBA.length);             // 写入消息体
			}
		}

		if (MacroDef.MSG_COMPRESS || MacroDef.MSG_ENCRIPT)
		{
		    $this->mSocketSendBA.setPos(0);
		    $this->CompressAndEncryptEveryOne();
			// CompressAndEncryptAllInOne();
		}
		
		$this->mSocketSendBA->setPos(0);        // 设置指针 pos
	}

	// 压缩加密每一个包
	protected function CompressAndEncryptEveryOne()
	{
		$origMsgLen = 0;    // 原始的消息长度，后面判断头部是否添加压缩标志
		$compressMsgLen = 0;
		$cryptLen = 0;
		$bHeaderChange = false; // 消息内容最前面的四个字节中消息的长度是否需要最后修正

		while ($this->mSocketSendBA.bytesAvailable > 0)
		{
			if (MacroDef.MSG_COMPRESS && !MacroDef.MSG_ENCRIPT)
			{
				$bHeaderChange = false;
			}

			$this->mSocketSendBA.readUnsignedInt32($origMsgLen);    // 读取一个消息包头

			if (MacroDef.MSG_COMPRESS)
			{
				if (origMsgLen > MsgCV.PACKET_ZIP_MIN)
				{
				    $compressMsgLen = $this->mSocketSendBA.compress(origMsgLen);
				}
				else
				{
				    $this->mSocketSendBA.incPosDelta((int)$origMsgLen);
					$compressMsgLen = $origMsgLen;
				}
			}
			// 只加密消息 body
			//#if MSG_ENCRIPT
			//                mSocketSendBA.position -= compressMsgLen;      // 移动加密指针位置
			//                cryptLen = mSocketSendBA.encrypt(m_cryptKeyArr[(int)m_cryptAlgorithm], compressMsgLen, m_cryptAlgorithm);
			//                if (compressMsgLen != cryptLen)
			//                {
			//                    bHeaderChange = true;
			//                }
			//                compressMsgLen = cryptLen;
			//#endif

			// 加密如果系统补齐字节，长度可能会变成 8 字节的证书倍，因此需要等加密完成后再写入长度
			if (MacroDef.MSG_COMPRESS && !MacroDef.MSG_ENCRIPT)
			{
				if (origMsgLen > MsgCV.PACKET_ZIP_MIN)    // 如果原始长度需要压缩
				{
					$bHeaderChange = true;
					$origMsgLen = $compressMsgLen;                // 压缩后的长度
					$origMsgLen |= MsgCV.PACKET_ZIP;            // 添加
				}

				if (bHeaderChange)
				{
				    $this->mSocketSendBA.decPosDelta((int)$compressMsgLen + 4);        // 移动到头部位置
				    $this->mSocketSendBA.writeUnsignedInt32($origMsgLen, false);     // 写入压缩或者加密后的消息长度
				    $this->mSocketSendBA.incPosDelta((int)$compressMsgLen);              // 移动到下一个位置
				}
			}

			// 整个消息压缩后，包括 4 个字节头的长度，然后整个加密
			if (MacroDef.MSG_ENCRIPT)
			{
				$cryptLen = (($compressMsgLen + 4 + 7) / 8) * 8 - 4;      // 计算加密后，不包括 4 个头长度的 body 长度
				if ($origMsgLen > MsgCV.PACKET_ZIP_MIN)    // 如果原始长度需要压缩
				{
					$origMsgLen = $cryptLen;                // 压缩后的长度
					$origMsgLen |= MsgCV.PACKET_ZIP;            // 添加
				}
				else
				{
					$origMsgLen = $cryptLen;                // 压缩后的长度
				}

				$this->mSocketSendBA.decPosDelta((int)($compressMsgLen + 4));        // 移动到头部位置
				$this->mSocketSendBA.writeUnsignedInt32($origMsgLen, false);     // 写入压缩或者加密后的消息长度

				$this->mSocketSendBA.decPosDelta(4);      // 移动到头部
				$this->mSocketSendBA.encrypt($this->mCryptContext, 0);  // 加密
			}
		}

		// 整个消息压缩后，包括 4 个字节头的长度，然后整个加密
//#if MSG_ENCRIPT
		//mSocketSendBA.position = 0;      // 移动到头部
		//mSocketSendBA.encrypt(m_cryptKeyArr[(int)m_cryptAlgorithm], 0, m_cryptAlgorithm);
//#endif
	}

	// 压缩解密作为一个包
	protected function CompressAndEncryptAllInOne()
	{
		$origMsgLen = mSocketSendBA.length;       // 原始的消息长度，后面判断头部是否添加压缩标志
		$compressMsgLen = 0;

		if (origMsgLen > MsgCV.PACKET_ZIP_MIN && MacroDef.MSG_COMPRESS)
		{
		    $compressMsgLen = $this->mSocketSendBA.compress();
		}
		else if (MacroDef.MSG_ENCRIPT)
		{
			$compressMsgLen = $origMsgLen;
			$this->mSocketSendBA.incPosDelta((int)origMsgLen);
		}

		if (MacroDef.MSG_ENCRIPT)
		{
		    $this->mSocketSendBA.decPosDelta((int)compressMsgLen);
		    $compressMsgLen = $this->mSocketSendBA.encrypt($this->mCryptContext, 0);
		}

		if (MacroDef.MSG_COMPRESS || MacroDef.MSG_ENCRIPT)             // 如果压缩或者加密，需要再次添加压缩或者加密后的头长度
		{
			if (origMsgLen > MsgCV.PACKET_ZIP_MIN)    // 如果原始长度需要压缩
			{
				$origMsgLen = $compressMsgLen;
				$origMsgLen |= MsgCV.PACKET_ZIP;            // 添加
			}
			else
			{
				$origMsgLen = $compressMsgLen;
			}

			$this->mSocketSendBA->setPos(0);
			$this->mSocketSendBA.insertUnsignedInt32($origMsgLen);            // 写入压缩或者加密后的消息长度
		}
	}

	// 消息格式
	// |------------- 加密的整个消息  -------------------------------------|
	// |----4 Header----|-压缩的 body----|----4 Header----|-压缩的 body----|
	// |                |                |                |                |
	protected function UnCompressAndDecryptEveryOne()
	{
		if (MacroDef.MSG_ENCRIPT)
		{
		    $this->mRawBuffer.msgBodyBA.decrypt($this->mCryptContext, 0);
		}
//#if MSG_COMPRESS
		//mRawBuffer.headerBA.setPos(0); // 这个头目前没有用，是客户端自己添加的，服务器发送一个包，就认为是一个完整的包
		//mRawBuffer.msgBodyBA.setPos(0);
		//uint msglen = mRawBuffer.headerBA.readUnsignedInt();
		//if ((msglen & DataCV.PACKET_ZIP) > 0)
		//{
		//    mRawBuffer.msgBodyBA.uncompress();
		//}
//#endif

		$this->mRawBuffer.msgBodyBA.setPos(0);

		$msglen = 0;
		while ($this->mRawBuffer.msgBodyBA.bytesAvailable >= 4)
		{
		    $this->mRawBuffer.msgBodyBA.readUnsignedInt32($msglen);    // 读取一个消息包头
			if (msglen == 0)     // 如果是 0 ，就说明最后是由于加密补齐的数据
			{
				break;
			}
			
			if ((msglen & MsgCV.PACKET_ZIP) > 0 && MacroDef.MSG_COMPRESS)
			{
				$msglen &= (~MsgCV.PACKET_ZIP);         // 去掉压缩标志位
				$msglen = $this->mRawBuffer.msgBodyBA.uncompress($msglen);
			}
			else
			{
			    $this->mRawBuffer->msgBodyBA->setPos($this->mRawBuffer->msgBodyBA->getPos() + $msglen);
			}

			$this->mUnCompressHeaderBA.clear();
			$this->mUnCompressHeaderBA.writeUnsignedInt32($msglen);        // 写入解压后的消息的长度，不要写入 msglen ，如果压缩，再加密，解密后，再解压后的长度才是真正的长度
			$this->mUnCompressHeaderBA->setPos(0);

			$mlock = new MLock(mReadMutex);
			{
			    $this->mMsgBuffer.circularBuffer.pushBackBA($this->mUnCompressHeaderBA);             // 保存消息大小字段
			    $this->mMsgBuffer.circularBuffer.pushBackArr($this->mRawBuffer.msgBodyBA.dynBuffer.buffer, mRawBuffer.msgBodyBA.position - msglen, msglen);      // 保存消息大小字段
			}

			Ctx.mInstance.mNetCmdNotify.addOneRevMsg();

			// Test 读取消息头
			// ByteBuffer buff = getMsg();
			// stNullUserCmd cmd = new stNullUserCmd();
			// cmd.derialize(buff);
		}
	}

	protected function UnCompressAndDecryptAllInOne()
	{
		if (MacroDef.MSG_ENCRIPT)
		{
		    $this->mRawBuffer.msgBodyBA.decrypt($this->mCryptContext, 0);
		}

		$msglen = 0;
		if (MacroDef.MSG_COMPRESS)
		{
		    $this->mRawBuffer.headerBA.setPos(0);

		    $this->mRawBuffer.headerBA.readUnsignedInt32(msglen);
			if ((msglen & MsgCV.PACKET_ZIP) > 0)
			{
			    $this->mRawBuffer.msgBodyBA.uncompress();
			}
		}

		if (!MacroDef.MSG_COMPRESS && !MacroDef.MSG_ENCRIPT)
		{
		    $this->mUnCompressHeaderBA.clear();
		    $this->mUnCompressHeaderBA.writeUnsignedInt32($this->mRawBuffer.msgBodyBA.length);
		    $this->mUnCompressHeaderBA->setPos(0);
		}

		$mlock = new MLock($this->mReadMutex);
		{
			if (!MacroDef.MSG_COMPRESS && !MacroDef.MSG_ENCRIPT)
			{
			    $this->mMsgBuffer.circularBuffer.pushBackBA($this->mUnCompressHeaderBA);             // 保存消息大小字段
			}
			$this->mMsgBuffer.circularBuffer.pushBackBA($this->mRawBuffer.msgBodyBA);      // 保存消息大小字段
		}
	}
}

?>