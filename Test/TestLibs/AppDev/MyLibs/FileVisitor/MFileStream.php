<?php

namespace MyLibs\FileVisitor;

use MyLibs\Base\GObject;
use MyLibs\EventHandle\AddOnceAndCallOnceEventDispatch;
use MyLibs\Tools\MEncode;
use MyLibs\Tools\UtilStr;
use MyLibs\Tools\UtilSysLibWrap;

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
 * @brief PHP 文件创建/写入
 * @url https://www.w3school.com.cn/php/php_file_create.asp
 * @brief PHP fseek() 函数
 * @url https://www.w3school.com.cn/php/func_filesystem_fseek.asp
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
	// SEEK_SET - 设定位置等于 offset 字节。默认。
	// SEEK_CUR - 设定位置为当前位置加上 offset。
	// SEEK_END - 设定位置为文件末尾加上 offset （要移动到文件尾之前的位置，offset 必须是一个负值）。
	protected $mFileOffsetMode;

	/**
	 * @brief 仅支持同步操作，目前无视参数 isSyncMode 和 evtDisp。FileMode.CreateNew 如果文件已经存在就抛出异常，FileMode.Append 和 FileAccess.Write 要同时使用
	 */
	public function __construct($filePath, $mode = FileMode::Read)
	{
		$this->mTypeId = "MFileStream";

		$this->mFilePath = $filePath;
		$this->mMode = $mode;
		$this->mFileOpState = FileOpState::eNoOp;
		$this->mFileOffsetMode = SEEK_SET;
	}
	
	public function init()
	{
	    
	}
	
	public function open()
	{
	    $this->checkAndOpen();
	}

	public function seek($offset)
	{
		if($this->mFileOpState == FileOpState::eOpenSuccess)
		{
		    fseek($this->mFileStream, $offset, $this->mFileOffsetMode);
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

	public function readText($offset = 0, $count = 0)
	{
		$this->checkAndOpen();

		$retStr = "";
		
		if ($count == 0)
		{
		    $count = $this->getLength();
		}

		if ($this->mFileOpState == FileOpState::eOpenSuccess)
		{
			try
			{
			    $retStr = fread($this->mFileStream, $count);
			}
			catch (\Exception $err)
			{
					
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

	public function writeText($text, $offset = 0, $count = 0)
	{
		$this->checkAndOpen();

		if ($text != null)
		{
		    if ($count == 0)
		    {
		        $count = UtilStr::length($bytes);
		    }
		    
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
		$this->writeText($text, 0, 0);
	}
}

?>