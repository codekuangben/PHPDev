<?php

namespace SDK\Lib;

/**
 * @brief 非优先级或者优先级列表
 */
interface INoOrPriorityList
{
	function setIsSpeedUpFind($value);
	function setIsOpKeepSort($value);
	function Clear();
	function Count();

	function get($index);
	function Contains($item);
	function RemoveAt($index);
	function getIndexByNoOrPriorityObject($priorityObject);

	function addNoOrPriorityObject($noPriorityObject, $priority = 0.0);
	function removeNoOrPriorityObject($noPriorityObject);
}

?>