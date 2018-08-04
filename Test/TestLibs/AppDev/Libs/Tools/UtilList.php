<?php

namespace SDK\Lib;

class UtilList
{
    public static function count($list)
    {
        return count($list);
    }
    
    public static function add($list, $item)
    {
        array_push($list, $item);
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
            $destArray[$destIndex + $index] = $retArray[$srcIndex + $index];
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