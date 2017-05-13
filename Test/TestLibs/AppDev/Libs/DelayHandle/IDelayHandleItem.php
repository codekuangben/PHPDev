<?php

namespace SDK\Lib;

/**
 * @brief 延迟添加的对象
 */
interface IDelayHandleItem
{
	function setClientDispose($isDispose);
	function isClientDispose();
}

?>