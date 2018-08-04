<?php

namespace SDK\Lib;

/**
 * @brief 互斥
 */
class MMutex
{
	private $mMutex; 	// 读互斥
	private $mName;	// name

	public function __construct($initiallyOwned, $name)
	{
		if (MacroDef::NET_MULTHREAD)
		{
			// IOS 下不支持，错误提示如下： "Named mutexes are not supported"
			//mMutex = new Mutex(initiallyOwned, name);
			$this->mMutex = new Mutex($initiallyOwned);
			$this->mName = $name;
		}
	}

	public function WaitOne()
	{
		if (MacroDef::NET_MULTHREAD)
		{
		    $this->mMutex.WaitOne();
		}
	}

	public function ReleaseMutex()
	{
		if (MacroDef::NET_MULTHREAD)
		{
		    $this->mMutex.ReleaseMutex();
		}
	}

	public function close()
	{
		if (MacroDef::NET_MULTHREAD)
		{
		    $this->mMutex.Close();
		}
	}
}

?>