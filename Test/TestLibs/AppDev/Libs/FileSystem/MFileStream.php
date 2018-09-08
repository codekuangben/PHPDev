<?php

namespace SDK\Lib;

class eFileOpState
{
	public const eNoOp = 0;      // 无操作
	public const eOpening = 1;   // 打开中
	public const eOpenSuccess = 2;   // 打开成功
	public const eOpenFail = 3;      // 打开失败
	public const eOpenClose = 4;     // 关闭
}

/**
 * @brief 仅支持本地文件操作，仅支持同步操作
 */
class MFileStream extends GObject
{
	public $mFileStream;
	
	protected $mFilePath;
	protected $mMode;
	protected $mAccess;
	protected $mFileOpState;

	protected $mText;
	protected $mBytes;
	protected $mOpenedEventDispatch;

	/**
	 * @brief 仅支持同步操作，目前无视参数 isSyncMode 和 evtDisp。FileMode.CreateNew 如果文件已经存在就抛出异常，FileMode.Append 和 FileAccess.Write 要同时使用
	 */
	public function __construct($filePath, $openedHandle = null, $mode = FileMode.Open, $access = FileAccess.Read)
	{
		$this->mTypeId = "MFileStream";

		$this->mFilePath = filePath;
		$this->mMode = mode;
		$this->mAccess = access;
		$this->mFileOpState = eFileOpState.eNoOp;

		$this->checkAndOpen(openedHandle);
	}

	public function seek($offset, $origin)
	{
		if($this->mFileOpState == eFileOpState.eOpenSuccess)
		{
			$this->mFileStream.Seek(offset, origin);
		}
	}

	public function addOpenedHandle($openedDisp = null)
	{
		if ($this->mOpenedEventDispatch == null)
		{
			$this->mOpenedEventDispatch = new AddOnceAndCallOnceEventDispatch();
		}

		$this->mOpenedEventDispatch.addEventHandle(null, openedDisp);
	}

	public function dispose()
	{
		$this->close();
	}

	protected function syncOpenFileStream()
	{
		if ($this->mFileOpState == eFileOpState.eNoOp)
		{
			$this->mFileOpState = eFileOpState.eOpening;

			try
			{
			    $this->mFileStream = fopen($this->mFilePath, $this->mMode, $this->mAccess);
				$this->mFileOpState = eFileOpState.eOpenSuccess;
			}
			catch(\Exception $exp)
			{
				$this->mFileOpState = eFileOpState.eOpenFail;
			}

			$this->onAsyncOpened();
		}
	}

	// 异步打开结束
	public function onAsyncOpened()
	{
		if ($this->mOpenedEventDispatch != null)
		{
			$this->mOpenedEventDispatch.dispatchEvent(this);
		}
	}

	protected function checkAndOpen($openedHandle = null)
	{
		if (openedHandle != null)
		{
			$this->addOpenedHandle(openedHandle);
		}

		if ($this->mFileOpState == eFileOpState.eNoOp)
		{
			$this->syncOpenFileStream();
		}
	}

	public function isValid()
	{
		return $this->mFileOpState == eFileOpState.eOpenSuccess;
	}

	// 获取总共长度
	public function getLength()
	{
		$len = 0;

		if ($this->mFileOpState == eFileOpState.eOpenSuccess)
		{
			if ($this->mFileStream != null)
			{
				$len = $this->mFileStream.Length;
			}
			/*
			if (mFileStream != null && mFileStream.CanSeek)
			{
				try
				{
					len = (int)mFileStream.Seek(0, SeekOrigin.End);     // 移动到文件结束，返回长度
					len = (int)mFileStream.Position;                    // Position 移动到 Seek 位置
				}
				catch(Exception exp)
				{
				}
			}
			*/
		}

		return len;
	}

	protected function close()
	{
		if ($this->mFileOpState == eFileOpState.eOpenSuccess)
		{
			if ($this->mFileStream != null)
			{
				$this->mFileStream.Close();
				$this->mFileStream.Dispose();
				$this->mFileStream = null;
			}

			$this->mFileOpState = eFileOpState.eOpenClose;
			$this->mFileOpState = eFileOpState.eNoOp;
		}
	}

	public function readText($offset = 0, $count = 0, $encode = null)
	{
		$this->checkAndOpen();

		$retStr = "";
		$bytes = null;

		if ($encode == null)
		{
			$encode = Encoding.UTF8;
		}

		if ($count == 0)
		{
			$count = getLength();
		}

		if ($this->mFileOpState == eFileOpState.eOpenSuccess)
		{
			if ($this->mFileStream.CanRead)
			{
				try
				{
					//$bytes = new byte[$count];
					$this->mFileStream.Read(bytes, 0, count);

					$retStr = encode.GetString(bytes);
				}
				catch (\Exception $err)
				{
						
				}
			}
		}

		return retStr;
	}

	public function readByte($offset = 0, $count = 0)
	{
		$this->checkAndOpen();

		if ($count == 0)
		{
			$count = getLength();
		}

		$bytes = null;

		if ($this->mFileStream.CanRead)
		{
			try
			{
				//$bytes = new byte[count];
				$this->mFileStream.Read(bytes, 0, count);
			}
			catch (\Exception $err)
			{
					
			}
		}

		return $bytes;
	}

	public function writeText($text, $gkEncode = MEncode.eUTF8)
	{
		$encode = UtilSysLibWrap.convGkEncode2EncodingEncoding(gkEncode);

		$this->checkAndOpen();

		if ($this->mFileStream.CanWrite)
		{
			//if (encode == null)
			//{
			//    encode = MEncode.UTF8;
			//}

			$bytes = encode.GetBytes(text);
			if (bytes != null)
			{
				try
				{
					$this->mFileStream.Write(bytes, 0, bytes.Length);
				}
				catch (\Exception $err)
				{
						
				}
			}
		}
	}

	public function writeByte($bytes, $offset = 0, $count = 0)
	{
		$this->checkAndOpen();

		if ($this->mFileStream.CanWrite)
		{
			if (bytes != null)
			{
				if (count == 0)
				{
					$count = bytes.Length;
				}

				if (count != 0)
				{
					try
					{
						$this->mFileStream.Write(bytes, offset, count);
					}
					catch (\Exception $err)
					{
							
					}
				}
			}
		}
	}

	public function writeLine($text, $gkEncode = MEncode.eUTF8)
	{
		$text = $text + UtilSysLibWrap.CR_LF;
		writeText($text, gkEncode);
	}
}

?>