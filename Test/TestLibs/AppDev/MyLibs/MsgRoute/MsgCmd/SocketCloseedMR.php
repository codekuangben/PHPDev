<?php

namespace MyLibs\MsgRoute\MsgCmd;

use MyLibs\MsgRoute\MsgRouteBase;
use MyLibs\MsgRoute\MsgRouteId;

class SocketCloseedMR extends MsgRouteBase
{
	public function __construct()
	{
		parent::__construct(MsgRouteId::eMRIDSocketClosed);
	}
}

?>