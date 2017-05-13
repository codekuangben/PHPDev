<?php

namespace SDK\Lib;

class LockQueue
{
	protected $mList;

	public function __construct($name)
	{
		$this->mList = new LockList($name);
	}

	public function push($item)
	{
		$this->mList->Add($item);
	}

	public function pop()
	{
		return $this->mList->RemoveAt(0);
	}
}

?>