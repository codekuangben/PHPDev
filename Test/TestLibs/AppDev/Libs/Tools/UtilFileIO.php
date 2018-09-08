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
	
	public static function isFile($path)
	{
	    return is_file($path);
	}
	
	public static function isDir($path)
	{
	    return is_dir($path);
	}
	
	public static function openDir($path)
	{
	    return opendir($path);
	}
	
	public static function closeDir($path)
	{
	    return closedir($path);
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
	static public function getFilePathNoName($fullPath)
	{
	    $index = UtilStr::LastIndexOf($fullPath, '/');
		$ret = "";

		if ($index == -1)
		{
		    $index = UtilStr::LastIndexOf($fullPath, '\\');
		}
		if ($index != -1)
		{
		    $ret = UtilStr::substr($fullPath, 0, $index);
		}
		else
		{
		    $ret = $fullPath;
		}

		return ret;
	}

	// 获取文件路径，没有文件名字扩展
	static public function getFilePathNoExt($fullPath)
	{
		$index = 0;
		$ret = $fullPath;
		$index = UtilStr::LastIndexOf($fullPath, '.');

		if ($index != -1)
		{
		    $ret = UtilStr::substr($fullPath, 0, $index);
		}

		return $ret;
	}

	// 获取当前文件的父目录名字
	static public function getFileParentDirName($fullPath)
	{
		$parentDir = "";
		$lastSlashIndex = -1;

		// 如果是文件
		if (UtilFileIO.existFile($fullPath))
		{
		    $lastSlashIndex = UtilStr::LastIndexOf($fullPath, "/");

			if(-1 == $lastSlashIndex)
			{
			    $lastSlashIndex = UtilStr::LastIndexOf($fullPath, "\\");
			}

			if (-1 == $lastSlashIndex)
			{
				$parentDir = "";
			}
			else
			{
			    $fullPath = UtilStr::substr($fullPath, 0, $lastSlashIndex);

				$lastSlashIndex = UtilStr::LastIndexOf($fullPath, "/");

				if (-1 == $lastSlashIndex)
				{
				    $lastSlashIndex = UtilStr::LastIndexOf($fullPath, "\\");
				}

				if (-1 == $lastSlashIndex)
				{
					$parentDir = $fullPath;
				}
				else
				{
				    $parentDir = UtilStr::substr($fullPath, lastSlashIndex + 1, UtilStr::length($fullPath) - (lastSlashIndex + 1));
				}
			}
		}
		else
		{
		    $lastSlashIndex = UtilStr::LastIndexOf($fullPath, "/");

			if (-1 == $lastSlashIndex)
			{
			    $lastSlashIndex = UtilStr::LastIndexOf($fullPath, "\\");
			}

			if (-1 == $lastSlashIndex)
			{
				$parentDir = "";
			}
			else
			{
			    $parentDir = UtilStr::substr($fullPath, $lastSlashIndex + 1, UtilStr::length($fullPath) - ($lastSlashIndex + 1));
			}
		}

		return $parentDir;
	}

	// 搜索文件夹中的文件
	// php获取目录下所有文件及目录（多种方法）
	// https://blog.csdn.net/FutureLilian/article/details/77937872
	static public function getAllFile($path, $includeExtList = null, $excludeExtList = null, $recursion = false)
	{
	    $handler = opendir($path);
	    $fileList = new MList();
	    
	    if(false !== $handler)
	    {
	        while( ($fileFullName = readdir($handler)) !== false ) 
	        {
	            if($fileFullName != "." && $fileFullName != ".." && UtilFileIO::isFile($fileFullName))
	            {
            		$extName = "";

        		    $extName = UtilFileIO.getFileExt($fileFullName);
        			
        		    if ($includeExtList != null && $includeExtList.IndexOf($extName) != -1)
        			{
        			    $fileList.Add(normalPath($fileFullName));
        			}
        			else if($excludeExtList != null && $excludeExtList.IndexOf($extName) == -1)
        			{
        			    $fileList.Add(normalPath($fileFullName));
        			}
        			else if($includeExtList == null && $excludeExtList == null)
        			{
        			    $fileList.Add(normalPath($fileFullName));
        			}
	            }
	        }
    
    		if ($recursion)
    		{
    		    $subDirArray = scandir($path);
    		    
    		    foreach ($subDirArray as $dirItem)
    			{
    			    if($dirItem != '.' && $dirItem != '..')
    			    {
    			        fileList.merge(getAllFile($dirItem, $includeExtList, $excludeExtList, $recursion));
    			    }
    			}
    		}
	    }
	    
		return fileList;
	}
	
	static public function getAllDirectory($path, $includeSubDirList = null, $excludeSubDirList = null, $recursion = false)
	{
	    $handler = opendir($path);
	    $fileList = new MList();
	    
	    if(false !== $handler)
	    {
	        while( ($fileFullName = readdir($handler)) !== false )
	        {
	            if($fileFullName != "." && $fileFullName != ".." && UtilFileIO::isDir($fileFullName))
	            {
	                $extName = "";
	                
	                $extName = UtilFileIO::getFileNameNoExt($fileFullName);
	                
	                if ($includeSubDirList != null && $includeSubDirList.IndexOf($extName) != -1)
	                {
	                    $fileList.Add(normalPath($fileFullName));
	                }
	                else if($excludeSubDirList != null && $excludeSubDirList.IndexOf($extName) == -1)
	                {
	                    $fileList.Add(normalPath($fileFullName));
	                }
	                else if($includeSubDirList == null && $includeSubDirList == null)
	                {
	                    $fileList.Add(normalPath($fileFullName));
	                }
	            }
	        }
	        
	        if ($recursion)
	        {
	            $subDirArray = scandir($path);
	            
	            foreach ($subDirArray as $dirItem)
	            {
	                if($dirItem != '.' && $dirItem != '..')
	                {
	                    fileList.merge(getAllFile($dirItem, $includeSubDirList, $excludeSubDirList, $recursion));
	                }
	            }
	        }
	    }
	    
	    return fileList;
	}

	// 递归拷贝目录
	static public function copyDirectory($srcPath, $destPath, $isRecurse = false)
	{
		if ($srcPath == $destPath)
		{
			echo("UtilFileIO::copyDirectory, error, destPath is srcPath subDir, can not copy");
			return;
		}

		if (!UtilFileIO::existDirectory($srcPath))
		{
			return;
		}

		if (!UtilFileIO::existDirectory($destPath))
		{
		    UtilFileIO::createDirectory($destPath);
		}

		$fileList = UtilFileIO::getAllFile($srcPath, null, null, false);

		for ($i = 0; $i < $fileList.Count(); $i++)
		{
		    UtilFileIO.copyFile($fileList.get($i), $destPath . "/" . UtilFileIO::getFileNameNoExt($fileList.get($i)), true);
		}

		if (isRecurse)
		{
		    $dirList = UtilFileIO::getAllFile($srcPath, null, null, false);
		    
		    for ($j = 0; $j < $dirList.Count(); $j++)
			{
			    copyDirectory($dirList.get(j), $destPath . "/" . UtilFileIO::getFileNameNoExt($dirList.get(j)), isRecurse);
			}
		}
	}

	static public function traverseDirectory(
		$srcPath,
		$destPath,
		$dirHandle = null,
		$fileHandle = null,
		$isRecurse = false,
		$isCreateDestPath = false
		)
	{
		// 如果不是目录规则的字符串，执行 new DirectoryInfo(destPath); 会报错
		if (!UtilStr::IsNullOrEmpty($destPath))
		{
			if ($srcPath == $destPath)
			{
				Debug.Log("UtilFileIO::traverseDirectory, error, destPath is srcPath subDir, can not copy");
				return;
			}
		}

		if (!UtilFileIO::existDirectory($srcPath))
		{
			return;
		}

		if (!UtilStr::IsNullOrEmpty($destPath))
		{
		    if (!UtilFileIO::existDirectory($destPath) && $isCreateDestPath)
			{
			    UtilFileIO::createDirectory($destPath);
			}
		}

		if (dirHandle != null)
		{
		    if (UtilStr::IsNullOrEmpty($destPath))
			{
			    dirHandle.call($srcPath, UtilFileIO::getFileNameNoExt($srcPath), "");
			}
			else
			{
			    dirHandle.call($srcPath, UtilFileIO::getFileNameNoExt($srcPath), $destPath);
			}
		}

		$fileList = UtilFileIO::getAllFile($srcPath);

		for ($i = 0; $i < $fileList.Count(); $i++)
		{
			if (fileHandle != null)
			{
				if (UtilStr::IsNullOrEmpty($destPath))
				{
				    fileHandle.call($fileList.get(i), UtilFileIO::getFileNameWithExt($fileList.get(i)), "");
				}
				else
				{
				    fileHandle.call($fileList.get(i), UtilFileIO::getFileNameWithExt($fileList.get(i)), $destPath);
				}
			}
		}

		if ($isRecurse)
		{
		    $dirList = UtilFileIO::getAllDirectory($srcPath);
		    
		    for ($j = 0; $j < $dirList.Count(); $j++)
			{
				if (UtilStr::IsNullOrEmpty($destPath))
				{
				    traverseDirectory($dirList.get($j), "", $dirHandle, $fileHandle, $isRecurse, $isCreateDestPath);
				}
				else
				{
				    traverseDirectory($dirList.get($j), $dirList.get($j) . "/" . UtilFileIO::getFileNameNoExt($dirList.get($j)), $dirHandle, $fileHandle, $isRecurse, $isCreateDestPath);
				}
			}
		}
	}

	static public function deleteFiles($srcPath, $fileList, $extNameList, $isRecurse = false)
	{
	    //删除当前文件夹内文件
	    $fileList = UtilFileIO::getAllFile($srcPath);
	    $extName = "";
	    $index = 0;
	    $listLen = $fileList.Count();
	    
	    while ($index < $listLen)
	    {
	        $fileName = UtilFileIO::getFileNameWithExt($fileList.get($index));
	        
	        if ($fileList != null)
	        {
	            if ($fileList.IndexOf($fileName) != -1)
	            {
	                UtilFileIO.deleteFile($fileList.get($index));
	            }
	        }
	        if ($extNameList != null)
	        {
	            $extName = UtilFileIO::getFileExt($fileList.get($index));
	            
	            if ($extNameList.IndexOf($extName) != -1)
	            {
	                UtilFileIO::deleteFile($fileList.get($index));
	            }
	        }
	        
	        $index += 1;
	    }
	    
	    if ($isRecurse)
	    {
	        $dirList = UtilFileIO::getAllDirectory($srcPath);
	        $index = 0;
	        $listLen = $dirList.Count();
	        
	        //递归删除子文件夹内文件
	        while ($index < $listLen)
	        {
	            deleteFiles($dirList.get($index), $fileList, $extNameList, $isRecurse);
	            $index += 1;
	        }
	    }
	}

	// 递归删除所有的文件和目录
	static public function deleteSubDirsAndFiles($curDir, $excludeDirList, $excludeFileList)
	{
		//删除当前文件夹内文件
	    $fileList = UtilFileIO::getAllFile($curDir);
		$normalPath = "";

		$index = 0;
		$listLen = $fileList.Count();
		
		while($index < $listLen)
		{
		    $fileName = UtilFileIO::getFileNameWithExt($fileList.get($index));
		    $normalPath = UtilFileIO::normalPath($fileList.get($index));
		    
		    if (!UtilFileIO::isEqualStrInList($normalPath, $excludeFileList))
			{
			    UtilFileIO::deleteFile($fileList.get($index));
			}
			
			$index += 1;
		}
		
		$dirList = UtilFileIO::getAllDirectory($srcPath);
		$index = 0;
		$listLen = $dirList.Count();

		// 递归删除子文件夹内文件
		while ($index < $listLen)
		{
		    $normalPath = UtilFileIO::normalPath($dirList.get($index));
			
			if(!UtilFileIO::isEqualStrInList($normalPath, $excludeDirList))
			{
				if (UtilFileIO::isSubStrInList($normalPath, $excludeDirList) && !UtilFileIO::isSubStrInList($normalPath, $excludeFileList))
				{
				    UtilFileIO::deleteDirectory($dirList.get($index), true);
				}
				else
				{
				    UtilFileIO::deleteSubDirsAndFiles($dirList.get($index), $excludeDirList, $excludeFileList);
				}
			}
			
			$index += 1;
		}
	}

	// 字符串是否是子串
	static public function isSubStrInList($str, $list)
	{
		$ret = false;

		$idx = 0;
		$len = 0;

		if($list != null)
		{
			$idx = 0;
			$len = $list.Count();

			while($idx < $len)
			{
				if($list[$idx].IndexOf($str) != -1)
				{
					$ret = true;
					break;
				}

				++$idx;
			}
		}

		return $ret;
	}

	static public function isEqualStrInList($str, $list)
	{
		$ret = false;

		$idx = 0;
		$len = 0;

		if ($list != null)
		{
			$idx = 0;
			$len = $list.length();

			while ($idx < $len)
			{
				if ($list[$idx] == $str)
				{
					$ret = true;
					break;
				}

				++$idx;
			}
		}

		return $ret;
	}

	// 递归创建子目录
	public static function recureCreateSubDir($rootPath, $subPath, $includeLast = false)
	{
	    $subPath = UtilFileIO::normalPath($subPath);
		
		if (!$includeLast)
		{
		    if (UtilStr::IndexOf($subPath, '/') == -1)
			{
				return;
			}
			
			$subPath = UtilStr::substr($subPath, 0, UtilStr::IndexOf($subPath, '/'));
		}

		if (UtilFileIO::existDirectory(UtilFileIO::combine($rootPath, $subPath)))
		{
			return;
		}

		$startIdx = 0;
		$splitIdx = 0;
		
		while (($splitIdx = UtilStr::IndexOf($subPath, '/', startIdx)) != -1)
		{
		    if (!UtilFileIO.existDirectory(UtilFileIO.combine($rootPath, UtilStr::substr($subPath, 0, $startIdx + $splitIdx))))
			{
			    UtilFileIO.createDirectory(UtilFileIO.combine($rootPath, UtilStr::substr($subPath, 0, $startIdx + $splitIdx)));
			}

			$startIdx += $splitIdx;
			$startIdx += 1;
		}

		UtilFileIO.createDirectory(UtilFileIO.combine($rootPath, $subPath));
	}

	static public function getCurrentDirectory()
	{
	    //$curPath = getcwd();
	    $curPath = $_SERVER['SCRIPT_FILENAME'];
		$curPath = UtilFileIO.normalPath($curPath);

		return $curPath;
	}

	// 去掉文件扩展名字，文件判断后缀是否是指定后缀
	static public function isFileNameSuffixNoExt($path, $suffix)
	{
		$path = UtilFileIO.normalPath($path);

		$ret = false;

		$dotIdx = 0;
		$dotIdx = $path.LastIndexOf(UtilFileIO::DOT);

		if (-1 != $dotIdx)
		{
		    $path = UtilStr::substr($path, 0, dotIdx);
		}

		$slashIdx = 0;
		$slashIdx = $path.LastIndexOf(UtilFileIO::SLASH);

		if (-1 != $slashIdx)
		{
		    $path = UtilStr::substr($path, $slashIdx + 1);
		}

		if (UtilStr::length($path) > UtilStr::length($suffix))
		{
		    if (UtilStr::substr($path, UtilStr::length($path) - UtilStr::length($suffix), UtilStr::length($suffix)) == $suffix)
			{
				$ret = true;
			}
		}

		return $ret;
	}

	// 去掉文件扩展名字，然后再去掉文件后缀
	static public function getFileNameRemoveSuffixNoExt($path, $suffix)
	{
		$path = UtilFileIO.normalPath($path);

		$ret = $path;

		$dotIdx = 0;
		$dotIdx = $path::LastIndexOf(UtilFileIO::DOT);

		if (-1 != $dotIdx)
		{
		    $path = UtilStr::substr($path, 0, dotIdx);
		}

		$slashIdx = 0;
		$slashIdx = UtilStr::LastIndexOf($path, UtilFileIO::SLASH);

		if (-1 != $slashIdx)
		{
		    $path = UtilStr::substr($path, $slashIdx + 1);
		}

		if (UtilStr::length($path) > UtilStr::length(suffix))
		{
		    if (UtilStr::substr($path, UtilStr::length($path) - UtilStr::length(suffix), UtilStr::length(suffix)) == suffix)
			{
			    $ret = UtilStr::substr($path, 0, UtilStr::length($path) - UtilStr::length(suffix));
			}
		}

		return $ret;
	}

	// 删除指定目录下所有类似的文件
	static public function deleteAllSearchPatternFile($fileFullName, $searchOption = MSearchOption::eTopDirectoryOnly)
	{
		// ref https://msdn.microsoft.com/zh-cn/library/wz42302f.aspx
		//string[] fileList = Directory.GetFiles(fileFullName, "*", (SearchOption)searchOption);

		//int index = 0;
		//int listLen = fileList.Length;

		//while(index < listLen)
		//{
		//	 UtilFileIO.deleteFile(fileList[index]);

		//	index += 1;
		//}
	}
}

?>