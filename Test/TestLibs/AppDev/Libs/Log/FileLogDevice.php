<?php

namespace SDK\Lib;

/**
 * @brief 文件日志
 * @url http://www.w3school.com.cn/php/php_ref_filesystem.asp
 * @url http://www.w3school.com.cn/php/func_filesystem_file.asp
 */
class FileLogDevice extends  LogDeviceBase
{
	protected $mFileSuffix;      // 文件后缀。例如 log_suffix.txt ，suffix 就是后缀
	protected $mFilePrefix;      // 文件前缀。例如 prefix_suffix.txt ，prefix 就是前缀
	protected $mFileStream;
	protected $mStreamWriter;

	public function __construct()
	{
		$this->mFilePrefix = "log";
	}

	public function getFileSuffix()
	{
		return $this->mFileSuffix;
	}
	
	public function setFileSuffix($value)
	{
	    $this->mFileSuffix = $value;
	}

	public function getFilePrefix()
	{
		return $this->mFilePrefix; 
	}
	
	public function setFilePrefix($value)
	{
	    $this->mFilePrefix = $value;
	}

	public function isValid()
	{
		return null != $this->mFileStream;
	}

	public function initDevice()
	{
	    $path = MFileSys.getWorkPath() . "/Debug";
	    $this->checkDirSize($path); // 检查目录大小

		$file = "";
		if(UtilStr::IsNullOrEmpty($this->mFileSuffix))
		{
		    $file = UtilStr::Format("{0}/{1}_{2}{3}", $path, $this->mFilePrefix, UtilSysLibWrap::getUTCFormatText(), ".txt");
		}
		else
		{
		    $file = UtilStr::Format("{0}/{1}_{2}{3}{4}{5}", $path, $this->mFilePrefix, $this->mFileSuffix, "_", UtilSysLibWrap::getUTCFormatText(), ".txt");
		}
		
		if (!Directory.Exists($path))                    // 判断是否存在
		{
		    Directory.CreateDirectory($path);            // 创建新路径
		}

		if (UtilFileIO::existFile($file))                  // 如果文件存在
		{
		    UtilFileIO::deleteFile($file);
		    $this->mFileStream = fopen(file, "wb");
		}
		else
		{
		    $this->mFileStream = fopen(file, "wb");
		}
	}

	public function closeDevice()
	{
		$this->mStreamWriter.Flush();
		//关闭流
		$this->mFileStream.Close();
		$this->mFileStream = null;
	}

	// 写文件
	public function logout($message, $type = LogColor::eLC_LOG)
	{
		if ($this->isValid())
		{
		    if ($this->mFileStream != null)
			{
			    fwrite($this->mFileStream, message);
			    fwrite($this->mFileStream, "\n");
			    fflush($this->mFileStream);             // 立马输出
			}
		}
	}
}

?>