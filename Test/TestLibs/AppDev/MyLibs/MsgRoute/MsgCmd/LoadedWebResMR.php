<?php

namespace MyLibs\MsgRoute\MsgCmd;

use MyLibs\MsgRoute\MsgRouteBase;
use MyLibs\MsgRoute\MsgRouteId;

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