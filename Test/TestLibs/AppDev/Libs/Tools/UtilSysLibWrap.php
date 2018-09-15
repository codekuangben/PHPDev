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
	public static function microtime_float()
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
	public static function incEulerAngles($degree, $delta)
	{
		return (degree + delta) % 360;
	}

	public static function decEulerAngles($degree, $delta)
	{
		return (degree - delta) % 360;
	}

	public static function assert($condition, $message = "")
	{
		Debug.Assert(condition, message);
	}

	public static function rangRandom($min, $max)
	{
		return UnityEngine.Random.Range(min, max);
	}

	public static function convEncode2NativeEncodeStr($srcEncode)
	{
	    $retEncodeStr = MEncodeStr::eUTF8Str;

		if (MEncode::eUTF8 == $srcEncode)
		{
		    $retEncodeStr = MEncodeStr::eGB2312Str;
		}
		else if (MEncode.eGB2312 == $srcEncode)
		{
		    $retEncodeStr = MEncodeStr::eUTF8Str;
		}
		else if (MEncode.eUnicode == $srcEncode)
		{
		    $retEncodeStr = MEncodeStr::eUTF8Str;
		}
		else if (MEncode.eDefault == $srcEncode)
		{
		    //$retEncodeStr = System.Text.Encoding.Default;
		}

		return $retEncode;
	}
	
	public static function isset($param)
	{
	    isset($param);
	}
	
	public static function unset($param)
	{
	    unset($param);
	}
	
	public static function dumpVar($param)
	{
	    var_dump($param);
	}
	
	public static function setShangHaiTimeZone()
	{
	    UtilSysLibWrap::setDefaultTimeZone('Asia/Shanghai');
	}
	
	public static function setDefaultTimeZone($value)
	{
	    date_default_timezone_set($value);
	}
	
	public static function set_time_limit($value)
	{
	    set_time_limit($value);
	}
	
	public static function ignore_user_abort($value)
	{
	    ignore_user_abort($value);
	}
}

?>