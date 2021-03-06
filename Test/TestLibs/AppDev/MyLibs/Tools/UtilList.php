<?php

namespace MyLibs\Tools;

class UtilList
{
    public static function isArray($list)
    {
        return is_array($list);
    }
    
    public static function count($list)
    {
        return count($list);
    }
    
    public static function add($list, $item)
    {
        array_push($list, $item);
    }
    
    public static function pushFront($list, $item)
    {
        array_unshift($list, $item);
    }
    
    public static function setCapacity($list, $capacity)
    {
        $index = 0;
        $listLen = $capacity;
        
        while($index < $listLen)
        {
            UtilList::add($list, null);
            $index += 1;
        }
    }
    
    public static function expandArray($srcArray, $expandNum)
    {
        $retArray = array();
        UtilList::setCapacity($retArray, $expandNum);
        
        $index = 0;
        $listLen = UtilList::count($srcArray);
        
        while($index < $listLen && $index < $expandNum)
        {
            $retArray[$index] = $retArray[$index];
            $index += 1;
        }
    }
    
    public static function Copy($srcArray, $srcIndex, $destArray, $destIndex, $length)
    {
        $index = 0;
        $srcListLen = UtilList::count($srcArray);
        $destListLen = UtilList::count($destArray);
        
        while($index < $length && $srcIndex + $index < $srcListLen && $destIndex + $index < $destListLen)
        {
            $destArray[$destIndex + $index] = $srcArray[$srcIndex + $index];
            $index += 1;
        }
    }
    
    public static function CopyToStr($srcArray, $srcIndex, &$destStr, $destIndex, $length)
    {
        $index = 0;
        $srcListLen = UtilList::count($srcArray);
        $destListLen = UtilStr::length($destStr);
        
        while($index < $length && $srcIndex + $index < $srcListLen)
        {
            if($destIndex + $index < $destListLen)
            {
                $destStr[$destIndex + $index] = (string)($srcArray[$srcIndex + $index]);
            }
            else
            {
                $destStr = $destStr . (string)($srcArray[$srcIndex + $index]);
            }
            
            $index += 1;
        }
    }
    
    public static function Reverse($srcArray)
    {
        $startIndex = 0;
        $endIndex = UtilList::count($srcArray) - 1;
        $tmp = null;
        
        while($startIndex < $endIndex)
        {
            $tmp = $srcArray[$startIndex];
            $srcArray[$startIndex] = $srcArray[$endIndex];
            $srcArray[$endIndex] = $tmp;
            
            $startIndex += 1;
            $endIndex -= 1;
        }
    }
}

?>