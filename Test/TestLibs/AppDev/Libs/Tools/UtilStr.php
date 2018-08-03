<?php

namespace SDK\Lib;

/**
 * @url http://www.w3school.com.cn/php/php_ref_string.asp
 * @url https://zhidao.baidu.com/question/504459056.html
 */
class UtilStr
{
    public static function IsNullOrEmpty($srcStr)
    {
        return (null == $srcStr || "" == $srcStr);
    }
    
    public static function length($srcStr)
    {
        return strlen($srcStr);
    }
    
    /**
     *@url http://www.w3school.com.cn/php/func_string_substr.asp
     */
    public static function substr($srcStr, $start, $length)
    {
        return substr($srcStr, $start, $length);
    }
    
    public static function removeLastCR($srcStr)
	{
	    if(!UtilStr::IsNullOrEmpty($srcStr))
		{
		    if(srcStr[strlen($srcStr) - 1] == Symbolic::CR)
			{
			    $srcStr = UtilStr::substr($srcStr, 0, strlen($srcStr) - 1);
			}
		}
	}

	//static public void split(ref string str, params string[] param)
	// 仅支持一个符号分割
	public static function split($srcStr, $splitSymbol)
	{
		$strArr = null;

		if (!UtilStr::IsNullOrEmpty($srcStr))
		{
		    $strArr = explode($splitSymbol, $srcStr);
		}

		return $strArr;
	}

	// 计算字符最后出现的位置，仅支持一个字符， string::LastIndexOf 比较耗时，好像要进入本地代码执行
	public static function LastIndexOf($srcStr, $findStr)
	{
		$lastIndex = -1;
		$index = strlen($srcStr) - 1; 

		while ($index >= 0)
		{
		    if($srcStr[$index] == $findStr)
			{
				$lastIndex = $index;
				break;
			}

			--$index;
		}

		return $lastIndex;
	}

	public static function IndexOf($srcStr, $findStr)
	{
		$retIndex = -1;
		$index = 0;
		$len = strlen($srcStr);

		while ($index < $len)
		{
		    if ($srcStr[$index] == $findStr)
			{
				$retIndex = $index;
				break;
			}

			$index += 1;
		}

		return $retIndex;
	}

	public static function toStringByCount($count, $srcStr)
	{
	    if($count < 0)
	    {
	        $count = 0;
	    }
	    
	    $ret = "";
	    $index = 0;
	    
	    while($index < $count)
	    {
	        $ret = $ret . $srcStr;

	        $index += 1;
	    }

	    return $ret;
	}

	public static function formatFloat($a, $b)
	{
	    $s = number_format($a, $b);
		return $s;
	}
	
	public static function replace($srcStr, $findStr, $replaceStr, $count)
	{
	    if(null != $count)
	    {
    	    return str_replace($findStr, $replaceStr, $srcStr, $count);
	    }
	    else
	    {
	        return str_replace($findStr, $replaceStr, $srcStr);
	    }
	}
	
	public static function Format($formatStr, $var0, $var1, $var2, $var3, $var4, $var5, $var6, $var7, $var8, $var9)
	{
	    if(null != $var0 && 
	       null != $var1 && 
	       null != $var2 && 
	       null != $var3 &&
	       null != $var4 &&
	       null != $var5 && 
	       null != $var6 && 
	       null != $var7 &&
	       null != $var8 &&
	       null != $var9)
	    {
	        $formatStr = UtilStr::replace($formatStr, "{0}", $var0);
	        $formatStr = UtilStr::replace($formatStr, "{1}", $var1);
	        $formatStr = UtilStr::replace($formatStr, "{2}", $var2);
	        $formatStr = UtilStr::replace($formatStr, "{3}", $var3);
	        $formatStr = UtilStr::replace($formatStr, "{4}", $var4);
	        $formatStr = UtilStr::replace($formatStr, "{5}", $var5);
	        $formatStr = UtilStr::replace($formatStr, "{6}", $var6);
	        $formatStr = UtilStr::replace($formatStr, "{7}", $var7);
	        $formatStr = UtilStr::replace($formatStr, "{8}", $var8);
	        $formatStr = UtilStr::replace($formatStr, "{9}", $var9);
	    }
	    else if(null != $var0 &&
	            null != $var1 &&
	            null != $var2 &&
	            null != $var3 &&
	            null != $var4 &&
	            null != $var5 &&
	            null != $var6 &&
	            null != $var7 &&
	            null != $var8)
	    {
	        $formatStr = UtilStr::replace($formatStr, "{0}", $var0);
	        $formatStr = UtilStr::replace($formatStr, "{1}", $var1);
	        $formatStr = UtilStr::replace($formatStr, "{2}", $var2);
	        $formatStr = UtilStr::replace($formatStr, "{3}", $var3);
	        $formatStr = UtilStr::replace($formatStr, "{4}", $var4);
	        $formatStr = UtilStr::replace($formatStr, "{5}", $var5);
	        $formatStr = UtilStr::replace($formatStr, "{6}", $var6);
	        $formatStr = UtilStr::replace($formatStr, "{7}", $var7);
	        $formatStr = UtilStr::replace($formatStr, "{8}", $var8);
	    }
	    else if(null != $var0 &&
	            null != $var1 &&
	            null != $var2 &&
	            null != $var3 &&
	            null != $var4 &&
	            null != $var5 &&
	            null != $var6 &&
	            null != $var7)
	    {
	        $formatStr = UtilStr::replace($formatStr, "{0}", $var0);
	        $formatStr = UtilStr::replace($formatStr, "{1}", $var1);
	        $formatStr = UtilStr::replace($formatStr, "{2}", $var2);
	        $formatStr = UtilStr::replace($formatStr, "{3}", $var3);
	        $formatStr = UtilStr::replace($formatStr, "{4}", $var4);
	        $formatStr = UtilStr::replace($formatStr, "{5}", $var5);
	        $formatStr = UtilStr::replace($formatStr, "{6}", $var6);
	        $formatStr = UtilStr::replace($formatStr, "{7}", $var7);
	    }
	    else if(null != $var0 &&
	            null != $var1 &&
	            null != $var2 &&
	            null != $var3 &&
	            null != $var4 &&
	            null != $var5 &&
	            null != $var6)
	    {
	        $formatStr = UtilStr::replace($formatStr, "{0}", $var0);
	        $formatStr = UtilStr::replace($formatStr, "{1}", $var1);
	        $formatStr = UtilStr::replace($formatStr, "{2}", $var2);
	        $formatStr = UtilStr::replace($formatStr, "{3}", $var3);
	        $formatStr = UtilStr::replace($formatStr, "{4}", $var4);
	        $formatStr = UtilStr::replace($formatStr, "{5}", $var5);
	        $formatStr = UtilStr::replace($formatStr, "{6}", $var6);
	    }
	    else if(null != $var0 &&
	            null != $var1 &&
	            null != $var2 &&
	            null != $var3 &&
	            null != $var4 &&
	            null != $var5)
	    {
	        $formatStr = UtilStr::replace($formatStr, "{0}", $var0);
	        $formatStr = UtilStr::replace($formatStr, "{1}", $var1);
	        $formatStr = UtilStr::replace($formatStr, "{2}", $var2);
	        $formatStr = UtilStr::replace($formatStr, "{3}", $var3);
	        $formatStr = UtilStr::replace($formatStr, "{4}", $var4);
	        $formatStr = UtilStr::replace($formatStr, "{5}", $var5);
	    }
	    else if(null != $var0 &&
	            null != $var1 &&
	            null != $var2 &&
	            null != $var3 &&
	             null != $var4)
	    {
	        $formatStr = UtilStr::replace($formatStr, "{0}", $var0);
	        $formatStr = UtilStr::replace($formatStr, "{1}", $var1);
	        $formatStr = UtilStr::replace($formatStr, "{2}", $var2);
	        $formatStr = UtilStr::replace($formatStr, "{3}", $var3);
	        $formatStr = UtilStr::replace($formatStr, "{4}", $var4);
	    }
	    else if(null != $var0 &&
	            null != $var1 &&
	            null != $var2 &&
	            null != $var3)
	    {
	        $formatStr = UtilStr::replace($formatStr, "{0}", $var0);
	        $formatStr = UtilStr::replace($formatStr, "{1}", $var1);
	        $formatStr = UtilStr::replace($formatStr, "{2}", $var2);
	        $formatStr = UtilStr::replace($formatStr, "{3}", $var3);
	    }
	    else if(null != $var0 &&
	            null != $var1 &&
	            null != $var2)
	    {
	        $formatStr = UtilStr::replace($formatStr, "{0}", $var0);
	        $formatStr = UtilStr::replace($formatStr, "{1}", $var1);
	        $formatStr = UtilStr::replace($formatStr, "{2}", $var2);
	    }
	    else if(null != $var0 &&
	            null != $var1)
	    {
	        $formatStr = UtilStr::replace($formatStr, "{0}", $var0);
	        $formatStr = UtilStr::replace($formatStr, "{1}", $var1);
	    }
	    else if(null != $var0)
	    {
	        $formatStr = UtilStr::replace($formatStr, "{0}", $var0);
	    }
	    
	    return $formatStr;
	}
}

?>