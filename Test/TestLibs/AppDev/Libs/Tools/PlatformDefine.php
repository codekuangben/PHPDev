<?php

namespace SDK\Lib;

class PlatformDefine
{
    public static $PlatformName;

    public static function init()
	{
	    $this->$PlatformName = php_uname('s');
	}
}

?>