<?php

namespace SDK\Module;

require_once (dirname(__FILE__) . "/../../Libs/FrameWork/Ctx.php");

use SDK\Lib\Ctx;
use SDK\Lib\MacroDef;

class AppFrame
{
    public function __construct()
    {
        Ctx::instance();
    }
    
    public function init()
    {
        Ctx::$mInstance->init();
    }
    
    public function run()
    {
        $interval = 1 / 24;
        
        Ctx::$mInstance->run();
        
        if(MacroDef::ENABLE_LOOP)
        {
            while(true)
            {
                $this->_update();
                sleep($interval);   //暂停时间（单位为秒）
            }
        }
    }
    
    public function dispose()
    {
        Ctx::$mInstance->dispose();
    }
    
    protected function _update()
    {
        Ctx::$mInstance->mProcessSys->ProcessNextFrame();
    }
}

?>