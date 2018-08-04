<?php

namespace SDK\Lib;

/**
 * @brief 有 Id 的缓存池
 */
class IdPoolSys
{
	protected $mId2PoolDic;

	public function __construct()
	{
		$this->mId2PoolDic = new MDictionary();
	}

	public function init()
	{

	}

	public function dispose()
	{

	}

	public function getObject($id)
	{
		$ret = null;

		if ($this->mId2PoolDic.ContainsKey(id))
		{
			if ($this->mId2PoolDic[id].Count() > 0)
			{
				$ret = $this->mId2PoolDic[id][0];
				$this->mId2PoolDic[id].RemoveAt(0);
			}
		}

		return ret;
	}

	public function deleteObj($id, $obj)
	{
		if(!$this->mId2PoolDic.ContainsKey($id))
		{
			$this->mId2PoolDic[$id] = new MList();
			$this->mId2PoolDic[$id].setIsSpeedUpFind(true);
		}

		if(!$this->mId2PoolDic[$id].Contains($obj))
		{
			$this->mId2PoolDic[$id].Add($obj);
		}
	}
}

?>