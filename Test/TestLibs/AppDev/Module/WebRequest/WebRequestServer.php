<?php

namespace Module\WebRequest;

require_once (dirname(__FILE__) . "/../Frame/AppFrame.php");

use MyLibs\Tools\UtilSysLibWrap;
use MyLibs\Tools\UtilConvert;
use MyLibs\Network\CmdDispatch\CmdDispatchInfo;
use Module\Unity\EventNotify\UnityNetNotify;

/**
 * @brief 所有的网络请求都发送到这里
 */
if(UtilSysLibWrap::issetInRequest("CmdId") && 
    UtilSysLibWrap::issetInRequest("ParamId"))
{
    $cmdDispatchInfo = new CmdDispatchInfo();
    
    $cmdDispatchInfo->CmdId = UtilConvert::convStrToInt(UtilSysLibWrap::request("CmdId"));
    $cmdDispatchInfo->ParamId = UtilConvert::convStrToInt(UtilSysLibWrap::request("ParamId"));

    $unityNetNotify = new UnityNetNotify();
    $unityNetNotify->init();
    $unityNetNotify->handleMsg($cmdDispatchInfo);
}

?>