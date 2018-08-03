<?php

namespace SDK\Lib;

class UtilLogic
{
	// 判断一个 unicode 字符是不是汉字
	public static bool IsChineseLetter(string input, int index)
	{
		int code = 0;
		int chfrom = System.Convert.ToInt32("4e00", 16); //范围（0x4e00～0x9fff）转换成int（chfrom～chend）
		int chend = System.Convert.ToInt32("9fff", 16);
		if (input != "")
		{
			code = System.Char.ConvertToUtf32(input, index); //获得字符串input中指定索引index处字符unicode编码

			if (code >= chfrom && code <= chend)
			{
				return true; //当code在中文范围内返回true
			}
			else
			{
				return false; //当code不在中文范围内返回false
			}
		}
		return false;
	}

	public static bool IsIncludeChinese(string input)
	{
		int idx = 0;
		for (idx = 0; idx < input.Length; ++idx)
		{
			if (IsChineseLetter(input, idx))
			{
				return true;
			}
		}

		return false;
	}

	// 判断 unicode 字符个数，只判断字母和中文吗，中文算 2 个字节
	public static int CalcCharCount(string str)
	{
		int charCount = 0;
		int idx = 0;
		for (idx = 0; idx < str.Length; ++idx)
		{
			if (IsChineseLetter(str, idx))
			{
				charCount += 2;
			}
			else
			{
				charCount += 1;
			}
		}

		return charCount;
	}

	// 从数字获取 5 位字符串
	public static string get5StrFromDigit(int digit)
	{
		string ret = "";
		if (digit < 10)
		{
			ret = string.Format("{0}{1}", "0000", digit.ToString());
		}
		else if (digit < 100)
		{
			ret = string.Format("{0}{1}", "000", digit.ToString());
		}

		return ret;
	}

	// 格式化时间，显示格式为 00年00天00时00分00秒
	static public string formatTime(int second)
	{
		string ret = "";

		int left = 0;
		int year = second / (356 * 24 * 60 * 60);
		left = second % (356 * 24 * 60 * 60);
		int day = left / (24 * 60 * 60);
		left = left % (24 * 60 * 60);
		int hour = left / (60 * 60);
		left = left % (60 * 60);
		int min = left / 60;
		left = left % 60;
		int sec = left;

		if(year != 0)
		{
			ret = string.Format("{0}{1}年", ret, year);
		}
		if (day != 0)
		{
			ret = string.Format("{0}{1}天", ret, day);
		}
		if (hour != 0)
		{
			ret = string.Format("{0}{1}时", ret, hour);
		}
		if (min != 0)
		{
			ret = string.Format("{0}{1}分", ret, min);
		}
		if (sec != 0)
		{
			ret = string.Format("{0}{1}秒", ret, sec);
		}

		return ret;
	}

	public static float getSquare(float num)
	{
		return num * num;
	}
}

?>