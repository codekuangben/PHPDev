<?php

namespace MyLibs;

/**
 * @brief 日志设备
 */
class LogDeviceBase
{
    protected $mLogDeviceId;
    
    public function __construct()
    {
        $this->mLogDeviceId = LogDeviceId::eFileLogDevice;
    }
    
    public function getLogDeviceId()
    {
        return $this->mLogDeviceId;
    }
    
    public function setLogDeviceId($value)
    {
        $this->mLogDeviceId = $value;
    }
    
	public function initDevice()
	{

	}

	public function closeDevice()
	{

	}

	public function logout($message, $type = LogColor::eLC_LOG)
	{
	    
	}
}

?>