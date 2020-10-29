<?php

namespace MyLibs;

/**
 * @brief 系统消息流程，整个系统的消息分发都走这里，仅限单线程
 */
class SysMsgRoute extends LockQueue
{
	public function __construct($name)
	{
		parent::__construct($name);
	}

	public function pushMsg($msg)
	{
	    if ($msg->isMainThreadImmeHandle())
		{
			if(MThread::isMainThread())
			{
				Ctx::$msIns->mMsgRouteNotify->handleMsg(msg);
			}
			else
			{
				$this->push($msg);
			}
		}
		else
		{
			$this->push($msg);
		}
	}

	public function popMsg()
	{
		return $this->pop();
	}
}

?>