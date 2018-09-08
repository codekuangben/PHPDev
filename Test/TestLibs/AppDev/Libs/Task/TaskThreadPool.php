<?php

namespace SDK\Lib;

class TaskThreadPool
{
	protected $mList;

	public function __construct()
	{

	}

	public function initThreadPool($numThread, $taskQueue)
	{
	    $this->mList = new MList($numThread);
		$idx = 0;
		
		for($idx = 0; $idx < $numThread; ++$idx)
		{
		    $this->mList.Add(new TaskThread(UtilStr::Format("TaskThread{0}", idx), $taskQueue));
		    $this->mList[$idx].start();
		}
	}

	public function notifyIdleThread()
	{
	    $index = 0;
	    $listLen = $this->mList->Count();
	    $item = null;
	    
	    while($index < $listLen)
	    {
	        $item = $this->mList->get($index);
	        
	        if($item.notifySelf())       // 如果唤醒某个线程就退出，如果一个都没有唤醒，说明当前线程都比较忙，需要等待
	        {
	            break;
	        }
	        
	        $index += 1;
	    }
	}
}

?>