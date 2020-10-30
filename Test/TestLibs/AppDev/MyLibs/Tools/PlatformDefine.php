<?php

namespace MyLibs\Tools;

class PlatformDefine
{
    public static $PlatformName;

    public static function init()
	{
	    PlatformDefine::$PlatformName = php_uname('s');
	}
}

?>