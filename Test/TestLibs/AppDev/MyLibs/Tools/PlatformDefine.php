<?php

namespace MyLibs;

class PlatformDefine
{
    public static $PlatformName;

    public static function init()
	{
	    PlatformDefine::$PlatformName = php_uname('s');
	}
}

?>