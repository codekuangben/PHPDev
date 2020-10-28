<?php

namespace SDK\Lib;

class PlatformDefine
{
    public static $PlatformName;

    public static function init()
	{
	    PlatformDefine::$PlatformName = php_uname('s');
	}
}

?>