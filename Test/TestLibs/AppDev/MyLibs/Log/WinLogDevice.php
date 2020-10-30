<?php

namespace MyLibs\Log;

/**
 * @brief 文件日志
 */
class WinLogDevice extends LogDeviceBase
{
    public function __construct()
    {
        parent::__construct();
        
        $this->mLogDeviceId = LogDeviceId::eWinLogDevice;
    }
    
	public function logout($message, $type = LogColor::eLC_LOG)
	{
	    if (type == LogColor::eLC_LOG)
		{
			echo(message);
		}
		else if (type == LogColor::eLC_WARN)
		{
		    echo(message);
		}
		else if (type == LogColor::eLC_ERROR)
		{
		    error(message);
		}
	}
}

?>