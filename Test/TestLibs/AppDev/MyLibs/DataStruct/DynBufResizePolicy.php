<?php

namespace SDK\Lib;

class DynBufResizePolicy
{
	// 获取一个最近的大小
	static public function getCloseSize($needSize, $capacity, $maxCapacity)
	{
		$ret = 0;
		if ($capacity > $needSize)        // 使用 > ，不使用 >= ，浪费一个自己，方便判断
		{
			$ret = $capacity;
		}
		else
		{
			$ret = 2 * $capacity;
			while ($ret < $needSize && $ret < $maxCapacity)
			{
				$ret *= 2;
			}

			if ($ret > $maxCapacity)
			{
				$ret = $maxCapacity;
			}

			if ($ret < $needSize)      // 分配失败
			{
				Ctx::$msInstance->mLogSys->error(string.Format("Malloc byte buffer failed，cannot malloc {0} byte buffer", needSize));
			}
		}

		return $ret;
	}
}

?>