<?php

namespace MyLibs;

class FileOpState
{
	public const eNoOp = 0;      // 无操作
	public const eOpening = 1;   // 打开中
	public const eOpenSuccess = 2;   // 打开成功
	public const eOpenFail = 3;      // 打开失败
	public const eOpenClose = 4;     // 关闭
}

class FileMode
{
    public const Read = 'r';
    public const ReadPlus = 'r+';
    public const Write = 'w';
    public const WritePlus = 'w+';
    public const Append = 'a';
    public const AppendPlus = 'w+';
}

/**
 * @brief 仅支持本地文件操作，仅支持同步操作
 * @url https://www.php.net/manual/zh/function.fopen.php
 */
class MFileStream extends GObject
{
	public $mFileStream;
	
	protected $mFilePath;
	protected $mMode;
	protected $mFileOpState;

	protected $mText;
	protected $mBytes;
	protected $mOpenedEventDispatch;

	/**
	 * @brief 仅支持同步操作，目前无视参数 isSyncMode 和 evtDisp。FileMode.CreateNew 如果文件已经存在就抛出异常，FileMode.Append 和 FileAccess.Write 要同时使用
	 */
	public function __construct($filePath, $mode = FileMode::Read)
	{
		$this->mTypeId = "MFileStream";

		$this->mFilePath = $filePath;
		$this->mMode = $mode;
		$this->mFileOpState = FileOpState::eNoOp;
	}
	
	public function init()
	{
	    
	}
	
	public function open()
	{
	    $this->checkAndOpen();
	}

	public function seek($offset, $origin)
	{
		if($this->mFileOpState == FileOpState::eOpenSuccess)
		{
			$this->mFileStream->Seek(offset, origin);
		}
	}

	public function addOpenedHandle($openedDisp = null)
	{
		if ($this->mOpenedEventDispatch == null)
		{
			$this->mOpenedEventDispatch = new AddOnceAndCallOnceEventDispatch();
		}

		$this->mOpenedEventDispatch->addEventHandle(null, openedDisp);
	}

	public function dispose()
	{
		$this->close();
	}

	protected function syncOpenFileStream()
	{
		if ($this->mFileOpState == FileOpState::eNoOp)
		{
			$this->mFileOpState = FileOpState::eOpening;

			try
			{
			    $this->mFileStream = fopen($this->mFilePath, $this->mMode);
				$this->mFileOpState = FileOpState::eOpenSuccess;
			}
			catch(\Exception $exp)
			{
				$this->mFileOpState = FileOpState::eOpenFail;
			}

			$this->onAsyncOpened();
		}
	}

	// 异步打开结束
	public function onAsyncOpened()
	{
		if ($this->mOpenedEventDispatch != null)
		{
			$this->mOpenedEventDispatch->dispatchEvent(this);
		}
	}

	protected function checkAndOpen()
	{
		if ($this->mFileOpState == FileOpState::eNoOp)
		{
			$this->syncOpenFileStream();
		}
	}

	public function isValid()
	{
		return $this->mFileOpState == FileOpState::eOpenSuccess;
	}

	// 获取总共长度
	public function getLength()
	{
		$len = 0;

		if ($this->mFileOpState == FileOpState::eOpenSuccess)
		{
		    $len = filesize($this->mFilePath);
			/*
			if (mFileStream != null && mFileStream->CanSeek)
			{
				try
				{
					len = (int)mFileStream->Seek(0, SeekOrigin::End);     // 移动到文件结束，返回长度
					len = (int)mFileStream->Position;                    // Position 移动到 Seek 位置
				}
				catch(Exception exp)
				{
				}
			}
			*/
		}

		return $len;
	}

	public function close()
	{
		if ($this->mFileOpState == FileOpState::eOpenSuccess)
		{
			if ($this->mFileStream != null)
			{
			    fclose($this->mFileStream);
				$this->mFileStream = null;
			}

			$this->mFileOpState = FileOpState::eOpenClose;
			$this->mFileOpState = FileOpState::eNoOp;
		}
	}

	public function readText($offset = 0, $count = 0, $encode = null)
	{
		$this->checkAndOpen();

		$retStr = "";
		$bytes = null;

		if ($encode == null)
		{
			$encode = MEncode::UTF8;
		}

		if ($count == 0)
		{
			$count = getLength();
		}

		if ($this->mFileOpState == FileOpState::eOpenSuccess)
		{
			if ($this->mFileStream->CanRead)
			{
				try
				{
					//$bytes = new byte[$count];
					$this->mFileStream->Read($bytes, 0, $count);

					$retStr = MUtilEncode::GetString($encode, $bytes);
				}
				catch (\Exception $err)
				{
						
				}
			}
		}

		return $retStr;
	}

	public function readByte($offset = 0, $count = 0)
	{
		$this->checkAndOpen();

		if ($count == 0)
		{
		    $count = $this->getLength();
		}

		$bytes = null;

		try
		{
			//$bytes = new byte[count];
		    $bytes = fread($this->mFileStream, $count);
		}
		catch (\Exception $err)
		{
				
		}

		return $bytes;
	}

	public function writeText($text)
	{
		$this->checkAndOpen();

		if ($text != null)
		{
			try
			{
			    fwrite($this->mFileStream, $text);
			}
			catch (\Exception $err)
			{
					
			}
		}
	}

	public function writeByte($bytes, $offset = 0, $count = 0)
	{
		$this->checkAndOpen();

		if ($bytes != null)
		{
			if ($count == 0)
			{
			    $count = UtilStr::length($bytes);
			}

			if ($count != 0)
			{
				try
				{
				    fwrite($this->mFileStream, $bytes, $count);
				}
				catch (\Exception $err)
				{
						
				}
			}
		}
	}

	public function writeLine($text, $gkEncode = MEncode::eUTF8)
	{
		$text = $text + UtilSysLibWrap::CR_LF;
		writeText($text, $gkEncode);
	}
}

?>