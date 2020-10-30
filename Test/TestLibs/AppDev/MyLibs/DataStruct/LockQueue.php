<?php

namespace MyLibs\DataStruct;

class LockQueue
{
	protected $mList;

	public function __construct($name)
	{
		$this->mList = new LockList($name);
	}

	public function push($item)
	{
		$this->mList->add($item);
	}

	public function pop()
	{
		return $this->mList->removeAt(0);
	}
}

?>