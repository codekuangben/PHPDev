<?php

namespace SDK\Lib;

class MSearchOption
{
	public const eTopDirectoryOnly = 0;
	public const eAllDirectories = 1;
}

class UtilFileIO
{
	public const DOT = ".";
	public const SLASH = "/";

	public static function normalPath($path)
	{
	    return UtilStr::replace($path, '\\', '/');
	}

	// 删除目录的时候，一定要关闭这个文件夹，否则删除文件夹可能出错
	// https://www.2cto.com/kf/201707/662517.html
	public static function deleteDirectory($path, $recursive = true)
	{
	    if (UtilFileIO::existDirectory($path))
		{
			try
			{
			    //先删除目录下的文件：
			    $handle = opendir($path);
			    
			    if(null != $handle)
			    {
			        while (false !== ($file = readdir($handle)))
    			    {
    			        if($file != "." && $file!="..")
    			        {
    			            $fullpath = $path."/".$file;
    			            
    			            if(!is_dir($fullpath))
    			            {
    			                if(unlink($fullpath))
    			                {
    			                    echo "delete file success, path = " . $fullpath . "\n";
    			                }
    			                else
    			                {
    			                    echo "delete file fail, path = " . $fullpath . "\n";
    			                }
    			            }
    			            else
    			            {
    			                if($recursive)
    			                {
    			                    UtilFileIO::deleteDirectory($fullpath, $recursive);
    			                }
    			            }
    			        }
    			    }
			    }
			    
			    closedir($path);
			    
			    //删除当前文件夹：
			    if(rmdir($path))
			    {
			        echo "delete file success, path = " . $path . "\n";
			        return true;
			    } 
			    else
			    {
			        echo "delete file fail, path = " . $path . "\n";
			        return false;
			    }
			}
			catch (\Exception $err)
			{
			    echo(string.Format("UtilFileIO::DeleteDirectory, error, Error = {0}, path = {1}", $err->getMessage(), $path));
			}
		}
	}

	// 目录是否存在
	public static function existDirectory($path)
	{
	    return is_dir($path);
	}

	// 文件是否存在
	// http://www.php.net/manual/zh/function.file-exists.php
	public static function existFile($path)
	{
	    return file_exists($path);
	}

	// 移动文件
	// http://www.w3school.com.cn/php/func_filesystem_copy.asp
	public static function move($srcPath, $destPath)
	{
		try
		{
		    rename($srcPath, $destPath);
		}
		catch (\Exception $err)
		{
		    echo(UtilStr::Format("UtilFileIO::move error, ErrorMsg = {0}, srcPath = {1}, destPath = {2}", $$err->getMessage(), srcPath, destPath));
		}
	}

	// http://www.w3school.com.cn/php/func_filesystem_unlink.asp
	public static function deleteFile($path)
	{
	    $ret = true;
	    
	    if (UtilFileIO::existFile($path))
		{
			try
			{
			    if(unlink($path))
			    {
			        echo("delete file success");
			        $ret = true;
			    }
			    else
			    {
			        echo("delete file fail");
			        $ret = false;
			    }
			}
			catch (\Exception $err)
			{
			    Debug.Log(string.Format("UtilFileIO::deleteFile, error, Path = {0}, ErrorMessage = {1}", path, $err->getMessage()));
			}
		}

		return $ret;
	}

	// destFileName 目标路径和文件名字
	// http://www.w3school.com.cn/php/func_filesystem_copy.asp
	public static function copyFile($sourceFileName, $destFileName, $overwrite = false)
	{
	    $ret = true;
	    
		try
		{
		    if($overwrite)
		    {
		        if(UtilFileIO::existFile($destFileName))
		        {
		            UtilFileIO::deleteFile($destFileName);
		        }
		        
		        $ret = copy($sourceFileName, $destFileName, $overwrite);
		    }
		    else
		    {
		        if(!UtilFileIO::existFile($destFileName))
		        {
		            $ret = copy($sourceFileName, $destFileName, $overwrite);
		        }
		    }
		}
		catch (\Exception $err)
		{
			echo(UtilStr::Format("UtilFileIO::copyFile, error, ErrorMsg = {0}, sourceFileName = {1}, destFileName = {2}", $err->getMessage(), sourceFileName, destFileName));
		}
		
		return $ret;
	}

	static public function createDirectory($pathAndName, $isRecurse = false)
	{
	    $ret = true;
	    
	    if ($isRecurse)
		{
		    $normPath = UtilFileIO::normalPath($pathAndName);
		    $pathArr = UtilStr::split($normPath, '/');

			$curCreatePath = "";
			$idx = 0;
			$listLen = UtilList::count($pathArr);

			while ($idx < $listLen)
			{
				// Mac 下是以 ‘／’ 开头的，如果使用  '/' 分割字符串，就会出现字符长度为 0 的问题
			    if (0 != UtilStr::length($pathArr[idx]))
				{
				    if(UtilStr::length($curCreatePath) == 0)
					{
					    $curCreatePath = $pathArr[$idx];
					}
					else
					{
					    $curCreatePath = string.Format("{0}/{1}", $curCreatePath, $pathArr[$idx]);
					}

					if (!is_dir($curCreatePath))
					{
						try
						{
						    $ret = mkdir($curCreatePath);
						    
						    if($ret)
						    {
						        echo("createDirectory success");
						    }
						    else
						    {
						        echo("createDirectory fail");
						    }
						}
						catch(\Exception $err)
						{
							echo (UtilStr::Format ("UitlPath::CreateDirectory, error, ErrorMsg = {0}, path = {1}", $err->getMessage(), curCreatePath));
						}
					}
				}
				
				$idx += 1;
			}
		}
		else
		{
			try
			{
			    if (!is_dir($pathAndName))
				{
					// 这个接口默认就支持创建所有没有的目录
				    mkdir($pathAndName, 0777);
				}
			}
			catch (\Exception $err)
			{
				echo(string.Format("UtilFileIO::CreateDirectory, error, ErrorMsg = {0}, pathAndName = {1}", $err->getMessage(), pathAndName));
			}
		}
	}

	static public function renameFile($srcPath, $destPath)
	{
		try
		{
			if (UtilFileIO::existFile(srcPath))
			{
			    UtilFileIO.move($srcPath, $destPath);
				return true;
			}
			else
			{
				return false;
			}
		}
		catch (\Exception $err)
		{
		    Debug.Log(string.Format("UtilFileIO::renameFile, error, ErrorMsg = {0}, srcPath = {1}, destPath = {2}", $err->getMessage(), srcPath, destPath));
			return false;
		}
	}

	static public function combine(...$pathList)
	{
		$idx = 0;
		$listLen = UtilList::count($pathList);
		$ret = "";
		$isFirst = true;

		while ($idx < $listLen)
		{
		    if (UtilStr::length($pathList[$idx]) > 0)
			{
			    if(!$isFirst)
				{
				    $ret = $ret . "/";
				}
				else
				{
				    $isFirst = false;
				}

				$ret = $ret . $pathList[$idx];
			}

			$idx += 1;
		}

		$ret = UtilStr::replace($ret, "//", "/");

		return ret;
	}

	// 获取扩展名
	static public function getFileExt($path)
	{
	    $dotIdx = UtilStr::LastIndexOf($path, '.');

	    if (-1 != $dotIdx)
		{
		    return UtilStr::substr($path, dotIdx + 1);
		}

		return "";
	}

	// 获取文件名字，没有路径，但是有扩展名字
	static public function getFileNameWithExt($fullPath)
	{
	    $index = $fullPath.LastIndexOf('/');
		$ret = "";

		if ($index == -1)
		{
		    $ret = UtilStr::LastIndexOf($fullPath, '\\');
		}
		if ($index != -1)
		{
		    $ret = UtilStr::substr($fullPath, $index + 1);
		}
		else
		{
		    $ret = $fullPath;
		}

		return $ret;
	}

	// 获取文件名字，没有扩展名字
	static public function getFileNameNoExt($fullPath)
	{
	    $index = UtilStr::LastIndexOf($fullPath, '/');
		$ret = "";

		if ($index == -1)
		{
		    $index = UtilStr::LastIndexOf($fullPath, '\\');
		}
		if ($index != -1)
		{
		    $ret = UtilStr::substr($fullPath, $index + 1);
		}
		else
		{
		    $ret = $fullPath;
		}

		$index = UtilStr::LastIndexOf($ret, '.');
		
		if ($index != -1)
		{
		    $ret = UtilStr::substr($ret, 0, $index);
		}

		return $ret;
	}

	// 获取文件路径，没有文件名字
	static public function getFilePathNoName(string fullPath)
	{
		int index = fullPath.LastIndexOf('/');
		string ret = "";

		if (index == -1)
		{
			index = fullPath.LastIndexOf('\\');
		}
		if (index != -1)
		{
			ret = fullPath.Substring(0, index);
		}
		else
		{
			ret = fullPath;
		}

		return ret;
	}

	// 获取文件路径，没有文件名字扩展
	static public function getFilePathNoExt(string fullPath)
	{
		int index = 0;
		string ret = fullPath;
		index = fullPath.LastIndexOf('.');

		if (index != -1)
		{
			ret = fullPath.Substring(0, index);
		}

		return ret;
	}

	// 获取当前文件的父目录名字
	static public function getFileParentDirName(string fullPath)
	{
		string parentDir = "";
		int lastSlashIndex = -1;

		// 如果是文件
		if (UtilFileIO.existFile(fullPath))
		{
			lastSlashIndex = fullPath.LastIndexOf("/");

			if(-1 == lastSlashIndex)
			{
				lastSlashIndex = fullPath.LastIndexOf("\\");
			}

			if (-1 == lastSlashIndex)
			{
				parentDir = "";
			}
			else
			{
				fullPath = fullPath.Substring(0, lastSlashIndex);

				lastSlashIndex = fullPath.LastIndexOf("/");

				if (-1 == lastSlashIndex)
				{
					lastSlashIndex = fullPath.LastIndexOf("\\");
				}

				if (-1 == lastSlashIndex)
				{
					parentDir = fullPath;
				}
				else
				{
					parentDir = fullPath.Substring(lastSlashIndex + 1, fullPath.Length - (lastSlashIndex + 1));
				}
			}
		}
		else
		{
			lastSlashIndex = fullPath.LastIndexOf("/");

			if (-1 == lastSlashIndex)
			{
				lastSlashIndex = fullPath.LastIndexOf("\\");
			}

			if (-1 == lastSlashIndex)
			{
				parentDir = "";
			}
			else
			{
				parentDir = fullPath.Substring(lastSlashIndex + 1, fullPath.Length - (lastSlashIndex + 1));
			}
		}

		return parentDir;
	}

	// 搜索文件夹中的文件
	static public function getAllFile(string path, MList<string> includeExtList = null, MList<string> excludeExtList = null, bool recursion = false)
	{
		DirectoryInfo dir = new DirectoryInfo(path);
		MList<string> fileList = new MList<string>();

		string extName = "";
		FileInfo[] allFile = dir.GetFiles();
		foreach (FileInfo file in allFile)
		{
			extName = UtilFileIO.getFileExt(file.FullName);
			if (includeExtList != null && includeExtList.IndexOf(extName) != -1)
			{
				fileList.Add(normalPath(file.FullName));
			}
			else if(excludeExtList != null && excludeExtList.IndexOf(extName) == -1)
			{
				fileList.Add(normalPath(file.FullName));
			}
			else if(includeExtList == null && excludeExtList == null)
			{
				fileList.Add(normalPath(file.FullName));
			}
		}

		if (recursion)
		{
			DirectoryInfo[] allDir = dir.GetDirectories();
			foreach (DirectoryInfo dirItem in allDir)
			{
				fileList.merge(getAllFile(dirItem.FullName, includeExtList, excludeExtList, recursion));
			}
		}
		return fileList;
	}

	// 递归拷贝目录
	static public void copyDirectory(string srcPath, string destPath, bool isRecurse = false)
	{
		DirectoryInfo sourceDirInfo = new DirectoryInfo(srcPath);
		DirectoryInfo targetDirInfo = new DirectoryInfo(destPath);

		if (targetDirInfo.FullName.StartsWith(sourceDirInfo.FullName, StringComparison.CurrentCultureIgnoreCase))
		{
			Debug.Log("UtilFileIO::copyDirectory, error, destPath is srcPath subDir, can not copy");
			return;
		}

		if (!sourceDirInfo.Exists)
		{
			return;
		}

		if (!targetDirInfo.Exists)
		{
			targetDirInfo.Create();
		}

		FileInfo[] files = sourceDirInfo.GetFiles();

		for (int i = 0; i < files.Length; i++)
		{
			UtilFileIO.copyFile(files[i].FullName, targetDirInfo.FullName + "/" + files[i].Name, true);
		}

		DirectoryInfo[] dirs = sourceDirInfo.GetDirectories();

		if (isRecurse)
		{
			for (int j = 0; j < dirs.Length; j++)
			{
				copyDirectory(dirs[j].FullName, targetDirInfo.FullName + "/" + dirs[j].Name, isRecurse);
			}
		}
	}

	static public void traverseDirectory(
		string srcPath,
		string destPath,
		MAction3<string, string, string> dirHandle = null,
		MAction3<string, string, string> fileHandle = null,
		bool isRecurse = false,
		bool isCreateDestPath = false
		)
	{
		DirectoryInfo sourceDirInfo = new DirectoryInfo(srcPath);
		DirectoryInfo targetDirInfo = null;

		// 如果不是目录规则的字符串，执行 new DirectoryInfo(destPath); 会报错
		if (!string.IsNullOrEmpty(destPath))
		{
			targetDirInfo = new DirectoryInfo(destPath);

			if (targetDirInfo.FullName.StartsWith(sourceDirInfo.FullName, StringComparison.CurrentCultureIgnoreCase))
			{
				Debug.Log("UtilFileIO::traverseDirectory, error, destPath is srcPath subDir, can not copy");
				return;
			}
		}

		if (!sourceDirInfo.Exists)
		{
			return;
		}

		if (!string.IsNullOrEmpty(destPath))
		{
			if (!UtilFileIO.existDirectory(destPath) && isCreateDestPath)
			{
				UtilFileIO.createDirectory(destPath);
				targetDirInfo = new DirectoryInfo(destPath);
			}
		}

		if (dirHandle != null)
		{
			if (string.IsNullOrEmpty(destPath))
			{
				dirHandle(sourceDirInfo.FullName, sourceDirInfo.Name, "");
			}
			else
			{
				dirHandle(sourceDirInfo.FullName, sourceDirInfo.Name, targetDirInfo.FullName);
			}
		}

		FileInfo[] files = sourceDirInfo.GetFiles();

		for (int i = 0; i < files.Length; i++)
		{
			if (fileHandle != null)
			{
				if (string.IsNullOrEmpty(destPath))
				{
					fileHandle(files[i].FullName, files[i].Name, "");
				}
				else
				{
					fileHandle(files[i].FullName, files[i].Name, targetDirInfo.FullName);
				}
			}
		}

		DirectoryInfo[] dirs = sourceDirInfo.GetDirectories();

		if (isRecurse)
		{
			for (int j = 0; j < dirs.Length; j++)
			{
				if (string.IsNullOrEmpty(destPath))
				{
					traverseDirectory(dirs[j].FullName, "", dirHandle, fileHandle, isRecurse, isCreateDestPath);
				}
				else
				{
					traverseDirectory(dirs[j].FullName, targetDirInfo.FullName + "/" + dirs[j].Name, dirHandle, fileHandle, isRecurse, isCreateDestPath);
				}
			}
		}
	}

	static public void deleteFiles(string srcPath, MList<string> fileList, MList<string> extNameList, bool isRecurse = false)
	{
		DirectoryInfo fatherFolder = new DirectoryInfo(srcPath);
		//删除当前文件夹内文件
		FileInfo[] files = fatherFolder.GetFiles();
		string extName = "";

		foreach (FileInfo file in files)
		{
			string fileName = file.Name;

			if (fileList != null)
			{
				if (fileList.IndexOf(fileName) != -1)
				{
					UtilFileIO.deleteFile(file.FullName);
				}
			}
			if (extNameList != null)
			{
				extName = UtilFileIO.getFileExt(file.FullName);
				if (extNameList.IndexOf(extName) != -1)
				{
					UtilFileIO.deleteFile(file.FullName);
				}
			}
		}
		if (isRecurse)
		{
			//递归删除子文件夹内文件
			foreach (DirectoryInfo childFolder in fatherFolder.GetDirectories())
			{
				deleteFiles(childFolder.FullName, fileList, extNameList, isRecurse);
			}
		}
	}

	// 递归删除所有的文件和目录
	static public void deleteSubDirsAndFiles(string curDir, MList<string> excludeDirList, MList<string> excludeFileList)
	{
		DirectoryInfo fatherFolder = new DirectoryInfo(curDir);
		//删除当前文件夹内文件
		FileInfo[] files = fatherFolder.GetFiles();
		string normalPath = "";

		foreach (FileInfo file in files)
		{
			string fileName = file.Name;
			normalPath = UtilFileIO.normalPath(file.FullName);
			if (!UtilFileIO.isEqualStrInList(normalPath, excludeFileList))
			{
				UtilFileIO.deleteFile(file.FullName);
			}
		}

		// 递归删除子文件夹内文件
		foreach (DirectoryInfo childFolder in fatherFolder.GetDirectories())
		{
			normalPath = UtilFileIO.normalPath(childFolder.FullName);
			if(!UtilFileIO.isEqualStrInList(normalPath, excludeDirList))
			{
				if (UtilFileIO.isSubStrInList(normalPath, excludeDirList) && !UtilFileIO.isSubStrInList(normalPath, excludeFileList))
				{
					UtilFileIO.deleteDirectory(childFolder.FullName, true);
				}
				else
				{
					UtilFileIO.deleteSubDirsAndFiles(childFolder.FullName, excludeDirList, excludeFileList);
				}
			}
		}
	}

	// 字符串是否是子串
	static public bool isSubStrInList(string str, MList<string> list)
	{
		bool ret = false;

		int idx = 0;
		int len = 0;

		if(list != null)
		{
			idx = 0;
			len = list.length();

			while(idx < len)
			{
				if(list[idx].IndexOf(str) != -1)
				{
					ret = true;
					break;
				}

				++idx;
			}
		}

		return ret;
	}

	static public bool isEqualStrInList(string str, MList<string> list)
	{
		bool ret = false;

		int idx = 0;
		int len = 0;

		if (list != null)
		{
			idx = 0;
			len = list.length();

			while (idx < len)
			{
				if (list[idx] == str)
				{
					ret = true;
					break;
				}

				++idx;
			}
		}

		return ret;
	}

	// 大写转换成小写
	static public string toLower(string src)
	{
		return src.ToLower();
	}

	// 递归创建子目录
	public static void recureCreateSubDir(string rootPath, string subPath, bool includeLast = false)
	{
		subPath = normalPath(subPath);
		if (!includeLast)
		{
			if (subPath.IndexOf('/') == -1)
			{
				return;
			}
			subPath = subPath.Substring(0, subPath.LastIndexOf('/'));
		}

		if (UtilFileIO.existDirectory(UtilFileIO.combine(rootPath, subPath)))
		{
			return;
		}

		int startIdx = 0;
		int splitIdx = 0;
		while ((splitIdx = subPath.IndexOf('/', startIdx)) != -1)
		{
			if (!UtilFileIO.existDirectory(UtilFileIO.combine(rootPath, subPath.Substring(0, startIdx + splitIdx))))
			{
				UtilFileIO.createDirectory(UtilFileIO.combine(rootPath, subPath.Substring(0, startIdx + splitIdx)));
			}

			startIdx += splitIdx;
			startIdx += 1;
		}

		UtilFileIO.createDirectory(UtilFileIO.combine(rootPath, subPath));
	}

	static public string getCurrentDirectory()
	{
		string curPath = System.Environment.CurrentDirectory;
		curPath = UtilFileIO.normalPath(curPath);

		return curPath;
	}

	// 去掉文件扩展名字，文件判断后缀是否是指定后缀
	static public bool isFileNameSuffixNoExt(string path, string suffix)
	{
		path = UtilFileIO.normalPath(path);

		bool ret = false;

		int dotIdx = 0;
		dotIdx = path.LastIndexOf(UtilFileIO.DOT);

		if (-1 != dotIdx)
		{
			path = path.Substring(0, dotIdx);
		}

		int slashIdx = 0;
		slashIdx = path.LastIndexOf(UtilFileIO.SLASH);

		if (-1 != slashIdx)
		{
			path = path.Substring(slashIdx + 1);
		}

		if (path.Length > suffix.Length)
		{
			if (path.Substring(path.Length - suffix.Length, suffix.Length) == suffix)
			{
				ret = true;
			}
		}

		return ret;
	}

	// 去掉文件扩展名字，然后再去掉文件后缀
	static public string getFileNameRemoveSuffixNoExt(string path, string suffix)
	{
		path = UtilFileIO.normalPath(path);

		string ret = path;

		int dotIdx = 0;
		dotIdx = path.LastIndexOf(UtilFileIO.DOT);

		if (-1 != dotIdx)
		{
			path = path.Substring(0, dotIdx);
		}

		int slashIdx = 0;
		slashIdx = path.LastIndexOf(UtilFileIO.SLASH);

		if (-1 != slashIdx)
		{
			path = path.Substring(slashIdx + 1);
		}

		if (path.Length > suffix.Length)
		{
			if (path.Substring(path.Length - suffix.Length, suffix.Length) == suffix)
			{
				ret = path.Substring(0, path.Length - suffix.Length);
			}
		}

		return ret;
	}

	// 删除指定目录下所有类似的文件
	static public void deleteAllSearchPatternFile(string fileFullName, MSearchOption searchOption = MSearchOption.eTopDirectoryOnly)
	{
		// ref https://msdn.microsoft.com/zh-cn/library/wz42302f.aspx
		string[] fileList = Directory.GetFiles(fileFullName, "*", (SearchOption)searchOption);

		int index = 0;
		int listLen = fileList.Length;

		while(index < listLen)
		{
			 UtilFileIO.deleteFile(fileList[index]);

			index += 1;
		}
	}
}

?>