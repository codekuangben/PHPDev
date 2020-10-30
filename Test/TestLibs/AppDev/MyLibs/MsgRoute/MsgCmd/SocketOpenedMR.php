<?php

namespace MyLibs\MsgRoute\MsgCmd;

use MyLibs\MsgRoute\MsgRouteBase;
use MyLibs\MsgRoute\MsgRouteId;

class SocketOpenedMR extends MsgRouteBase
{
	public function __construct()
	{
		parent::__construct(MsgRouteId::eMRIDSocketOpened);
	}
}

?>