namespace SDK.Lib
{
public class UtilStr
{
	static public void removeLastCR(ref string srcStr)
	{
		if(!string.IsNullOrEmpty(srcStr))
		{
			if(srcStr[srcStr.Length - 1] == Symbolic.CR)
			{
				srcStr = srcStr.Substring(0, srcStr.Length - 1);
			}
		}
	}

	//static public void split(ref string str, params string[] param)
	// 仅支持一个符号分割
	static public string[] split(ref string str, char splitSymbol)
	{
		char[] split = new char[1];
		split[0] = splitSymbol;
		string[] strArr = null;

		if (!string.IsNullOrEmpty(str))
		{
			strArr = str.Split(split);
		}

		return strArr;
	}

	// 计算字符最后出现的位置，仅支持一个字符， string::LastIndexOf 比较耗时，好像要进入本地代码执行
	static public int LastIndexOf(ref string str, char findStr)
	{
		int lastIndex = -1;
		int index = str.Length - 1; 

		while (index >= 0)
		{
			if(str[index] == findStr)
			{
				lastIndex = index;
				break;
			}

			--index;
		}

		return lastIndex;
	}

	static public int IndexOf(ref string str, char findStr)
	{
		int retIndex = -1;
		int index = 0;
		int len = str.Length;

		while (index < len)
		{
			if (str[index] == findStr)
			{
				retIndex = index;
				break;
			}

			index += 1;
		}

		return retIndex;
	}

	static public string toStringByCount(int count, string str)
	{
		if(count < 0)
		{
			count = 0;
		}

		string ret = "";
		System.Text.StringBuilder stringBuilder = new System.Text.StringBuilder();

		int index = 0;

		while(index < count)
		{
			stringBuilder.Append(str);

			index += 1;
		}

		ret = stringBuilder.ToString();

		return ret;
	}

	static public string formatFloat(float a, int b)
	{
		string s = a.ToString("F" + b);
		return s;
	}
}
}