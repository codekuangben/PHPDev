<?php

namespace SDK\Lib;

/**
 * @brief 对象池
 */
class PoolSys
{
	protected $mPoolList;
	//protected LockList<IRecycle> mPoolList;       // 这个是线程安全队列，但是比较耗时

	public function __construct()
	{
		$this->mPoolList = new MList();
		$this->mPoolList->setIsSpeedUpFind(true);
		//$this->mPoolList = new LockList<IRecycle>("PoolSys_List");
	}

	public function newObject($T)
	{
		$retObj = null;
		$tmpObj = null;
		$finded = false;

		// 查找
		$index = 0;
		$listLen = $this->mPoolList.count();

		if($listLen > 0)
		{
		    $tmpObj = $this->mPoolList[$index];

			$finded = true;

			$retObj = $tmpObj;
			$this->mPoolList.removeAt($index);

			$retObj.resetDefault();
		}

		if (!$finded)
		{
		    $retObj = new $T();
		}

		return $retObj;
	}

	public function deleteObj($obj)
	{
		if (!$this->mPoolList.contains($obj))
		{
			$this->mPoolList.add($obj);
		}
	}
}

?>