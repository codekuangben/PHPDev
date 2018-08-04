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
}

?>