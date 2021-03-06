<?php

namespace MyLibs\Thread;

use MyLibs\FrameWork\Ctx;

/**
 *@brief 基本的线程
 *@url https://www.cnblogs.com/jkko123/p/6351604.html
 */
class MThread /*extends Thread*/
{
	protected static $msMainThreadId;           // 主线程 id
	protected $mCurThreadId;                    // 当前线程的 id

	// 数据区域
	protected $mEventHandle;
	protected $mParam;           // 参数数据
	protected $mIsExitFlag;           // 退出标志

	public function __construct($func, $param)
	{
	    $this->mEventHandle = $func;
	    $this->mParam = $param;
	}

	public function isExitFlag()
	{
	    return $this->mIsExitFlag;
	}
	
	public function setIsExitFlag($value)
	{
	    $this->mIsExitFlag = value;
	}

	public function setHandle($value)
	{
	    $this->mEventHandle = $value;
	}

	public function setParam($value)
	{
	    $this->mParam = $value;
	}

	// 函数区域
	/**
	 *@brief 开启一个线程
	 */
	public function start()
	{
		$this->start();
	}

	public function join()
	{
	    $this->Join();
	}

	/**
	 *@brief 线程回调函数
	 */
	public function run()
	{
		$this->getCurThreadId();

		if($this->mEventHandle != null)
		{
		    $this->mEventHandle($this->mParam);
		}
	}

	protected function getCurThreadId()
	{
		//$this->mCurThreadId = Thread::$CurrentThread->ManagedThreadId;       // 当前线程的 ID
	}

	public function isCurThread($threadId)
	{
		return ($this->mCurThreadId == $threadId);
	}

	static public function getMainThreadId()
	{
	    //MThread::$msMainThreadId = Thread::$CurrentThread->ManagedThreadId;
	}

	static public function isMainThread()
	{
	    //return (MThread::msMainThreadId == Thread::$CurrentThread->ManagedThreadId);
	}

	static public function needMainThread()
	{
	    if (!MThread::isMainThread())
		{
			Ctx::$msIns->mLogSys->error("error: log out in other thread");
			throw new \Exception("cannot call function in thread");
		}
	}

	/**
	 * @ref http://php.net/manual/en/function.microtime.php 
	 */
	static public function Sleep($millisecondsTimeout)
	{
		usleep($millisecondsTimeout);
	}
}

?>