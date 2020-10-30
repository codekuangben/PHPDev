<?php

namespace MyLibs\Tools;

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
	    $str->TrimEnd('\0');
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
		if (abs($lhv->x - $rhv->x) < 0.0001)
		{
		    if (abs($lhv->y - $rhv->y) < 0.0001)
			{
			    if (abs($lhv->z - $rhv->z) < 0.0001)
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
		//UnityEngine::Random::InitState((int)UtilSysLibWrap->getUTCSec());
		//return UnityEngine::Random::Range(min, max);
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
		//Debug::Assert(condition, message);
	}

	public static function rangRandom($min, $max)
	{
		//return UnityEngine::Random::Range(min, max);
	}

	public static function convEncode2NativeEncodeStr($srcEncode)
	{
	    $retEncodeStr = MEncodeStr::eUTF8Str;

		if (MEncode::eUTF8 == $srcEncode)
		{
		    $retEncodeStr = MEncodeStr::eGB2312Str;
		}
		else if (MEncode::eGB2312 == $srcEncode)
		{
		    $retEncodeStr = MEncodeStr::eUTF8Str;
		}
		else if (MEncode::eUnicode == $srcEncode)
		{
		    $retEncodeStr = MEncodeStr::eUTF8Str;
		}
		else if (MEncode::eDefault == $srcEncode)
		{
		    //$retEncodeStr = System.Text.Encoding.Default;
		}

		return $retEncodeStr;
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
	
	public static function isBool($value)
	{
	    return is_bool($value);
	}
	
	public static function isInt($value)
	{
	    return is_int($value);
	}
	
	public static function isNumeric($value)
	{
	    return is_numeric($value);
	}
	
	public static function unserialize(string $str, array $options = null)
	{
	    return unserialize($str);
	}
	
	public static function serialize($value)
	{
	    return serialize($value);
	}
	
	public static function json_decode(string $json, bool $assoc = null, int $depth = null, int $options = null)
	{
	    return json_decode($json);
	}
	
	public static function jsonEncode($value, int $options = null, int $depth = null)
	{
	    return json_encode($value, $options, $depth);
	}
	
	public static function pregReplace($pattern, $replacement, $subject, int $limit = null, int &$count = null)
	{
	    return preg_replace($pattern, $replacement, $subject, $limit, $count);
	}
	
	public static function pregMatch(string $pattern, string $subject, array &$matches = null, int $flags = null, int $offset = null)
	{
	    return preg_match($pattern, $subject, $matches, $flags, $offset);
	}
	
	public static function die()
	{
	    die();
	}
	
	public static function print($str)
	{
	    print($str);
	}
	
	public static function echo($str)
	{
	    echo($str);
	}
	
	public static function print_r($str)
	{
	    print_r($str);
	}
	
	public static function issetInRequest($fieldName)
	{
	    return isset($_REQUEST[$fieldName]);
	}
	
	public static function request($fieldName)
	{
	    return $_REQUEST[$fieldName];
	}
	
	public static function issetInServer($fieldName)
	{
	    return isset($_SERVER[$fieldName]);
	}
	
	public static function issetInFiles($fieldName)
	{
	    return isset($_FILES[$fieldName]);
	}
	
	public static function fileName($fieldName)
	{
	    return $_FILES[$fieldName]['name'];
	}
	
	public static function fileType($fieldName)
	{
	    return $_FILES[$fieldName]['type'];
	}
	
	public static function fileSize($fieldName)
	{
	    return $_FILES[$fieldName]['size'];
	}
	
	public static function fileTmpName($fieldName)
	{
	    return $_FILES[$fieldName]['tmp_name'];
	}
	
	/**
	 * @brief PHP $_FILES函数详解
	 * @url https://www.cnblogs.com/laijinquan/p/8682282.html
	 * UPLOAD_ERR_OK 
     * 值：0; 没有错误发生，文件上传成功。 
     * UPLOAD_ERR_INI_SIZE 
     * 值：1; 上传的文件超过了 php.ini 中 upload_max_filesize 选项限制的值。 
     * UPLOAD_ERR_FORM_SIZE 
     * 值：2; 上传文件的大小超过了 HTML 表单中 MAX_FILE_SIZE 选项指定的值。 
     * UPLOAD_ERR_PARTIAL 
     * 值：3; 文件只有部分被上传。 
     * UPLOAD_ERR_NO_FILE 
     * 值：4; 没有文件被上传。 
     * 值：5; 上传文件大小为0.  
	 */
	public static function fileError($fieldName)
	{
	    return isset($_FILES[$fieldName]['error']);
	}
	
	public static function issetInCookie($key)
	{
	    return isset($_COOKIE[$key]);
	}
	
	public static function issetInEnv($key)
	{
	    return isset($_ENV[$key]);
	}
	
	public static function isWin()
	{
	    $ret = TRUE;
	    $ret = (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN');
	    return $ret;
	}
	
	public static function isLinux()
	{
	    $ret = TRUE;
	    $ret = (PATH_SEPARATOR == ':');
	    return $ret;
	}
}

?>