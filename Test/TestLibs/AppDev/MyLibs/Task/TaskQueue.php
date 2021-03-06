<?php

namespace MyLibs\Task;

use MyLibs\DataStruct\LockQueue;

class TaskQueue extends LockQueue
{
	public $mTaskThreadPool;

	public function __construct($name)
	{
	    parent::__construct($name);
	}

	public function push($item)
	{
	    parent::push($item);

		// 检查是否有线程空闲，如果有就唤醒
		$this->mTaskThreadPool->notifyIdleThread();
	}
}

?>