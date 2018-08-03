<?php

namespace SDK\Lib;

enum MSearchOption
{
	eTopDirectoryOnly = 0,
	eAllDirectories = 1,
}

public class UtilPath
{
	public const string DOT = ".";
	public const string SLASH = "/";

	public static string normalPath(string path)
	{
		return path.Replace('\\', '/');
	}

	// 删除目录的时候，一定要关闭这个文件夹，否则删除文件夹可能出错
	static public void deleteDirectory(string path, bool recursive = true)
	{
		if (Directory.Exists(path))
		{
			try
			{
				Directory.Delete(path, recursive);
			}
			catch (Exception err)
			{
				Debug.Log(string.Format("UtilPath::DeleteDirectory, error, Error = {0}, path = {1}", err.Message, path));
			}
		}
	}

	// 目录是否存在
	static public bool existDirectory(string path)
	{
		return Directory.Exists(path);
	}

	// 文件是否存在
	static public bool existFile(string path)
	{
		return File.Exists(path);
	}

	// 移动文件
	static public void move(string srcPath, string destPath)
	{
		try
		{
			File.Move(srcPath, destPath);
		}
		catch (Exception err)
		{
			Debug.Log(string.Format("UtilPath::move error, ErrorMsg = {0}, srcPath = {1}, destPath = {2}", err.Message, srcPath, destPath));
		}
	}

	public static bool deleteFile(string path)
	{
		if (UtilPath.existFile(path))
		{
			try
			{
				File.Delete(path);
			}
			catch (Exception err)
			{
				Debug.Log(string.Format("UtilPath::deleteFile, error, Path = {0}, ErrorMessage = {1}", path, err.Message));
			}
		}

		return true;
	}

	// destFileName 目标路径和文件名字
	public static void copyFile(string sourceFileName, string destFileName, bool overwrite = false)
	{
		try
		{
			File.Copy(sourceFileName, destFileName, overwrite);
		}
		catch (Exception err)
		{
			Debug.Log(string.Format("UtilPath::copyFile, error, ErrorMsg = {0}, sourceFileName = {1}, destFileName = {2}", err.Message, sourceFileName, destFileName));
		}
	}

	static public void createDirectory(string pathAndName, bool isRecurse = false)
	{
		if (isRecurse)
		{
			string normPath = normalPath(pathAndName);
			string[] pathArr = normPath.Split(new[] { '/' });

			string curCreatePath = "";
			int idx = 0;

			for (; idx < pathArr.Length; ++idx)
			{
				// Mac 下是以 ‘／’ 开头的，如果使用  '/' 分割字符串，就会出现字符长度为 0 的问题
				if (0 != pathArr[idx].Length)
				{
					if(curCreatePath.Length == 0)
					{
						curCreatePath = pathArr[idx];
					}
					else
					{
						curCreatePath = string.Format("{0}/{1}", curCreatePath, pathArr[idx]);
					}

					if (!Directory.Exists(curCreatePath))
					{
						try
						{
							Directory.CreateDirectory(curCreatePath);
						}
						catch(Exception err)
						{
							Debug.Log (string.Format ("UitlPath::CreateDirectory, error, ErrorMsg = {0}, path = {1}", err.Message, curCreatePath));
						}
					}
				}
			}
		}
		else
		{
			try
			{
				if (!Directory.Exists(pathAndName))
				{
					// 这个接口默认就支持创建所有没有的目录
					Directory.CreateDirectory(pathAndName);
				}
			}
			catch (Exception err)
			{
				Debug.Log(string.Format("UtilPath::CreateDirectory, error, ErrorMsg = {0}, pathAndName = {1}", err.Message, pathAndName));
			}
		}
	}

	static public bool renameFile(string srcPath, string destPath)
	{
		try
		{
			if (UtilPath.existFile(srcPath))
			{
				UtilPath.move(srcPath, destPath);
				return true;
			}
			else
			{
				return false;
			}
		}
		catch (Exception excep)
		{
			Debug.Log(string.Format("UtilPath::renameFile, error, ErrorMsg = {0}, srcPath = {1}, destPath = {2}", excep.Message, srcPath, destPath));
			return false;
		}
	}

	static public string combine(params string[] pathList)
	{
		int idx = 0;
		string ret = "";
		bool isFirst = true;
		StringBuilder stringBuilder = new StringBuilder();

		while (idx < pathList.Length)
		{
			if (pathList[idx].Length > 0)
			{
				//if(stringBuilder.ToString(stringBuilder.Length - 1, 1) != "/" || pathList[idx][pathList[idx].Length - 1] != '/')
				//{
				//    stringBuilder.Append("/");
				//}

				if(!isFirst)
				{
					stringBuilder.Append("/");
				}
				else
				{
					isFirst = false;
				}

				stringBuilder.Append(pathList[idx]);
			}

			idx += 1;
		}

		ret = stringBuilder.ToString();
		ret = ret.Replace("//", "/");

		return ret;
	}

	// 获取扩展名
	static public string getFileExt(string path)
	{
		int dotIdx = path.LastIndexOf('.');

		if (-1 != dotIdx)
		{
			return path.Substring(dotIdx + 1);
		}

		return "";
	}

	// 获取文件名字，没有路径，但是有扩展名字
	static public string getFileNameWithExt(string fullPath)
	{
		int index = fullPath.LastIndexOf('/');
		string ret = "";

		if (index == -1)
		{
			index = fullPath.LastIndexOf('\\');
		}
		if (index != -1)
		{
			ret = fullPath.Substring(index + 1);
		}
		else
		{
			ret = fullPath;
		}

		return ret;
	}

	// 获取文件名字，没有扩展名字
	static public string getFileNameNoExt(string fullPath)
	{
		int index = fullPath.LastIndexOf('/');
		string ret = "";

		if (index == -1)
		{
			index = fullPath.LastIndexOf('\\');
		}
		if (index != -1)
		{
			ret = fullPath.Substring(index + 1);
		}
		else
		{
			ret = fullPath;
		}

		index = ret.LastIndexOf('.');
		if (index != -1)
		{
			ret = ret.Substring(0, index);
		}

		return ret;
	}

	// 获取文件路径，没有文件名字
	static public string getFilePathNoName(string fullPath)
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
	static public string getFilePathNoExt(string fullPath)
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
	static public string getFileParentDirName(string fullPath)
	{
		string parentDir = "";
		int lastSlashIndex = -1;

		// 如果是文件
		if (UtilPath.existFile(fullPath))
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
	static public MList<string> getAllFile(string path, MList<string> includeExtList = null, MList<string> excludeExtList = null, bool recursion = false)
	{
		DirectoryInfo dir = new DirectoryInfo(path);
		MList<string> fileList = new MList<string>();

		string extName = "";
		FileInfo[] allFile = dir.GetFiles();
		foreach (FileInfo file in allFile)
		{
			extName = UtilPath.getFileExt(file.FullName);
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

	// 添加版本的文件名，例如 E:/aaa/bbb/ccc.txt?v=1024
	public static string versionPath(string path, string version)
	{
		if (!string.IsNullOrEmpty(version))
		{
			return string.Format("{0}?v={1}", path, version);
		}
		else
		{
			return path;
		}
	}

	// 删除所有除去版本号外相同的文件，例如 E:/aaa/bbb/ccc.txt?v=1024 ，只要 E:/aaa/bbb/ccc.txt 一样就删除，参数就是 E:/aaa/bbb/ccc.txt ，没有版本号的文件
	public static void delFileNoVer(string path)
	{
		path = normalPath(path);
		DirectoryInfo TheFolder = new DirectoryInfo(path.Substring(0, path.LastIndexOf('/')));
		FileInfo[] allFiles = TheFolder.GetFiles(string.Format("{0}*", path));
		foreach (var item in allFiles)
		{
			item.Delete();
		}
	}

	public static bool fileExistNoVer(string path)
	{
		path = normalPath(path);
		DirectoryInfo TheFolder = new DirectoryInfo(path.Substring(0, path.LastIndexOf('/')));
		FileInfo[] allFiles = TheFolder.GetFiles(string.Format("{0}*", path));

		return allFiles.Length > 0;
	}

	static public void saveTex2File(Texture2D tex, string filePath)
	{
		//将图片信息编码为字节信息
		byte[] bytes = tex.EncodeToPNG();
		//保存
		System.IO.File.WriteAllBytes(filePath, bytes);
	}

	static public void saveStr2File(string str, string filePath, Encoding encoding)
	{
		System.IO.File.WriteAllText(filePath, str, encoding);
	}

	static public void saveByte2File(string path, byte[] bytes)
	{
		System.IO.File.WriteAllBytes(path, bytes);
	}

	// 递归拷贝目录
	static public void copyDirectory(string srcPath, string destPath, bool isRecurse = false)
	{
		DirectoryInfo sourceDirInfo = new DirectoryInfo(srcPath);
		DirectoryInfo targetDirInfo = new DirectoryInfo(destPath);

		if (targetDirInfo.FullName.StartsWith(sourceDirInfo.FullName, StringComparison.CurrentCultureIgnoreCase))
		{
			Debug.Log("UtilPath::copyDirectory, error, destPath is srcPath subDir, can not copy");
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
			UtilPath.copyFile(files[i].FullName, targetDirInfo.FullName + "/" + files[i].Name, true);
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
				Debug.Log("UtilPath::traverseDirectory, error, destPath is srcPath subDir, can not copy");
				return;
			}
		}

		if (!sourceDirInfo.Exists)
		{
			return;
		}

		if (!string.IsNullOrEmpty(destPath))
		{
			if (!UtilPath.existDirectory(destPath) && isCreateDestPath)
			{
				UtilPath.createDirectory(destPath);
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
					UtilPath.deleteFile(file.FullName);
				}
			}
			if (extNameList != null)
			{
				extName = UtilPath.getFileExt(file.FullName);
				if (extNameList.IndexOf(extName) != -1)
				{
					UtilPath.deleteFile(file.FullName);
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
			normalPath = UtilPath.normalPath(file.FullName);
			if (!UtilPath.isEqualStrInList(normalPath, excludeFileList))
			{
				UtilPath.deleteFile(file.FullName);
			}
		}

		// 递归删除子文件夹内文件
		foreach (DirectoryInfo childFolder in fatherFolder.GetDirectories())
		{
			normalPath = UtilPath.normalPath(childFolder.FullName);
			if(!UtilPath.isEqualStrInList(normalPath, excludeDirList))
			{
				if (UtilPath.isSubStrInList(normalPath, excludeDirList) && !UtilPath.isSubStrInList(normalPath, excludeFileList))
				{
					UtilPath.deleteDirectory(childFolder.FullName, true);
				}
				else
				{
					UtilPath.deleteSubDirsAndFiles(childFolder.FullName, excludeDirList, excludeFileList);
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

	// 打包成 unity3d 后文件名字会变成小写，这里修改一下
	static public void modifyFileNameToCapital(string path, string fileNameNoExt)
	{
		string srcFullPath = string.Format("{0}/{1}.{2}", path, fileNameNoExt.ToLower(), UtilSysLibWrap.UNITY3D);
		string destFullPath = string.Format("{0}/{1}.{2}", path, fileNameNoExt, UtilSysLibWrap.UNITY3D);
		UtilPath.move(srcFullPath, destFullPath);

		srcFullPath = string.Format("{0}/{1}.{2}.manifest", path, fileNameNoExt.ToLower(), UtilSysLibWrap.UNITY3D);
		destFullPath = string.Format("{0}/{1}.{2}.manifest", path, fileNameNoExt, UtilSysLibWrap.UNITY3D);
		UtilPath.move(srcFullPath, destFullPath);
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

		if (UtilPath.existDirectory(UtilPath.combine(rootPath, subPath)))
		{
			return;
		}

		int startIdx = 0;
		int splitIdx = 0;
		while ((splitIdx = subPath.IndexOf('/', startIdx)) != -1)
		{
			if (!UtilPath.existDirectory(UtilPath.combine(rootPath, subPath.Substring(0, startIdx + splitIdx))))
			{
				UtilPath.createDirectory(UtilPath.combine(rootPath, subPath.Substring(0, startIdx + splitIdx)));
			}

			startIdx += splitIdx;
			startIdx += 1;
		}

		UtilPath.createDirectory(UtilPath.combine(rootPath, subPath));
	}

	// Android 运行时
	static public bool isAndroidRuntime()
	{
		return Application.platform == RuntimePlatform.Android;
	}

	// windows 运行时
	static public bool isWindowsRuntime()
	{
		return Application.platform == RuntimePlatform.WindowsPlayer || Application.platform == RuntimePlatform.WindowsEditor;
	}

	// 是否是 StreamingAssetsPath 目录
	static public bool isStreamingAssetsPath(string path)
	{
		path = UtilPath.normalPath(path);
		return path.IndexOf(MFileSys.msDataStreamStreamingAssetsPath) == 0;
	}

	static public string getCurrentDirectory()
	{
		string curPath = System.Environment.CurrentDirectory;
		curPath = UtilPath.normalPath(curPath);

		return curPath;
	}

	// 去掉文件扩展名字，文件判断后缀是否是指定后缀
	static public bool isFileNameSuffixNoExt(string path, string suffix)
	{
		path = UtilPath.normalPath(path);

		bool ret = false;

		int dotIdx = 0;
		dotIdx = path.LastIndexOf(UtilPath.DOT);

		if (-1 != dotIdx)
		{
			path = path.Substring(0, dotIdx);
		}

		int slashIdx = 0;
		slashIdx = path.LastIndexOf(UtilPath.SLASH);

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
		path = UtilPath.normalPath(path);

		string ret = path;

		int dotIdx = 0;
		dotIdx = path.LastIndexOf(UtilPath.DOT);

		if (-1 != dotIdx)
		{
			path = path.Substring(0, dotIdx);
		}

		int slashIdx = 0;
		slashIdx = path.LastIndexOf(UtilPath.SLASH);

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

	// 通过文件名字和版本检查文件是否存在
	static public bool checkFileFullNameExistByVersion(string fileFullName, string version)
	{
		bool ret = false;

		string path = UtilPath.versionPath(fileFullName, version);
		ret = UtilPath.existFile(path);

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
			 UtilPath.deleteFile(fileList[index]);

			index += 1;
		}
	}
}

?>