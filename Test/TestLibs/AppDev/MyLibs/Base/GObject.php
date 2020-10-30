<?php

namespace MyLibs\Base;

class GObject
{
	protected $mTypeId;     // 名字

	public function __construct()
	{
		$this->mTypeId = "GObject";
	}

	public function getTypeId()
	{
		return $this->mTypeId;
	}
}

?>