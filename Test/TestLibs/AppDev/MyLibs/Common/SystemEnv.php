<?php

namespace MyLibs\Common;

class SystemEnv
{
    public static $MY_PHP_ROOT_PATH;
    //$GLOBALS["MY_PHP_ROOT_PATH"] = $MY_PHP_ROOT_PATH;
}

SystemEnv::$MY_PHP_ROOT_PATH = dirname(__FILE__) . "/../..";

?>