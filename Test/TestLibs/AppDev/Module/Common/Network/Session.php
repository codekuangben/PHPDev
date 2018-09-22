<?php

namespace SDK\Module;

require_once (dirname(__FILE__) . "/../../Module/Entry/MainEntry.php");

use SDK\Lib\Ctx;
use SDK\Lib\NullUserCmd;

if(isset($_POST["byCmd"]) && isset($_POST["byParam"]))
{
    $msg = new NullUserCmd();
    $msg->byCmd = $_POST["byCmd"];
    $msg->byParam = $_POST["byParam"];
    
    if(!Ctx::$msInstance->mNetCmdNotify->isStopNetHandle())
    {
        Ctx::$msInstance->mNetCmdNotify->handleMsg($msg);
    }
}

?>