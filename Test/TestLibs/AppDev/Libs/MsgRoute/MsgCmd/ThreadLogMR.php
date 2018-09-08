<?php

namespace SDK\Lib;

// 线程日志
class ThreadLogMR extends MsgRouteBase
{
	public $mLogSys;

	public function __construct()
	{
		parent::__construct(MsgRouteId.eMRIDThreadLog);
	}
}

?>