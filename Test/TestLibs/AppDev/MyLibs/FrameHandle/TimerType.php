<?php

/**
* @brief 定时器类型
*/
namespace MyLibs\FrameHandle;

class TimerType
{
	public const eTickTimer = 0;             // 每一帧定时器
	public const eOneSecTimer = 1;           // 1 秒定时器
	public const eFiveSecTimer = 2;          // 5 秒定时器
	public const eTimerTotla = 3;// 总共定时器种类
}

?>