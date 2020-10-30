<?php

namespace MyLibs\Thread;

/**
 * @brief 单一对象同步
 */
class MCondition
{
	protected $mMutex;
	protected $mEvent;
	protected $mCanEnterWait;  // 是否可以进入等待

	public function __construct($name)
	{
		$this->mMutex = new MMutex(false, name);
		$this->mEvent = new MEvent(false);
		$this->mCanEnterWait = true;      // 允许进入等待状态
	}

	public function canEnterWait()
	{
		return $this->mCanEnterWait;
	}

	public function wait()
	{
	    $this->mMutex->WaitOne();
	    
	    if ($this->mCanEnterWait)
		{
		    $this->mMutex->ReleaseMutex();   // 这个地方需要释放锁，否则 notifyAll 进不来
		    $this->mEvent->WaitOne();
		    $this->mEvent->Reset();      // 重置信号
		}
		else
		{
		    $this->mCanEnterWait = true;
		    $this->mMutex->ReleaseMutex();
		}
	}

	public function notifyAll()
	{
	    $lock = new MLock($this->mMutex);
	    
		{
		    if ($this->mCanEnterWait) // 如果 mCanEnterWait == false，必然不能进入等待
			{
			    $this->mCanEnterWait = false;
			    $this->mEvent->Set();        // 唤醒线程
			}
		}
	}
}

?>