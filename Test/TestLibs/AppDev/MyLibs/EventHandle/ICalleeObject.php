<?php

namespace MyLibs;

/**
 * @brief 可被调用的函数对象
 */
interface ICalleeObject
{
	function call($dispObj);
}

?>