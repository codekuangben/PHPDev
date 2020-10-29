<?php

namespace MyLibs;

class MsgRouteNotify
{
	protected $mDispList;

	public function __construct()
	{
		$this->mDispList = new MList();
	}

	public function addOneNofity($disp)
	{
		if(!$this->mDispList->contains(disp))
		{
			$this->mDispList->add(disp);
		}
	}

	public function removeOneNotify($disp)
	{
		if($this->mDispList->contains(disp))
		{
			$this->mDispList->remove(disp);
		}
	}

	public function handleMsg($msg)
	{
		$index = 0;
		$liseLen = $this->mDispList->count();

		while(index < liseLen)
		{
			$this->mDispList[index]->handleMsg(msg);

			++$index;
		}

		// 暂时不用缓存，非资源数据结构重新申请内存应该不会太耗时
		//Ctx::$msIns->mPoolSys->deleteObj(msg);
	}
}

?>