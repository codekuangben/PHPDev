<?php

namespace MyLibs\Base;

class UniqueNumIdGen
{
	protected $mPreIdx;
	protected $mCurId;

	public function __construct($baseUniqueId)
	{
		$this->mCurId = $baseUniqueId;
	}

	public function genNewId()
	{
		$this->mPreIdx = $this->mCurId;
		$this->mCurId++;
		return $this->mPreIdx;
	}

	public function getCurId()
	{
		return $this->mCurId;
	}
}

?>