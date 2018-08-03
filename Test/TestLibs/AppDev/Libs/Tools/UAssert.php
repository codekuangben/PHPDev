<?php

namespace SDK\Lib;

class UAssert
{
	public static function DebugAssert($condation)
	{
		if(!condation)
		{
			throw new \Exception("DebugAssert Error");
		}
	}
}

?>