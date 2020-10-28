<?php

namespace SDK\Lib;

class LogTypeId
{
	public const eLogCommon = 0;         	// 通用日志
	public const eLogTest = 3;				// 测试日志
	public const eLogLocalFile = 6;		       // 本地文件系统， MDataStream
	public const eLogEventRemove = 14;		   // 事件移除日志
	public const eLogPriorityListCheck = 21;   // PriorityList 错误检查
	public const eLogNoPriorityListCheck = 22; // NoPriorityList 错误检查
	public const eLogEventDispatch = 23; // LogEventDispatch 日志

	public const eLogProfileDebug = 27;	// 配置日志
	public const eLogProfile = 28;			// 配置日志
}

?>