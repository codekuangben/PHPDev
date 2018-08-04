<?php

namespace SDK\Lib;

class MQueue
{
	protected $mQueue;

	public function __construct()
	{
		$this->mQueue = new Queue();
	}

	public function Count()
	{
		return $this->mQueue.Count;
	}

	public function Dequeue()
	{
		return $this->mQueue.Dequeue();
	}

	public function Enqueue($item)
	{
		$this->mQueue.Enqueue($item);
	}

	public function Peek()
	{
		return $this->mQueue.Peek();
	}
}

?>