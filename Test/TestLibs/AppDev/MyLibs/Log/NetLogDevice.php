<?php

namespace SDK\Lib;

/**
 * @brief 文件日志
 */
class NetLogDevice extends LogDeviceBase
{
    public function __construct()
    {
        parent::__construct();
        
        $this->mLogDeviceId = LogDeviceId::eNetLogDevice;
    }
}

?>