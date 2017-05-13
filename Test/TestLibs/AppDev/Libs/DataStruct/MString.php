<?php

namespace SDK\Lib;

/**
 * @brief 表示一个字符串，因为 string 的很多操作都会重新生成一个新的字符串，主要解决这个问题
 */
class MString
{
	protected $mNativeStr;    // 本地字符串
	protected $mStartIndex;      // 从 0 开始的索引
	protected $mStrLen;          // 长度

	public function __construct()
	{
		$this->mNativeStr = "";
		$this->mStartIndex = 0;
		$this->mStrLen = 0;
	}

	public function getNativeStr()
	{
		return $this->mNativeStr;
	}

	public function getStartIndex()
	{
		return $this->mStartIndex;
	}

	public function getStrLen()
	{
		return $this->mStrLen;
	}

	public function setNativeStr($value)
	{
		$this->mNativeStr = value;
	}

	public function setStartIndex($value)
	{
		$this->mStartIndex = value;
	}

	public function setStrLen($value)
	{
		$this->mStrLen = value;
	}

	// 回去内部表示的字符串
	public function getInterStr()
	{
		if ($this->mStrLen == $this->mNativeStr.Length)
		{
			return $this->mNativeStr;
		}
		else
		{
			return $this->mNativeStr.Substring($this->mStartIndex, $this->mStrLen);
		}
	}

	public function assign($str)
	{
		$this->mNativeStr = str;
		$this->mStartIndex = 0;
		$this->mStrLen = $this->mNativeStr.Length;
	}

	public function copyFrom($rhv)
	{
		$this->mNativeStr = rhv.getNativeStr();
		$this->mStartIndex = rhv.getStartIndex();
		$this->mStrLen = rhv.getStrLen();
	}

	public function IndexOf($findChar)
	{
		$retIndex = -1;
		$index = $this->mStartIndex;

		while (index < $this->mStrLen)
		{
			if ($this->mNativeStr[$this->mStartIndex + $index] == $findChar)
			{
				$retIndex = $index;
				break;
			}

			$index += 1;
		}

		return $retIndex;
	}

	public function LastIndexOf($findChar)
	{
		$lastIndex = -1;
		$index = $this->mStartIndex + $this->mStrLen - 1;

		while ($index >= 0)
		{
			if ($this->mNativeStr[$this->mStartIndex + $index] == $findChar)
			{
				$lastIndex = $index;
				break;
			}

			$index -= 1;
		}

		return $lastIndex;
	}

	public function Substring($startIndex)
	{
		$length = 0;

		$ret = new MString();
		$ret->copyFrom(this);

		if ($startIndex >= 0 && $startIndex < $this->mStrLen)
		{
			$ret->setStartIndex($this->mStartIndex + $startIndex);
			$length = $this->mStrLen - $startIndex;
		}
		else
		{
			$startIndex = 0;
		}

		if ($startIndex + $length <= $this->mStrLen)
		{
			$ret.setStrLen($length);
		}
		else
		{
			$ret->setStrLen($this->mStrLen - $startIndex);
		}

		return $ret;
	}

	public function Substring($startIndex, $length)
	{
		$ret = new MString();
		$ret->copyFrom(this);

		if ($startIndex >= 0 && $startIndex < $this->mStrLen)
		{
			$ret.setStartIndex($this->mStartIndex + $startIndex);
		}
		else
		{
			$startIndex = 0;
		}

		if ($startIndex + $length <= $this->mStrLen)
		{
			$ret->setStrLen($length);
		}
		else
		{
			$ret->setStrLen($this->mStrLen - $startIndex);
		}

		return $ret;
	}
}

?>