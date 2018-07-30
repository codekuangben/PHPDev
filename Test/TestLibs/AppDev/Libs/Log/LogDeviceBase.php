<?php

namespace SDK\Lib;

/**
 * @brief 日志设备
 */
class LogDeviceBase
{
	public function initDevice()
	{

	}

	public function closeDevice()
	{

	}

	public function logout(string message, LogColor type = LogColor.eLC_LOG);
}

?>