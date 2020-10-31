<?php

namespace Module\Common\FrameWork;

use MyLibs\Base\GObject;

class CtxExt extends GObject
{
    public static $msIns;
    
    public function __construct()
    {
        
    }
    
    public static function instance($value = null)
    {
        if (self::$msIns == null)
        {
            if(null != $value)
            {
                self::$msIns = $value;
            }
            else
            {
                self::$msIns = new CtxExt();
            }
        }
        
        return CtxExt::$msIns;
    }
    
    protected function _preInit()
    {
        
    }
    
    public function _execInit()
    {
        
    }
    
    public function _postInit()
    {
        
    }
    
    public function init()
    {
        $this->_preInit();
        $this->_execInit();
        $this->_postInit();
    }
    
    public function dispose()
    {
        
    }
    
    public function run()
    {
        
    }
}

?>