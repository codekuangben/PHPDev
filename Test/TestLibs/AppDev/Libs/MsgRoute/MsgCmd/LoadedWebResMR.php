<?php

namespace SDK\Lib;

class LoadedWebResMR extends MsgRouteBase
{
	public $mTask;

	public function __construct()
	{
		parent::__construct(MsgRouteID.eMRIDLoadedWebRes);
	}

	public function resetDefault()
	{
		$this->mTask = null;
	}
}

?>