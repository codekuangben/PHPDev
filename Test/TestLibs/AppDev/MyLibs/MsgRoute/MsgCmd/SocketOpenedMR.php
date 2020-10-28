<?php

namespace MyLibs;

class SocketOpenedMR extends MsgRouteBase
{
	public function __construct()
	{
		parent::__construct(MsgRouteId::eMRIDSocketOpened);
	}
}

?>