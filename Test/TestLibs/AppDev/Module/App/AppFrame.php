<?php

namespace SDK\Module;

require_once (dirname(__FILE__) . "/../../Libs/FrameWork/Ctx.php");

use SDK\Lib\Ctx;
use SDK\Lib\MacroDef;

class AppFrame
{
    protected $mNetNotify;
    
    public function __construct()
    {
        Ctx::instance();
    }
    
    public function init()
    {
        Ctx::$mInstance->init();
        
        $this->mNetNotify = new NetNotify();
        $this->mNetNotify->init();
        Ctx::$mInstance->mNetCmdNotify->addOneNofity($this->mNetNotify);
    }
    
    public function dispose()
    {
        Ctx::$mInstance->dispose();
        
        $this->mNetNotify->dispose();
        $this->mNetNotify = null;
    }
    
    public function run()
    {
        $interval = 1 / 24;
        
        Ctx::$mInstance->run();
        $this->_update();
        
        if(MacroDef::ENABLE_LOOP)
        {
            while(true)
            {
                $this->_update();
                sleep($interval);   //暂停时间（单位为秒）
            }
        }
    }
    
    protected function _update()
    {
        Ctx::$mInstance->mProcessSys->ProcessNextFrame();
    }
}

?>