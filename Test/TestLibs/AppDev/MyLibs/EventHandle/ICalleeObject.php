<?php

namespace MyLibs\EventHandle;

/**
 * @brief 可被调用的函数对象
 */
interface ICalleeObject
{
	function call($dispatchObject);
}

?>