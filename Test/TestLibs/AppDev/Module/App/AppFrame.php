<?php

namespace Module\App;

// 注册加载器
require_once (dirname(__FILE__) . "/../../MyLibs/Common/SystemEnv.php");
require_once (dirname(__FILE__) . "/../../MyLibs/Base/MLoader.php");

use MyLibs\Common\SystemEnv;
use MyLibs\Base\MLoader;

MLoader::appendPHPPath(SystemEnv::$MY_PHP_ROOT_PATH);
MLoader::registerLoader(MLoader::$loadClassInSearchPath);

// 开始正式加载
require_once (dirname(__FILE__) . "/../../MyLibs/FrameWork/Ctx.php");
require_once (dirname(__FILE__) . "/../Common/Common/DataPrequisites.php");

use MyLibs\FrameWork\Ctx;
use MyLibs\FrameWork\MacroDef;
use Module\App\EventNotify\NetNotify;

class AppFrame
{
    protected $mNetNotify;
    
    public function __construct()
    {
        Ctx::instance();
    }
    
    public function init()
    {
        Ctx::$msIns->init();
        
        $this->mNetNotify = new NetNotify();
        $this->mNetNotify->init();
        Ctx::$msIns->mNetCmdNotify->addOneNofity($this->mNetNotify);
    }
    
    public function dispose()
    {
        Ctx::$msIns->dispose();
        
        $this->mNetNotify->dispose();
        $this->mNetNotify = null;
    }
    
    public function run()
    {
        $interval = 1 / 24;
        
        Ctx::$msIns->run();
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