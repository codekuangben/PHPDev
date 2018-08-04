<?php

namespace SDK\Lib;

class MEncode
{
	/**
	static public Encoding UTF8 = Encoding.UTF8;
	//static public Encoding GB2312 = Encoding.GetEncoding(936);         // GB2312 这个解码器在 mono 中是没有的，不能使用
	static public Encoding GB2312 = Encoding.UTF8;         // GB2312
	static public Encoding Unicode = Encoding.Unicode;
	static public Encoding Default = Encoding.Default;
	*/
	public const eUTF8 = 0;
	public const eGB2312 = 1;
	public const eUnicode = 2;
	public const eDefault = 3;
}

class MEncodeStr
{
    public const eUTF8Str = "UTF-8";
    public const eGB2312Str = "GBK";
    public const eUnicodeStr = "UTF-8";
    public const eDefaultStr = "UTF-8";
}

?>