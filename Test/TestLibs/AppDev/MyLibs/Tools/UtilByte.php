<?php

namespace MyLibs;

/**
 *@brief 字节编码解码，大端小端
 */
class UtilByte
{
	/**
	 *@brief 检查大端小端
	 */
    public static function checkEndian()
	{
		if(pack('L', 1) === pack('N', 1))
		{
		    SystemEndian::$msLocalEndian = MEndian::eBIG_ENDIAN;
		}
		else
		{
		    SystemEndian::$msLocalEndian = MEndian::eLITTLE_ENDIAN;
		}
	}

	// 两种编码的 string 字符串之间转换
	public static function convStr2Str($srcStr, $srcCharSet, $destCharSet)
	{
	    $srcEncodeStr = UtilSysLibWrap::convEncode2NativeEncodeStr($srcCharSet);
	    $destEncodeStr = UtilSysLibWrap::convEncode2NativeEncodeStr($destCharSet);
		
	    $retStr = iconv($srcEncodeStr, $destEncodeStr, $srcStr); 
		
		return $retStr;
	}
}

?>