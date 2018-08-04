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
			//$this->mMutex = new Mutex($initiallyOwned);
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