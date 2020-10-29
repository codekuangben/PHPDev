<?php

namespace MyLibs;

class IndexItemBase
{
	protected $mIndex;   // 索引

	public function __construct()
	{
		$this->mIndex = -1;
	}

	public function getIndex()
	{
		return $this->mIndex;
	}

	public function setIndex($value)
	{
	    $this->mIndex = $value;
	}

	public function resetIndex()
	{
	    $this->mIndex = -1;
	}
}

?>