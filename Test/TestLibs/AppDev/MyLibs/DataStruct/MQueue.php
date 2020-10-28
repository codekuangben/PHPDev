<?php

namespace MyLibs;

class MQueue
{
	protected $mQueue;

	public function __construct()
	{
		//$this->mQueue = new Queue();
	}

	public function count()
	{
		return $this->mQueue->count;
	}

	public function Dequeue()
	{
		return $this->mQueue->Dequeue();
	}

	public function Enqueue($item)
	{
		$this->mQueue->Enqueue($item);
	}

	public function Peek()
	{
		return $this->mQueue->Peek();
	}
}

?>