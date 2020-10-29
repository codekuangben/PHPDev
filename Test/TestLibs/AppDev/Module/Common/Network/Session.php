<?php

namespace MModule;

require_once (dirname(__FILE__) . "/../../Entry/MainEntry.php");

use MyLibs\Ctx;
use MyLibs\NullUserCmd;

if(isset($_REQUEST["CmdId"]) && isset($_REQUEST["ParamId"]))
{
    $msg = new NullUserCmd();
    $msg->CmdId = $_REQUEST["CmdId"];
    $msg->ParamId = $_REQUEST["ParamId"];
    
    if(!Ctx::$msInstance->mNetCmdNotify->isStopNetHandle())
    {
        Ctx::$msInstance->mNetCmdNotify->handleMsg($msg);
    }
    
    echo("result=1");
}

?>