<?php

namespace MModule;

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
    
    $cmdDispatchInfo->byCmd = UtilConvert::convStrToInt(UtilSysLibWrap::request("Cmd"));
    $cmdDispatchInfo->byParam = UtilConvert::convStrToInt(UtilSysLibWrap::request("Param"));

    $unityNetNotify = new UnityNetNotify();
    $unityNetNotify->init();
    $unityNetNotify->handleMsg();
}

?>