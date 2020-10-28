<?php

namespace MyLibs;

/**
 * @brief 任务线程
 */
class TaskThread extends MThread
{
	protected $mTaskQueue;
	protected $mCondition;
	protected $mCurTask;

	public function __construct($name, $taskQueue)
	{
	    parent::__construct(null, null);
	    
		$this->mTaskQueue = $taskQueue;
		$this->mCondition = new MCondition($name);
	}

	/**
	 *brief 线程回调函数
	 */
	public function threadHandle()
	{
	    while (!$this->mIsExitFlag)
		{
		    $this->mCurTask = $this->mTaskQueue->pop();
		    
		    if($this->mCurTask != null)
			{
			    $this->mCurTask->runTask();
			}
			else
			{
			    $this->mCondition->wait();
			}
		}
	}

	public function notifySelf()
	{
	    if($this->mCondition->canEnterWait)
		{
		    $this->mCondition->notifyAll();
			return true;
		}

		return false;
	}
}

?>