<?php

namespace SDK\Lib;

class SocketCloseedMR extends MsgRouteBase
{
	public function __construct()
	{
		parent::__construct(MsgRouteID.eMRIDSocketClosed);
	}
}

?>