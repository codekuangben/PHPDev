<?php

namespace MyLibs;

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