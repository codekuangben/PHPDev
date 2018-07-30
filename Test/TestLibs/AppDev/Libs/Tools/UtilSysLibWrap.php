<?php

namespace SDK\Lib;

/**
 * @brief 对 api 的进一步 wrap 
 */
class UtilSysLibWrap
{
	public const TRUE = "true";
	public const FALSE = "false";

	//public static FAKE_POS = new Vector3(-1000, 0, -1000);  // 默认隐藏到这个位置

	public const CR_LF = "\n";       // 回车换行， Mac 下面即使写入 "\r\n"，读取出来后，也只有 "\n"，因此这里 Windows 下也只写入 "\n"，而不是 "\r\n"
	public const SEPARATOR = "=";    // 分隔符

	// 剔除字符串末尾的空格
	public static function trimEndSpace($str)
	{
	    $str.TrimEnd('\0');
	}

	// 判断两个 Object 地址是否相等
	public static function isAddressEqual($a, $b)
	{
		return $a === $b;
	}

	// 判断两个 Object 内容是否相等
	public static function isObjectEqual($a, $b)
	{
		return $a == $b;
	}

	// 判断两个函数是否相等，不能使用 isAddressEqual 判断函数是否相等
	public static function isDelegateEqual($a, $b)
	{
		return $a == $b;
	}

	// 判断向量是否相等
	public static function isVectorEqual($lhv, $rhv)
	{
		if (UnityEngine.Mathf.Abs(lhv.x - rhv.x) < 0.0001)
		{
			if (UnityEngine.Mathf.Abs(lhv.y - rhv.y) < 0.0001)
			{
				if (UnityEngine.Mathf.Abs(lhv.z - rhv.z) < 0.0001)
				{
					return true;
				}
			}
		}

		return false;
	}

	/**
	 * @brief 返回 UTC 秒
	 * @ref http://php.net/manual/en/function.microtime.php
	 */
	public static function getUTCSec()
	{
		return UtilSysLibWrap::microtime_float() / 1000;
	}

	// 返回 UTC 毫秒
	public static function getFloatUTCMilliseconds()
	{
		return UtilSysLibWrap::microtime_float();
	}

	// 获取当前时间的文本可读形式
	public static function getUTCFormatText()
	{
		return date('Y-m-d H:i:s');
	}
	
	/**
	 * @ref http://php.net/manual/en/function.microtime.php
	 */
	function microtime_float()
	{
		list($usec, $sec) = explode(" ", microtime());
		return ((float)$usec + (float)$sec);
	}
	
	/**
	 * @ref http://blog.csdn.net/qduningning/article/details/11939769
	 * 1.在php.ini中找到date.timezone，将它的值改成 Asia/Shanghai，即 date.timezone = Asia/Shanghai
	 * 2.在程序开始时添加 date_default_timezone_set('Asia/Shanghai')即可。 
	 */
	public static function setTimeZone($timeZone = 'Asia/Shanghai')
	{
		date_default_timezone_set($timeZone);
	}

	public static function Range($min, $max)
	{
		UnityEngine.Random.InitState((int)UtilSysLibWrap.getUTCSec());
		return UnityEngine.Random.Range(min, max);
	}

	// 欧拉角增加
	static public function incEulerAngles($degree, $delta)
	{
		return (degree + delta) % 360;
	}

	static public function decEulerAngles($degree, $delta)
	{
		return (degree - delta) % 360;
	}

	static public function assert($condition, $message = "")
	{
		Debug.Assert(condition, message);
	}

	static public function rangRandom($min, $max)
	{
		return UnityEngine.Random.Range(min, max);
	}

	static public function convGkEncode2EncodingEncoding($gkEncode)
	{
		$retEncode = System.Text.Encoding.UTF8;

		if (GkEncode.eUTF8 == gkEncode)
		{
			$retEncode = System.Text.Encoding.UTF8;
		}
		else if (GkEncode.eGB2312 == gkEncode)
		{
			$retEncode = System.Text.Encoding.UTF8;
		}
		else if (GkEncode.eUnicode == gkEncode)
		{
			$retEncode = System.Text.Encoding.Unicode;
		}
		else if (GkEncode.eDefault == gkEncode)
		{
			//$retEncode = System.Text.Encoding.Default;
		}

		return $retEncode;
	}

	// 转换资源目录到精灵名字
	static public function convFullPath2SpriteName($fullPath)
	{
		$spriteName = "";
		$lastSlashIndex = -1;
		$dotIndex = -1;

		$lastSlashIndex = UtilStr.LastIndexOf($fullPath, '/');

		if(-1 == $lastSlashIndex)
		{
			$lastSlashIndex = UtilStr.LastIndexOf($fullPath, '\\');
		}

		$dotIndex = UtilStr.LastIndexOf($fullPath, '.');
		
		if (-1 == $lastSlashIndex)
		{
			if (-1 == $dotIndex)
			{
				$spriteName = $fullPath;
			}
			else
			{
				$spriteName = $fullPath.Substring(0, $dotIndex);
			}
		}
		else
		{
			if (-1 == $dotIndex)
			{
				$spriteName = $fullPath.Substring($lastSlashIndex + 1, $fullPath.Length - $lastSlashIndex);
			}
			else
			{
				$spriteName = $fullPath.Substring($lastSlashIndex + 1, $dotIndex - ($lastSlashIndex + 1));
			}
		}

		return $spriteName;
	}
}

?>