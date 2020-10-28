<?php

namespace SDK\Lib;

/**
 * @brief 优先级队列对象
 */
class PriorityProcessObject
{
	public $mPriorityObject;
	public $mPriority;

	public function __construct()
	{
		$this->mPriorityObject = null;
		$this->mPriority = 0.0;
	}
}

?>