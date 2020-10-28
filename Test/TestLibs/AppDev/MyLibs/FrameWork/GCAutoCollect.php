<?php

namespace MyLibs;

/**
 * @brief 垃圾自动回收
 */
class GCAutoCollect
{
	// 定时器启动垃圾回收
	// 收集垃圾
	public function Collect()
	{
		UtilSysLibWrap::ImmeUnloadUnusedAssets();
	}
}

?>