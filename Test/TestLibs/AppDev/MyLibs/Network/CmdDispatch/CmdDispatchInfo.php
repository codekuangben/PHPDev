<?php

namespace MyLibs\Network\CmdDispatch;

use MyLibs\EventHandle\IDispatchObject;

class CmdDispatchInfo implements IDispatchObject
{
	public $byteBuffer;
	public $CmdId;
	public $ParamId;
}

?>