<?php

namespace SDK\Lib;

/**
 * @brief 文件日志
 */
class WinLogDevice extends LogDeviceBase
{
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