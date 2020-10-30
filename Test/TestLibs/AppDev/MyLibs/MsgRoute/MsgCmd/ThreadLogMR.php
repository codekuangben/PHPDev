<?php

namespace MyLibs\MsgRoute\MsgCmd;

use MyLibs\MsgRoute\MsgRouteBase;
use MyLibs\MsgRoute\MsgRouteId;

// 线程日志
class ThreadLogMR extends MsgRouteBase
{
	public $mLogSys;

	public function __construct()
	{
		parent::__construct(MsgRouteId::eMRIDThreadLog);
	}
}

?>