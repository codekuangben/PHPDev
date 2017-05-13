using System;
using System.Collections;
using System.IO;
using System.Text;
using UnityEngine;

namespace SDK.Lib
{
/**
 * @brief 仅支持本地文件操作，仅支持同步操作
 */
public class MDataStream : GObject, IDispatchObject
{
	public enum eFilePlatformAndPath
	{
		eResourcesPath = 0,                 // Resources 文件夹下的文件操作
		eAndroidStreamingAssetsPath = 1,    // Android 平台下 StreamingAssetsPath 目录下，只有 Android 的 StreamingAssets 这个目录必须需要 WWW 才能读取，其它的平台所有的目录都可以使用 File 系统读取操作
		eOther,                             // 其它
	}

	public enum eFileOpState
	{
		eNoOp = 0,      // 无操作
		eOpening = 1,   // 打开中
		eOpenSuccess = 2,   // 打开成功
		eOpenFail = 3,      // 打开失败
		eOpenClose = 4,     // 关闭
	}

	public FileStream mFileStream;
	protected WWW mWWW;

	protected string mFilePath;
	protected FileMode mMode;
	protected FileAccess mAccess;
	protected eFileOpState mFileOpState;

	protected eFilePlatformAndPath mFilePlatformAndPath;

	protected bool mIsSyncMode;
	protected AddOnceAndCallOnceEventDispatch mOpenedEvtDisp;   // 文件打开结束分发，主要是 WWW 是异步读取本地文件的，因此需要确保文件被打开成功

	protected string mText;
	protected byte[] mBytes;

	protected MAction<IDispatchObject> mOpenedDispatch;

	/**
	 * @brief 仅支持同步操作，目前无视参数 isSyncMode 和 evtDisp。FileMode.CreateNew 如果文件已经存在就抛出异常，FileMode.Append 和 FileAccess.Write 要同时使用
	 */
	public MDataStream(string filePath, MAction<IDispatchObject> openedDisp = null, FileMode mode = FileMode.Open, FileAccess access = FileAccess.Read, bool isSyncMode = true)
	{
		$this->mTypeId = "MDataStream";

		$this->mFilePath = filePath;
		$this->mMode = mode;
		$this->mAccess = access;
		$this->mFileOpState = eFileOpState.eNoOp;
		$this->mIsSyncMode = isSyncMode;
		$this->mOpenedDispatch = openedDisp;

		$this->checkPlatformAndPath(mFilePath);
	}

	public void open()
	{
		$this->checkAndOpen($this->mOpenedDispatch);
	}

	public void seek(long offset, SeekOrigin origin)
	{
		if(mFileOpState == eFileOpState.eOpenSuccess)
		{
			if(isResourcesFile())
			{

			}
			else if (isWWWStream())
			{

			}
			else
			{
				mFileStream.Seek(offset, origin);
			}
		}
	}

	public void addOpenedHandle(MAction<IDispatchObject> openedDisp = null)
	{
		if (mOpenedEvtDisp == null)
		{
			mOpenedEvtDisp = new AddOnceAndCallOnceEventDispatch();
		}

		mOpenedEvtDisp.addEventHandle(null, openedDisp);
	}

	public void dispose()
	{
		close();
	}

	public void checkPlatformAndPath(string path)
	{
		if($this->checkResourcesFile())
		{
			$this->mFilePlatformAndPath = eFilePlatformAndPath.eResourcesPath;
		}
		else if (UtilPath.isAndroidRuntime() && UtilPath.isStreamingAssetsPath(path))
		{
			$this->mFilePlatformAndPath = eFilePlatformAndPath.eAndroidStreamingAssetsPath;
		}
		else
		{
			$this->mFilePlatformAndPath = eFilePlatformAndPath.eOther;
		}
	}

	protected bool checkResourcesFile()
	{
		if ($this->mFilePath.Substring(0, MFileSys.msDataStreamResourcesPath.Length) == MFileSys.msDataStreamResourcesPath)
		{
			$this->mFilePath = mFilePath.Substring(MFileSys.msDataStreamResourcesPath.Length + 1);
			return true;
		}

		return false;
	}

	public bool isResourcesFile()
	{
		if($this->mFilePlatformAndPath == eFilePlatformAndPath.eResourcesPath)
		{
			return true;
		}

		return false;
	}

	public bool isWWWStream()
	{
		//return mFilePlatformAndPath == eFilePlatformAndPath.eAndroidStreamingAssetsPath ||
		//       mFilePlatformAndPath == eFilePlatformAndPath.eOther;

		return $this->mFilePlatformAndPath == eFilePlatformAndPath.eAndroidStreamingAssetsPath;
	}

	protected void syncOpenFileStream()
	{
		if ($this->mFileOpState == eFileOpState.eNoOp)
		{
			$this->mFileOpState = eFileOpState.eOpening;

			if(!$this->isWWWStream())
			{
				try
				{
					$this->mFileStream = new FileStream($this->mFilePath, $this->mMode, $this->mAccess);
					$this->mFileOpState = eFileOpState.eOpenSuccess;
				}
				catch(Exception exp)
				{
					if (MacroDef.ENABLE_ERROR)
					{
						Ctx.mInstance.mLogSys.error(string.Format("MDataStream::syncOpenFileStream, error, path = {0}, error message = {1}", $this->mFilePath, exp.Message), LogTypeId.eErrorFillIO);
					}

					$this->mFileOpState = eFileOpState.eOpenFail;
				}
			}

			$this->onAsyncOpened();
		}
	}

	// 异步打开
	public IEnumerator asyncWWWStreamingAssetOpen()
	{
		if ($this->mFileOpState == eFileOpState.eNoOp)
		{
			$this->mFileOpState = eFileOpState.eOpening;

			if ($this->isWWWStream())
			{
				// Android 平台
				mWWW = new WWW(mFilePath);   // 同步加载资源
				yield return mWWW;

				if(UtilApi.isWWWNoError(mWWW))
				{
					$this->mFileOpState = eFileOpState.eOpenSuccess;
				}
				else
				{
					$this->mFileOpState = eFileOpState.eOpenFail;
				}

				$this->onAsyncOpened();
			}
			else
			{
				yield break;
			}
		}

		yield break;
	}

	public void syncOpenResourcesFile()
	{
		if ($this->mFileOpState == eFileOpState.eNoOp)
		{
			$this->mFileOpState = eFileOpState.eOpening;

			TextAsset textAsset = null;
			try
			{
				string fileNoExt = UtilPath.getFilePathNoExt(mFilePath);
				textAsset = Resources.Load<TextAsset>(fileNoExt);

				if (textAsset != null)
				{
					$this->mFileOpState = eFileOpState.eOpenSuccess;

					$this->mText = textAsset.text;
					$this->mBytes = textAsset.bytes;
					Resources.UnloadAsset(textAsset);
				}
				else
				{
					$this->mFileOpState = eFileOpState.eOpenFail;
				}
			}
			catch (Exception exp)
			{
				if (MacroDef.ENABLE_ERROR)
				{
					Ctx.mInstance.mLogSys.error(string.Format("MDataStream::syncOpenResourcesFile, error, path = {0}, error message = {1}", $this->mFilePath, exp.Message), LogTypeId.eErrorFillIO);
				}

				$this->mFileOpState = eFileOpState.eOpenFail;
			}

			$this->onAsyncOpened();
		}
	}

	public IEnumerator asyncOpenResourcesFile()
	{
		if ($this->mFileOpState == eFileOpState.eNoOp)
		{
			$this->mFileOpState = eFileOpState.eOpening;

			TextAsset textAsset = null;
			//try
			//{
			string fileNoExt = UtilPath.getFilePathNoExt(mFilePath);
			ResourceRequest req = null;
			req = Resources.LoadAsync<TextAsset>(fileNoExt);
			yield return req;

			if (req != null && req.isDone)
			{
				textAsset = req.asset as TextAsset;
				if (textAsset != null)
				{
					$this->mFileOpState = eFileOpState.eOpenSuccess;

					$this->mText = textAsset.text;
					$this->mBytes = textAsset.bytes;
					Resources.UnloadAsset(textAsset);
				}
				else
				{
					$this->mFileOpState = eFileOpState.eOpenFail;
				}
			}
			//}
			//catch (Exception exp)
			//{
			//    mFileOpState = eFileOpState.eOpenFail;

			//}

			$this->onAsyncOpened();
		}
	}

	// 异步打开结束
	public void onAsyncOpened()
	{
		if ($this->mOpenedEvtDisp != null)
		{
			$this->mOpenedEvtDisp.dispatchEvent(this);
		}
	}

	protected void checkAndOpen(MAction<IDispatchObject> openedDisp = null)
	{
		if (openedDisp != null)
		{
			$this->addOpenedHandle(openedDisp);
		}

		if ($this->mFileOpState == eFileOpState.eNoOp)
		{
			if($this->isResourcesFile())
			{
				if($this->mIsSyncMode)
				{
					// 同步模式
					$this->syncOpenResourcesFile();
				}
				else
				{
					// 异步模式
					Ctx.mInstance.mCoroutineMgr.StartCoroutine($this->asyncOpenResourcesFile());
				}
			}
			else if ($this->isWWWStream())
			{
				Ctx.mInstance.mCoroutineMgr.StartCoroutine($this->asyncWWWStreamingAssetOpen());
			}
			else
			{
				$this->syncOpenFileStream();
			}
		}
	}

	public bool isValid()
	{
		return $this->mFileOpState == eFileOpState.eOpenSuccess;
	}

	// 获取总共长度
	public int getLength()
	{
		int len = 0;

		if ($this->mFileOpState == eFileOpState.eOpenSuccess)
		{
			if($this->isResourcesFile())
			{
				if ($this->mText != null)
				{
					len = $this->mText.Length;
				}
				else if(mBytes != null)
				{
					len = $this->mBytes.Length;
				}
			}
			else if ($this->isWWWStream())
			{
				if ($this->mWWW != null)
				{
					len = $this->mWWW.size;
				}
			}
			else
			{
				if ($this->mFileStream != null)
				{
					len = (int)$this->mFileStream.Length;
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
		}

		return len;
	}

	protected void close()
	{
		if ($this->mFileOpState == eFileOpState.eOpenSuccess)
		{
			if ($this->isResourcesFile())
			{

			}
			else if ($this->isWWWStream())
			{
				if($this->mWWW != null)
				{
					$this->mWWW.Dispose();
					$this->mWWW = null;
				}
			}
			else
			{
				if ($this->mFileStream != null)
				{
					$this->mFileStream.Close();
					$this->mFileStream.Dispose();
					$this->mFileStream = null;
				}
			}

			$this->mFileOpState = eFileOpState.eOpenClose;
			$this->mFileOpState = eFileOpState.eNoOp;
		}
	}

	public string readText(int offset = 0, int count = 0, Encoding encode = null)
	{
		$this->checkAndOpen();

		string retStr = "";
		byte[] bytes = null;

		if (encode == null)
		{
			encode = Encoding.UTF8;
		}

		if (count == 0)
		{
			count = getLength();
		}

		if ($this->mFileOpState == eFileOpState.eOpenSuccess)
		{
			if ($this->isResourcesFile())
			{
				retStr = mText;
			}
			else if ($this->isWWWStream())
			{
				if (UtilApi.isWWWNoError($this->mWWW))
				{
					if ($this->mWWW.text != null)
					{
						retStr = mWWW.text;
					}
					else if ($this->mWWW.bytes != null)
					{
						retStr = encode.GetString(bytes);
					}
				}
			}
			else
			{
				if ($this->mFileStream.CanRead)
				{
					try
					{
						bytes = new byte[count];
						$this->mFileStream.Read(bytes, 0, count);

						retStr = encode.GetString(bytes);
					}
					catch (Exception err)
					{
						if (MacroDef.ENABLE_ERROR)
						{
							Ctx.mInstance.mLogSys.error(string.Format("MDataStream::readText, error, path = {0}, error message = {1}", $this->mFilePath, err.Message), LogTypeId.eErrorFillIO);
						}
					}
				}
			}
		}

		return retStr;
	}

	public byte[] readByte(int offset = 0, int count = 0)
	{
		$this->checkAndOpen();

		if (count == 0)
		{
			count = $this->getLength();
		}

		byte[] bytes = null;

		if ($this->isResourcesFile())
		{
			bytes = $this->mBytes;
		}
		else if ($this->isWWWStream())
		{
			if (UtilApi.isWWWNoError(mWWW))
			{
				if ($this->mWWW.bytes != null)
				{
					bytes = $this->mWWW.bytes;
				}
			}
		}
		else
		{
			if ($this->mFileStream.CanRead)
			{
				try
				{
					bytes = new byte[count];
					$this->mFileStream.Read(bytes, 0, count);
				}
				catch (Exception err)
				{
					if (MacroDef.ENABLE_ERROR)
					{
						Ctx.mInstance.mLogSys.error(string.Format("MDataStream::readByte, error, path = {0}, error message = {1}", $this->mFilePath, err.Message), LogTypeId.eErrorFillIO);
					}
				}
			}
		}

		return bytes;
	}

	//public void writeText(string text, Encoding encode = null)
	public void writeText(string text, GkEncode gkEncode = GkEncode.eUTF8)
	{
		Encoding encode = UtilApi.convGkEncode2EncodingEncoding(gkEncode);

		$this->checkAndOpen();

		if ($this->isResourcesFile())
		{

		}
		else if ($this->isWWWStream())
		{
			
		}
		else
		{
			if ($this->mFileStream.CanWrite)
			{
				//if (encode == null)
				//{
				//    encode = GkEncode.UTF8;
				//}

				byte[] bytes = encode.GetBytes(text);

				if (bytes != null)
				{
					try
					{
						$this->mFileStream.Write(bytes, 0, bytes.Length);
					}
					catch (Exception err)
					{
						if (MacroDef.ENABLE_ERROR)
						{
							Ctx.mInstance.mLogSys.error(string.Format("MDataStream::writeText, error, path = {0}, error message = {1}", $this->mFilePath, err.Message), LogTypeId.eErrorFillIO);
						}
					}
				}
			}
		}
	}

	public void writeByte(byte[] bytes, int offset = 0, int count = 0)
	{
		$this->checkAndOpen();

		if ($this->isResourcesFile())
		{

		}
		else if ($this->isWWWStream())
		{
			
		}
		else
		{
			if ($this->mFileStream.CanWrite)
			{
				if (bytes != null)
				{
					if (count == 0)
					{
						count = bytes.Length;
					}

					if (count != 0)
					{
						try
						{
							$this->mFileStream.Write(bytes, offset, count);
						}
						catch (Exception err)
						{
							if (MacroDef.ENABLE_ERROR)
							{
								Ctx.mInstance.mLogSys.error(string.Format("MDataStream::writeByte, error, path = {0}, error message = {1}", $this->mFilePath, err.Message), LogTypeId.eErrorFillIO);
							}
						}
					}
				}
			}
		}
	}

	//public void writeLine(string text, Encoding encode = null)
	public void writeLine(string text, GkEncode gkEncode = GkEncode.eUTF8)
	{
		text = text + UtilApi.CR_LF;
		//writeText(text, encode);
		$this->writeText(text, gkEncode);
	}
}
}