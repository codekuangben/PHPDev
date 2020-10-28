<?php

namespace MyLibs;

class LoadedWebResMR extends MsgRouteBase
{
	public $mTask;

	public function __construct()
	{
		parent::__construct(MsgRouteId::eMRIDLoadedWebRes);
	}

	public function resetDefault()
	{
		$this->mTask = null;
	}
}

?>