<?php

namespace MModule;

require_once (dirname(__FILE__) . "/../../Entry/MainEntry.php");

use MyLibs\Ctx;
use MyLibs\NullUserCmd;

if(isset($_REQUEST["byCmd"]) && isset($_REQUEST["byParam"]))
{
    $msg = new NullUserCmd();
    $msg->byCmd = $_REQUEST["byCmd"];
    $msg->byParam = $_REQUEST["byParam"];
    
    if(!Ctx::$msInstance->mNetCmdNotify->isStopNetHandle())
    {
        Ctx::$msInstance->mNetCmdNotify->handleMsg($msg);
    }
    
    echo("result=1");
}

?>