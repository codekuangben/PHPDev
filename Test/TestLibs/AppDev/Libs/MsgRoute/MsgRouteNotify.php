<?php

namespace SDK\Lib;

class MsgRouteNotify
{
	protected $mDispList;

	public function __construct()
	{
		$this->mDispList = new MList();
	}

	public function addOneNofity($disp)
	{
		if(!$this->mDispList.Contains(disp))
		{
			$this->mDispList.Add(disp);
		}
	}

	public function removeOneNotify($disp)
	{
		if($this->mDispList.Contains(disp))
		{
			$this->mDispList.Remove(disp);
		}
	}

	public function handleMsg($msg)
	{
		$index = 0;
		$liseLen = $this->mDispList.Count();

		while(index < liseLen)
		{
			$this->mDispList[index].handleMsg(msg);

			++$index;
		}

		// 暂时不用缓存，非资源数据结构重新申请内存应该不会太耗时
		//Ctx.mInstance.mPoolSys.deleteObj(msg);
	}
}

?>