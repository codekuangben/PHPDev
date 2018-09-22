<?php

namespace SDK\Module;

require_once (dirname(__FILE__) . "/../../Libs/FrameWork/Ctx.php");
require_once (dirname(__FILE__) . "/../Common/Common/DataPrequisites.php");

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
        Ctx::$msInstance->init();
        
        $this->mNetNotify = new NetNotify();
        $this->mNetNotify->init();
        Ctx::$msInstance->mNetCmdNotify->addOneNofity($this->mNetNotify);
    }
    
    public function dispose()
    {
        Ctx::$msInstance->dispose();
        
        $this->mNetNotify->dispose();
        $this->mNetNotify = null;
    }
    
    public function run()
    {
        $interval = 1 / 24;
        
        Ctx::$msInstance->run();
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
        Ctx::$msInstance->mProcessSys->ProcessNextFrame();
    }
}

?>