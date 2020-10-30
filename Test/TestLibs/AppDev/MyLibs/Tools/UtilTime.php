<?php

namespace MyLibs\Tools;

class UtilTime
{
    public static function getTimeStamp()
    {
        $ret = time();
        return $ret;
    }
    
    public static function getTimeStr()
    {
        $ret = date('Y-m-d-H-i-s');
        return $ret;
    }
}

?>