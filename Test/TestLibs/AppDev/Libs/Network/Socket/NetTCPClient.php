using System.Net;
using System.Net.Sockets;
using System;
using System.Threading;

namespace SDK\Lib;
{
public class NetTCPClient
{
	// 发送和接收的超时时间
	public int mConnectTimeout = 5000;
	// 超时值（以毫秒为单位）。如果将该属性设置为 1 到 499 之间的值，该值将被更改为 500。默认值为 0，指示超时期限无限大。指定 -1 还会指示超时期限无限大。
	//public int m_sendTimeout = 5000;
	//public int m_revTimeout = 0;

	public string mIp;
	public int mPort;

	protected Socket mSocket = null;
	protected ClientBuffer mClientBuffer;
	protected bool mIsRecvThreadStart;      // 接收线程是否启动
	protected bool mIsConnected;

	protected MEvent mMsgSendEndEvent;       // 当前所有的消息都发送出去了，通知等待线程
	protected MMutex mSendMutex;   // 读互斥

	public NetTCPClient(string ip = "localhost", int port = 5000)
	{
		$this->mIsRecvThreadStart = false;
		$this->mIsConnected = false;
		$this->mMsgSendEndEvent = new MEvent(false);
		$this->mSendMutex = new MMutex(false, "NetTCPClient_SendMutex");

		$this->mIp = ip;
		$this->mPort = port;

		$this->mClientBuffer = new ClientBuffer();
		$this->mClientBuffer.setEndian(SystemEndian.msServerEndian);     // 设置服务器字节序
	}

	public ClientBuffer clientBuffer
	{
		get
		{
			return $this->mClientBuffer;
		}
	}

	public bool brecvThreadStart
	{
		get
		{
			return $this->mIsRecvThreadStart;
		}
		set
		{
			$this->mIsRecvThreadStart = value;
		}
	}

	public bool isConnected
	{
		get
		{
			return $this->mIsConnected;
		}
	}

	public MEvent msgSendEndEvent
	{
		get
		{
			return $this->mMsgSendEndEvent;
		}
		set
		{
			$this->mMsgSendEndEvent = value;
		}
	}

	// 是否可以发送新的数据，上一次发送的数据是否发送完成，只有上次发送的数据全部发送完成，才能发送新的数据
	public bool canSendNewData()
	{
		return $this->mClientBuffer.sendBuffer.bytesAvailable == 0;
	}

	// 设置接收缓冲区大小，和征途服务器对接，这个一定要和服务器大小一致，并且一定要是 8 的整数倍，否则在消息比较多，并且一个包发送过来的时候，会出错
	public void SetRevBufferSize(int size)
	{
		$this->mSocket.ReceiveBufferSize = size;      // ReceiveBufferSize 默认 8096 字节
		$this->mClientBuffer.SetRevBufferSize(size);
	}

	public void SetSendBufferSize(int size)
	{
		$this->mSocket.SendBufferSize = size;      // SendBufferSize 默认 8096 字节
	}

	// 连接服务器
	public bool Connect(string address, int remotePort)
	{
		if ($this->mSocket != null && $this->mSocket.Connected)
		{
			return true;
		}
		try
		{
			//获得远程服务器的地址
			IPAddress remoteAdd = IPAddress.Parse(address);
			IPEndPoint ipe = new IPEndPoint(remoteAdd, remotePort);
			// 创建socket
			$this->mSocket = new Socket(AddressFamily.InterNetwork, SocketType.Stream, ProtocolType.Tcp);
			// 开始连接
			IAsyncResult result = $this->mSocket.BeginConnect(ipe, new System.AsyncCallback(ConnectionCallback), mSocket);
			// 这里做一个超时的监测，当连接超过5秒还没成功表示超时
			bool success = result.AsyncWaitHandle.WaitOne(mConnectTimeout, true);
			if (!success)
			{
				//超时
				//Disconnect(0);
			}
			else
			{
				// 设置建立链接标识
				$this->mIsConnected = true;
				// 打印端口信息
				string ipPortStr;

				ipPortStr = string.Format("local IP: {0}, Port: {1}", ((IPEndPoint)mSocket.LocalEndPoint).Address.ToString(), ((IPEndPoint)mSocket.LocalEndPoint).Port.ToString());

				ipPortStr = string.Format("Remote IP: {0}, Port: {1}", ((IPEndPoint)mSocket.RemoteEndPoint).Address.ToString(), ((IPEndPoint)mSocket.RemoteEndPoint).Port.ToString());;
			}
		}
		catch (System.Exception e)
		{
			// 连接失败
			Ctx.mInstance.mLogSys.error(e.Message);
			return false;
		}

		return true;
	}

	// 异步连接回调
	private void ConnectionCallback(System.IAsyncResult ar)
	{
		try
		{
			// 与服务器取得连接
			$this->mSocket.EndConnect(ar);
			$this->mIsConnected = true;
			// 设置选项
			$this->mSocket.SetSocketOption(SocketOptionLevel.Tcp, SocketOptionName.NoDelay, true);
			$this->SetRevBufferSize(8096);
			// 设置 timeout
			//mSocket.SendTimeout = m_sendTimeout;
			//mSocket.ReceiveTimeout = m_revTimeout;

			if (!MacroDef::NET_MULTHREAD)
			{
				$this->Receive();
			}

			// 连接成功，通知
			// 这个在主线程中调用
			Ctx.mInstance.mSysMsgRoute.pushMsg(new SocketOpenedMR());
		}
		catch (System.Exception e)
		{
			// 错误处理
			if (e.GetType() == typeof(SocketException))
			{
				if (((SocketException)e).SocketErrorCode == SocketError.ConnectionRefused)
				{
					// 输出日志
					if (MacroDef::ENABLE_LOG)
					{
						Ctx.mInstance.mLogSys.log(e.Message);
					}
				}
				else
				{
					// 输出日志
					if (MacroDef::ENABLE_LOG)
					{
						Ctx.mInstance.mLogSys.log(e.Message);
					}
				}
			}
			else
			{
				// 输出日志
				Ctx.mInstance.mLogSys.error(e.Message);
			}

			// 一旦建立失败
			//Disconnect();
		}
	}

	// 接收数据
	public void Receive()
	{
		// 只有 socket 连接的时候才继续接收数据
		if ($this->mSocket.Connected)
		{
			// 接收从服务器返回的信息
			IAsyncResult asyncSend = $this->mSocket.BeginReceive(mClientBuffer.dynBuffer.buffer, 0, (int)mClientBuffer.dynBuffer.capacity, SocketFlags.None, new System.AsyncCallback(ReceiveData), 0);

			//checkThread();

			//bool success = asyncSend.AsyncWaitHandle.WaitOne(m_revTimeout, true);
			//if (!success)
			//{
			//    Ctx.mInstance.mLogSys.asyncLog(string.Format("RecvMsg Timeout {0} ", m_revTimeout));
			//}
		}
	}

	// 接收头消息
	private void ReceiveData(System.IAsyncResult ar)
	{
		if (!$this->checkAndUpdateConnect())        // 如果连接完成后直接断开，这个时候如果再使用 mSocket.EndReceive 这个函数就会抛出异常
		{
			return;
		}

		//checkThread();

		if ($this->mSocket == null)        // SocketShutdown.Both 这样关闭，只有还会收到数据，因此判断一下
		{
			return;
		}

		int read = 0;
		try
		{
			read = $this->mSocket.EndReceive(ar);          // 获取读取的长度

			if (read > 0)
			{
				if (MacroDef::ENABLE_LOG)
				{
					Ctx.mInstance.mLogSys.log("Receive data " + read.ToString());
				}

				$this->mClientBuffer.dynBuffer.size = (uint)read; // 设置读取大小
				//$this->mClientBuffer.moveDyn2Raw();             // 将接收到的数据放到原始数据队列
				//$this->mClientBuffer.moveRaw2Msg();             // 将完整的消息移动到消息缓冲区
				$this->mClientBuffer.moveDyn2Raw_KBE();
				$this->mClientBuffer.moveRaw2Msg_KBE();
				$this->Receive();                  // 继续接收
			}
			else
			{
				// Socket 已经断开或者异常，需要断开链接
				$this->Disconnect(0);
			}
		}
		catch (System.Exception e)
		{
			// 输出日志
			Ctx.mInstance.mLogSys.error(e.Message);
			Ctx.mInstance.mLogSys.error("Receive data error");
			// 断开链接
			$this->Disconnect(0);
		}
	}

	// 发送消息
	public void Send()
	{
		using (MLock mlock = new MLock(mSendMutex))
		{
			if (!$this->checkAndUpdateConnect())
			{
				return;
			}

			//checkThread();

			if ($this->mSocket == null)
			{
				return;
			}

			if ($this->mClientBuffer.sendBuffer.bytesAvailable == 0)     // 如果发送缓冲区没有要发送的数据
			{
				if ($this->mClientBuffer.sendTmpBuffer.circularBuffer.size > 0)      // 如果发送临时缓冲区有数据要发
				{
					//$this->mClientBuffer.getSocketSendData();
					$this->mClientBuffer.getSocketSendData_KBE();
				}

				if ($this->mClientBuffer.sendBuffer.bytesAvailable == 0)        // 如果发送缓冲区中确实没有数据
				{
					if (MacroDef::NET_MULTHREAD)
					{
						$this->mMsgSendEndEvent.Set();        // 通知等待线程，所有数据都发送完成
					}
					return;
				}
			}

			try
			{
				if (MacroDef::ENABLE_LOG)
				{
					Ctx.mInstance.mLogSys.log(string.Format("Start send byte num {0} ", mClientBuffer.sendBuffer.bytesAvailable));
				}

				IAsyncResult asyncSend = $this->mSocket.BeginSend(mClientBuffer.sendBuffer.dynBuffer.buffer, (int)mClientBuffer.sendBuffer.position, (int)mClientBuffer.sendBuffer.bytesAvailable, 0, new System.AsyncCallback(SendCallback), 0);
				//bool success = asyncSend.AsyncWaitHandle.WaitOne(m_sendTimeout, true);
				//if (!success)
				//{
				//    Ctx.mInstance.mLogSys.asyncLog(string.Format("SendMsg Timeout {0} ", m_sendTimeout));
				//}
			}
			catch (System.Exception e)
			{
				if (MacroDef::NET_MULTHREAD)
				{
					$this->mMsgSendEndEvent.Set();        // 发生异常，通知等待线程，所有数据都发送完成，防止等待线程不能解锁
				}
				// 输出日志
				Ctx.mInstance.mLogSys.error(e.Message);
				// 断开链接
				$this->Disconnect(0);
			}
		}
	}

	//发送回调
	private void SendCallback(System.IAsyncResult ar)
	{
		using (MLock mlock = new MLock(mSendMutex))
		{
			if (!$this->checkAndUpdateConnect())
			{
				return;
			}

			//checkThread();

			try
			{
				int bytesSent = $this->mSocket.EndSend(ar);

				if (MacroDef::ENABLE_LOG)
				{
					Ctx.mInstance.mLogSys.log(string.Format("End send bytes num {0} ", bytesSent));
				}

				if (mClientBuffer.sendBuffer.length < mClientBuffer.sendBuffer.position + (uint)bytesSent)
				{
					if (MacroDef::ENABLE_LOG)
					{
						Ctx.mInstance.mLogSys.log(string.Format("End send bytes error {0}", bytesSent));
					}

					$this->mClientBuffer.sendBuffer.setPos(mClientBuffer.sendBuffer.length);
				}
				else
				{
					$this->mClientBuffer.sendBuffer.setPos(mClientBuffer.sendBuffer.position + (uint)bytesSent);
				}

				if ($this->mClientBuffer.sendBuffer.bytesAvailable > 0)     // 如果上一次发送的数据还没发送完成，继续发送
				{
					$this->Send();                 // 继续发送数据
				}
			}
			catch (System.Exception e)
			{
				// 输出日志
				Ctx.mInstance.mLogSys.error(e.Message);
				$this->Disconnect(0);
			}
		}
	}

	// 关闭连接
	public void Disconnect(int timeout = 0)
	{
		// 关闭之后 mSocket.Connected 设置成 false
		if ($this->mSocket != null)
		{
			if ($this->mSocket.Connected)
			{
				$this->mSocket.Shutdown(SocketShutdown.Both);
				//mSocket.Close(timeout);  // timeout 不能是 0 ，是 0 含义未定义
				if (timeout > 0)
				{
					$this->mSocket.Close(timeout);
				}
				else
				{
					$this->mSocket.Close();
				}
			}
			else
			{
				$this->mSocket.Close();
			}

			$this->mSocket = null;
		}
	}
	
	// 检查并且更新连接状态
	protected bool checkAndUpdateConnect()
	{
		if ($this->mSocket != null && !$this->mSocket.Connected)
		{
			if ($this->mIsConnected)
			{
				Ctx.mInstance.mSysMsgRoute.pushMsg(new SocketCloseedMR());
			}

			$this->mIsConnected = false;
		}

		return mIsConnected;
	}

	protected bool checkThread()
	{
		if(Ctx.mInstance.mNetMgr.isNetThread(Thread.CurrentThread.ManagedThreadId))
		{
			return true;
		}

		return false;
	}
}
}