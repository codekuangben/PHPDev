<?php

namespace SDK\Lib;

class PlatformDefine
{
    public static $PlatformName;

    public static function init()
	{
	    $PlatformName = php_uname('s');
	}
}

?>