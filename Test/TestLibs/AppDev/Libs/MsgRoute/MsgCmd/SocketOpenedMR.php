<?php

namespace SDK\Lib;

class SocketOpenedMR extends MsgRouteBase
{
	public function __construct()
	{
		parent::__construct(MsgRouteID.eMRIDSocketOpened);
	}
}

?>