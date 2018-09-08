<?php

namespace SDK\Lib;

/**
 * @brief 锁操作
 */
class MLock
{
	protected $mMutex;

	public function __construct($mutex)
	{
		if (MacroDef::NET_MULTHREAD)
		{
			$this->mMutex = $mutex;
			$this->mMutex.WaitOne();
		}
	}

	// 这个在超出作用域的时候就会被调用，但是只有在使用 using 语句中，例如 using (MLock mlock = new MLock(mReadMutex)) ，这个语句执行完后立马调用，using (MLock mlock = new MLock(mReadMutex)) {} 才行
	public function Dispose()
	{
		if (MacroDef::NET_MULTHREAD)
		{
		    $this->ReleaseMutex();
		}
	}

	// 析构在垃圾回收的时候才会被调用
	//~MLock()
	//{
	//    mMutex.ReleaseMutex();
	//}

	//public void unlock()
	//{
	//    mMutex.ReleaseMutex();
	//}
}

?>