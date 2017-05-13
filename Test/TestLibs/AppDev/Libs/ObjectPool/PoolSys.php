<?php

namespace SDK\Lib;

/**
 * @brief 对象池
 */
public class PoolSys
{
	protected MList<IRecycle> mPoolList;
	//protected LockList<IRecycle> mPoolList;       // 这个是线程安全队列，但是比较耗时

	public PoolSys()
	{
		$this->mPoolList = new MList<IRecycle>();
		$this->mPoolList.setIsSpeedUpFind(true);
		//$this->mPoolList = new LockList<IRecycle>("PoolSys_List");
	}

	public T newObject<T>() where T : IRecycle, new()
	{
		T retObj = default(T);
		object tmpObj = null;
		bool finded = false;

		// 查找
		int idx = 0;

		for(idx = 0; idx < $this->mPoolList.Count(); ++idx)
		{
			tmpObj = $this->mPoolList[idx];

			if (typeof(T) == tmpObj.GetType())
			{
				finded = true;

				retObj = (T)tmpObj;
				$this->mPoolList.RemoveAt(idx);

				retObj.resetDefault();

				//MethodInfo myMethodInfo = retObj.GetType().GetMethod("resetDefault");

				//if (myMethodInfo != null)
				//{
				//    myMethodInfo.Invoke(retObj, null);
				//}

				break;
			}
		}

		if (!finded)
		{
			retObj = new T();
		}

		return retObj;
	}

	public void deleteObj(IRecycle obj)
	{
		if (!$this->mPoolList.Contains(obj))
		{
			$this->mPoolList.Add(obj);
		}
	}
}

?>