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

		if (File.Exists(@file))                  // 如果文件存在
		{
			//mFileStream = new FileStream(file, FileMode.Append);
			File.Delete(@file);
			$this->mFileStream = new FileStream(file, FileMode.Create);
		}
		else
		{
			$this->mFileStream = new FileStream(file, FileMode.Create);
		}

		$this->mStreamWriter = new StreamWriter($this->mFileStream);
	}

	public function closeDevice()
	{
		$this->mStreamWriter.Flush();
		//关闭流
		$this->mFileStream.Close();
		$this->mFileStream = null;
	}

	// 写文件
	public function logout(string message, LogColor type = LogColor.eLC_LOG)
	{
		if ($this->isValid())
		{
			if ($this->mStreamWriter != null)
			{
				$this->mStreamWriter.Write(message);
				$this->mStreamWriter.Write("\n");
				$this->mStreamWriter.Flush();             // 立马输出
			}
		}
	}

	// 检测日志目录大小，如果太大，就删除
	protected function checkDirSize($path)
	{
		if (Directory.Exists(path))
		{
			DirectoryInfo dirInfo = new DirectoryInfo(path);
			long size = 0;

			// 所有文件大小.
			FileInfo[] files = dirInfo.GetFiles();

			foreach (FileInfo file in files)
			{
				size += file.Length;
			}

			// 如果超过限制就删除
			if (size > 10 * 1024 * 1024)
			{
				foreach (FileInfo file in files)
				{
					try
					{
						file.Delete();
					}
					catch (Exception /*err*/)
					{

					}
				}
			}

			//{
			//    string[] strFiles = Directory.GetFiles(path);

			//    foreach (string strFile in strFiles)
			//    {
			//        FileInfo fileInfo = new FileInfo(strFile);
			//        Size += fileInfo.Length;
			//    }

			//    if (Size > 10 * 1024 * 1024)
			//    {
			//        foreach (string strFile in strFiles)
			//        {
			//            File.Delete(strFile);
			//        }
			//    }
			//}
		}
	}
}

?>