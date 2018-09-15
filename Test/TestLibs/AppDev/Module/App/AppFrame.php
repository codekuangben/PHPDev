<?php

namespace SDK\Module;

require_once (dirname(__FILE__) . "/../../Libs/FrameWork/Ctx.php");

use SDK\Lib\Ctx;

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
        Ctx::$mInstance->run();
    }
    
    public function dispose()
    {
        Ctx::$mInstance->dispose();
    }
}

?>