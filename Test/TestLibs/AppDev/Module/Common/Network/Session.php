<?php

namespace Module\Common\Network;

//require_once (dirname(__FILE__) . "/../../Entry/MainEntry.php");

use MyLibs\FrameWork\Ctx;
use MyLibs\Network\CmdDispatch\NullUserCmd;

if(isset($_REQUEST["CmdId"]) && isset($_REQUEST["ParamId"]))
{
    $msg = new NullUserCmd();
    $msg->CmdId = $_REQUEST["CmdId"];
    $msg->ParamId = $_REQUEST["ParamId"];
    
    if(!Ctx::$msIns->mNetCmdNotify->isStopNetHandle())
    {
        Ctx::$msIns->mNetCmdNotify->handleMsg($msg);
    }
    
    echo("result=1");
}

?>