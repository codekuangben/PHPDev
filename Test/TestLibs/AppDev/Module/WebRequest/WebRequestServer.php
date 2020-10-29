<?php

namespace MModule;

require_once (dirname(__FILE__) . "/../App/AppFrame.php");

use MyLibs\UtilSysLibWrap;
use MyLibs\UtilConvert;
use MyLibs\CmdDispatchInfo;

/**
 * @brief 所有的网络请求都发送到这里
 */
if(UtilSysLibWrap::issetInRequest("Cmd") && 
    UtilSysLibWrap::issetInRequest("Param"))
{
    $cmdDispatchInfo = new CmdDispatchInfo();
    
    $cmdDispatchInfo->CmdId = UtilConvert::convStrToInt(UtilSysLibWrap::request("Cmd"));
    $cmdDispatchInfo->ParamId = UtilConvert::convStrToInt(UtilSysLibWrap::request("Param"));

    $unityNetNotify = new UnityNetNotify();
    $unityNetNotify->init();
    $unityNetNotify->handleMsg($cmdDispatchInfo);
}

?>