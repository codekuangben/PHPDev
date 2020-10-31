<?php

namespace Module\Frame;

// 注册加载器
require_once (dirname(__FILE__) . "/../../MyLibs/Base/MLoader.php");

// 开始正式加载
//require_once (dirname(__FILE__) . "/../../MyLibs/FrameWork/Ctx.php");
//require_once (dirname(__FILE__) . "/../Common/Common/DataPrequisites.php");

use MyLibs\FrameWork\Ctx;
use MyLibs\FrameWork\MacroDef;
use Module\Common\FrameWork\CtxExt;
use Module\Frame\EventNotify\NetNotify;

class AppFrame
{
    protected $mNetNotify;
    
    public function __construct()
    {
        Ctx::instance();
        CtxExt::instance();
    }
    
    public function init()
    {
        Ctx::$msIns->init();
        CtxExt::$msIns->init();
        
        
        $this->mNetNotify = new NetNotify();
        $this->mNetNotify->init();
        Ctx::$msIns->mNetCmdNotify->addOneNofity($this->mNetNotify);
    }
    
    public function dispose()
    {
        CtxExt::$msIns->dispose();
        Ctx::$msIns->dispose();
        
        $this->mNetNotify->dispose();
        $this->mNetNotify = null;
    }
    
    public function run()
    {
        $interval = 1 / 24;
        
        Ctx::$msIns->run();
        CtxExt::$msIns->run();
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
        Ctx::$msIns->mProcessSys->ProcessNextFrame();
    }
}

?>