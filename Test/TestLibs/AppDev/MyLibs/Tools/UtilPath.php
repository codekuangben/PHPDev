<?php

namespace MyLibs;

class MSearchOption
{
	public const eTopDirectoryOnly = 0;
	public const eAllDirectories = 1;
}

class UtilPath
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
	    if (UtilPath::existDirectory($path))
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
    			                    UtilPath::deleteDirectory($fullpath, $recursive);
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
			    echo(string.Format("UtilPath::DeleteDirectory, error, Error = {0}, path = {1}", $err->getMessage(), $path));
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
		    echo(UtilStr::Format("UtilPath::move error, ErrorMsg = {0}, srcPath = {1}, destPath = {2}", $$err->getMessage(), srcPath, destPath));
		}
	}

	// http://www.w3school.com.cn/php/func_filesystem_unlink.asp
	public static function deleteFile($path)
	{
	    $ret = true;
	    
	    if (UtilPath::existFile($path))
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
			    Debug.Log(string.Format("UtilPath::deleteFile, error, Path = {0}, ErrorMessage = {1}", path, $err->getMessage()));
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
		        if(UtilPath::existFile($destFileName))
		        {
		            UtilPath::deleteFile($destFileName);
		        }
		        
		        $ret = copy($sourceFileName, $destFileName, $overwrite);
		    }
		    else
		    {
		        if(!UtilPath::existFile($destFileName))
		        {
		            $ret = copy($sourceFileName, $destFileName, $overwrite);
		        }
		    }
		}
		catch (\Exception $err)
		{
		    $ret = false;
			echo(UtilStr::Format("UtilPath::copyFile, error, ErrorMsg = {0}, sourceFileName = {1}, destFileName = {2}", $err->getMessage(), sourceFileName, destFileName));
		}
		
		return $ret;
	}

	static public function createDirectory($pathAndName, $isRecurse = false)
	{
	    $ret = true;
	    
	    if ($isRecurse)
		{
		    $normPath = UtilPath::normalPath($pathAndName);
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
				echo(string.Format("UtilPath::CreateDirectory, error, ErrorMsg = {0}, pathAndName = {1}", $err->getMessage(), pathAndName));
			}
		}
	}

	static public function renameFile($srcPath, $destPath)
	{
		try
		{
			if (UtilPath::existFile(srcPath))
			{
			    UtilPath::move($srcPath, $destPath);
				return true;
			}
			else
			{
				return false;
			}
		}
		catch (\Exception $err)
		{
		    //Debug::Log(string::Format("UtilPath::renameFile, error, ErrorMsg = {0}, srcPath = {1}, destPath = {2}", $err->getMessage(), srcPath, destPath));
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

		return $ret;
	}

	// 获取扩展名
	static public function getFileExt($path)
	{
	    $ret = NULL;
	    $dotIdx = UtilStr::LastIndexOf($path, '.');

	    if (-1 != $dotIdx)
		{
		    $ret = UtilStr::substr($path, $dotIdx + 1, UtilStr::length($path) - ($dotIdx + 1));
		}

		return $ret;
	}

	// 获取文件名字，没有路径，但是有扩展名字
	static public function getFileNameWithExt($fullPath)
	{
	    $index = $fullPath->LastIndexOf('/');
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
		if (UtilPath::existFile($fullPath))
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
	            if($fileFullName != "." && $fileFullName != ".." && UtilPath::isFile($fileFullName))
	            {
            		$extName = "";

        		    $extName = UtilPath::getFileExt($fileFullName);
        			
        		    if ($includeExtList != null && $includeExtList->indexOf($extName) != -1)
        			{
        			    $fileList->add(normalPath($fileFullName));
        			}
        			else if($excludeExtList != null && $excludeExtList->indexOf($extName) == -1)
        			{
        			    $fileList->add(normalPath($fileFullName));
        			}
        			else if($includeExtList == null && $excludeExtList == null)
        			{
        			    $fileList->add(normalPath($fileFullName));
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
    			        $fileList->merge(getAllFile($dirItem, $includeExtList, $excludeExtList, $recursion));
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
	            if($fileFullName != "." && $fileFullName != ".." && UtilPath::isDir($fileFullName))
	            {
	                $extName = "";
	                
	                $extName = UtilPath::getFileNameNoExt($fileFullName);
	                
	                if ($includeSubDirList != null && $includeSubDirList->indexOf($extName) != -1)
	                {
	                    $fileList->add(normalPath($fileFullName));
	                }
	                else if($excludeSubDirList != null && $excludeSubDirList->indexOf($extName) == -1)
	                {
	                    $fileList->add(normalPath($fileFullName));
	                }
	                else if($includeSubDirList == null && $includeSubDirList == null)
	                {
	                    $fileList->add(normalPath($fileFullName));
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
	                    $fileList->merge(getAllFile($dirItem, $includeSubDirList, $excludeSubDirList, $recursion));
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
			echo("UtilPath::copyDirectory, error, destPath is srcPath subDir, can not copy");
			return;
		}

		if (!UtilPath::existDirectory($srcPath))
		{
			return;
		}

		if (!UtilPath::existDirectory($destPath))
		{
		    UtilPath::createDirectory($destPath);
		}

		$fileList = UtilPath::getAllFile($srcPath, null, null, false);

		for ($i = 0; $i < $fileList->count(); $i++)
		{
		    UtilPath::copyFile($fileList.get($i), $destPath . "/" . UtilPath::getFileNameNoExt($fileList.get($i)), true);
		}

		if (isRecurse)
		{
		    $dirList = UtilPath::getAllFile($srcPath, null, null, false);
		    
		    for ($j = 0; $j < $dirList->count(); $j++)
			{
			    copyDirectory($dirList->get(j), $destPath . "/" . UtilPath::getFileNameNoExt($dirList.get(j)), isRecurse);
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
				//Debug::Log("UtilPath::traverseDirectory, error, destPath is srcPath subDir, can not copy");
				return;
			}
		}

		if (!UtilPath::existDirectory($srcPath))
		{
			return;
		}

		if (!UtilStr::IsNullOrEmpty($destPath))
		{
		    if (!UtilPath::existDirectory($destPath) && $isCreateDestPath)
			{
			    UtilPath::createDirectory($destPath);
			}
		}

		if (dirHandle != null)
		{
		    if (UtilStr::IsNullOrEmpty($destPath))
			{
			    $dirHandle->call($srcPath, UtilPath::getFileNameNoExt($srcPath), "");
			}
			else
			{
			    $dirHandle->call($srcPath, UtilPath::getFileNameNoExt($srcPath), $destPath);
			}
		}

		$fileList = UtilPath::getAllFile($srcPath);

		for ($i = 0; $i < $fileList->count(); $i++)
		{
			if ($fileHandle != null)
			{
				if (UtilStr::IsNullOrEmpty($destPath))
				{
				    $fileHandle->call($fileList->get(i), UtilPath::getFileNameWithExt($fileList->get(i)), "");
				}
				else
				{
				    $fileHandle->call($fileList->get(i), UtilPath::getFileNameWithExt($fileList->get(i)), $destPath);
				}
			}
		}

		if ($isRecurse)
		{
		    $dirList = UtilPath::getAllDirectory($srcPath);
		    
		    for ($j = 0; $j < $dirList->count(); $j++)
			{
				if (UtilStr::IsNullOrEmpty($destPath))
				{
				    traverseDirectory($dirList->get($j), "", $dirHandle, $fileHandle, $isRecurse, $isCreateDestPath);
				}
				else
				{
				    traverseDirectory($dirList->get($j), $dirList.get($j) . "/" . UtilPath::getFileNameNoExt($dirList.get($j)), $dirHandle, $fileHandle, $isRecurse, $isCreateDestPath);
				}
			}
		}
	}

	static public function deleteFiles($srcPath, $fileList, $extNameList, $isRecurse = false)
	{
	    //删除当前文件夹内文件
	    $fileList = UtilPath::getAllFile($srcPath);
	    $extName = "";
	    $index = 0;
	    $listLen = $fileList->count();
	    
	    while ($index < $listLen)
	    {
	        $fileName = UtilPath::getFileNameWithExt($fileList->get($index));
	        
	        if ($fileList != null)
	        {
	            if ($fileList->indexOf($fileName) != -1)
	            {
	                UtilPath::deleteFile($fileList->get($index));
	            }
	        }
	        if ($extNameList != null)
	        {
	            $extName = UtilPath::getFileExt($fileList->get($index));
	            
	            if ($extNameList->indexOf($extName) != -1)
	            {
	                UtilPath::deleteFile($fileList->get($index));
	            }
	        }
	        
	        $index += 1;
	    }
	    
	    if ($isRecurse)
	    {
	        $dirList = UtilPath::getAllDirectory($srcPath);
	        $index = 0;
	        $listLen = $dirList->count();
	        
	        //递归删除子文件夹内文件
	        while ($index < $listLen)
	        {
	            deleteFiles($dirList->get($index), $fileList, $extNameList, $isRecurse);
	            $index += 1;
	        }
	    }
	}

	// 递归删除所有的文件和目录
	static public function deleteSubDirsAndFiles($curDir, $excludeDirList, $excludeFileList)
	{
		//删除当前文件夹内文件
	    $fileList = UtilPath::getAllFile($curDir);
		$normalPath = "";

		$index = 0;
		$listLen = $fileList->count();
		
		while($index < $listLen)
		{
		    $fileName = UtilPath::getFileNameWithExt($fileList->get($index));
		    $normalPath = UtilPath::normalPath($fileList->get($index));
		    
		    if (!UtilPath::isEqualStrInList($normalPath, $excludeFileList))
			{
			    UtilPath::deleteFile($fileList->get($index));
			}
			
			$index += 1;
		}
		
		$dirList = UtilPath::getAllDirectory($srcPath);
		$index = 0;
		$listLen = $dirList->count();

		// 递归删除子文件夹内文件
		while ($index < $listLen)
		{
		    $normalPath = UtilPath::normalPath($dirList->get($index));
			
			if(!UtilPath::isEqualStrInList($normalPath, $excludeDirList))
			{
				if (UtilPath::isSubStrInList($normalPath, $excludeDirList) && !UtilPath::isSubStrInList($normalPath, $excludeFileList))
				{
				    UtilPath::deleteDirectory($dirList->get($index), true);
				}
				else
				{
				    UtilPath::deleteSubDirsAndFiles($dirList->get($index), $excludeDirList, $excludeFileList);
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
			$len = $list->count();

			while($idx < $len)
			{
				if($list[$idx]->indexOf($str) != -1)
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
			$len = $list->length();

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
	    $subPath = UtilPath::normalPath($subPath);
		
		if (!$includeLast)
		{
		    if (UtilStr::indexOf($subPath, '/') == -1)
			{
				return;
			}
			
			$subPath = UtilStr::substr($subPath, 0, UtilStr::indexOf($subPath, '/'));
		}

		if (UtilPath::existDirectory(UtilPath::combine($rootPath, $subPath)))
		{
			return;
		}

		$startIdx = 0;
		$splitIdx = 0;
		
		while (($splitIdx = UtilStr::indexOf($subPath, '/', startIdx)) != -1)
		{
		    if (!UtilPath::existDirectory(UtilPath::combine($rootPath, UtilStr::substr($subPath, 0, $startIdx + $splitIdx))))
			{
			    UtilPath::createDirectory(UtilPath::combine($rootPath, UtilStr::substr($subPath, 0, $startIdx + $splitIdx)));
			}

			$startIdx += $splitIdx;
			$startIdx += 1;
		}

		UtilPath::createDirectory(UtilPath::combine($rootPath, $subPath));
	}

	static public function getCurrentDirectory()
	{
	    //$curPath = getcwd();
	    $curPath = $_SERVER['SCRIPT_FILENAME'];
		$curPath = UtilPath::normalPath($curPath);

		return $curPath;
	}

	// 去掉文件扩展名字，文件判断后缀是否是指定后缀
	static public function isFileNameSuffixNoExt($path, $suffix)
	{
		$path = UtilPath::normalPath($path);

		$ret = false;

		$dotIdx = 0;
		$dotIdx = $path->LastIndexOf(UtilPath::DOT);

		if (-1 != $dotIdx)
		{
		    $path = UtilStr::substr($path, 0, dotIdx);
		}

		$slashIdx = 0;
		$slashIdx = $path->LastIndexOf(UtilPath::SLASH);

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
		$path = UtilPath::normalPath($path);

		$ret = $path;

		$dotIdx = 0;
		$dotIdx = $path::LastIndexOf(UtilPath::DOT);

		if (-1 != $dotIdx)
		{
		    $path = UtilStr::substr($path, 0, dotIdx);
		}

		$slashIdx = 0;
		$slashIdx = UtilStr::LastIndexOf($path, UtilPath::SLASH);

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
		//int listLen = fileList->Length;

		//while(index < listLen)
		//{
		//	 UtilPath::deleteFile(fileList[index]);

		//	index += 1;
		//}
	}
}

?>