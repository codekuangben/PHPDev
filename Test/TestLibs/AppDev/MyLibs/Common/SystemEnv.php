<?php

namespace MyLibs\Common;

class SystemEnv
{
    public static $MY_PHP_ROOT_PATH;
    //$GLOBALS["MY_PHP_ROOT_PATH"] = $MY_PHP_ROOT_PATH;
}

SystemEnv::$MY_PHP_ROOT_PATH = dirname(__FILE__) . "/../..";
SystemEnv::$MY_PHP_ROOT_PATH = realpath(SystemEnv::$MY_PHP_ROOT_PATH);
SystemEnv::$MY_PHP_ROOT_PATH = str_replace("\\", "/", SystemEnv::$MY_PHP_ROOT_PATH);

?>