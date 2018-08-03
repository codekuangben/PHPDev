<?php

namespace SDK\Lib;

/**
 * @brief 文件日志
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
#if UNITY_EDITOR
		//string path = string.Format("{0}{1}", Application.dataPath, "/Debug");
		string path = string.Format("{0}{1}", MFileSys.getWorkPath(), "/Debug");
#else
		string path = string.Format("{0}{1}", Application.persistentDataPath,"/Debug");
#endif
		$this->checkDirSize(path); // 检查目录大小

		string file;
		if(string.IsNullOrEmpty(mFileSuffix))
		{
			file = string.Format("{0}/{1}_{2}{3}", path, mFilePrefix, UtilSysLibWrap.getUTCFormatText(), ".txt");
		}
		else
		{
			file = string.Format("{0}/{1}_{2}{3}{4}{5}", path, mFilePrefix, mFileSuffix, "_", UtilSysLibWrap.getUTCFormatText(), ".txt");
		}
		
		if (!Directory.Exists(path))                    // 判断是否存在
		{
			Directory.CreateDirectory(path);            // 创建新路径
		}

		//if (File.Exists(@file))                  // 判断文件是否存在
		//{
		//    FileInfo fileinfo = new FileInfo(file);
		//    if (fileinfo.Length > 1 * 1024 * 1024)           // 如果大于 1 M ，就删除
		//    {
		//        File.Delete(@file);
		//    }
		//}

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

	public override void closeDevice()
	{
		$this->mStreamWriter.Flush();
		//关闭流
		$this->mStreamWriter.Close();
		$this->mStreamWriter = null;
		$this->mFileStream.Close();
		$this->mFileStream = null;
	}

	// 写文件
	public override void logout(string message, LogColor type = LogColor.eLC_LOG)
	{
		if ($this->isValid())
		{
			if ($this->mStreamWriter != null)
			{
				$this->mStreamWriter.Write(message);
				$this->mStreamWriter.Write("\n");
				//if (type == LogColor.WARN || type == LogColor.ERROR)
				//{
				//    m_stackTrace = new StackTrace(true);        // 这个在 new 的地方生成当时堆栈数据，需要的时候再 new ，否则是旧的堆栈数据
				//    m_traceStr = m_stackTrace.ToString();
				//    mStreamWriter.Write(m_traceStr);
				//    mStreamWriter.Write("\n");
				//}
				$this->mStreamWriter.Flush();             // 立马输出
			}
		}
	}

	// 检测日志目录大小，如果太大，就删除
	protected void checkDirSize(string path)
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