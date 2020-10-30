<?php

namespace MyLibs\Tools;

/**
 * @brief 各种类型转换
 */
class UtilConvert
{
    public static function convStrToInt($str)
    {
        $ret = intval($str);
        return $ret;
    }
    
    public static function convStrToFloat($str)
    {
        $ret = floatval($str);
        return $ret;
    }
    
    public static function convIntToStr($num)
    {
        $ret = strval($num);
        return $ret;
    }
    
    public static function convFloatToStr($num)
    {
        $ret = strval($num);
        return $ret;
    }
}

?>