<?php

namespace MyLibs\DataStruct\NoOrPriorityList;

/**
 * @brief 非优先级或者优先级列表
 */
interface INoOrPriorityList
{
	function setIsSpeedUpFind($value);
	function setIsOpKeepSort($value);
	function clear();
	function count();

	function get($index);
	function contains($item);
	function removeAt($index);
	function getIndexByNoOrPriorityObject($priorityObject);

	function addNoOrPriorityObject($noPriorityObject, $priority = 0.0);
	function removeNoOrPriorityObject($noPriorityObject);
}

?>