<?php

namespace SDK\Lib;

class LogTypeId
{
	public const eLogCommon = 0;         	// 通用日志
	public const eLogSceneCull = 1;      	// 场景裁剪日志
	public const eLogMSceneManager = 2;  	// 场景管理器
	public const eLogTest = 3;				// 测试日志
	public const eLogTestRL = 4;			// 测试资源加载
	public const eLogResLoader = 5;		// 资源加载
	public const eLogLocalFile = 6;		// 本地文件系统， MDataStream
	public const eLogAcceleration = 7;		// 重力
	public const eLogSplitMergeEmit = 8;	// 分裂融合
	public const eLogSceneInterActive = 9;	// 场景交互
	public const eLogBeingMove = 10;		// Being 移动
	public const eLogKBE = 11;				// KBEngine 日志
	public const eLogScene = 12;			// Scene 日志
	public const eLogUnityCB = 13;			// Unity回调日志
	public const eLogEventRemove = 14;		// 事件移除日志
	public const eLogMusicBug = 15;		// 手机音乐没有 bug
	public const eLogLoadBug = 16;			// 加载错误 bug
	public const eLogMergeBug = 17;		// 手机不能融合 bug
	public const eLogEatBug = 18;			// 手机不能吃 bug
	public const eLogSimHitBullet = 19;	// 模拟集中日志
	public const eLogTwoDTerrain = 20;		// 2D 地形
	public const eLogPriorityListCheck = 21;// PriorityList 错误检查
	public const eLogNoPriorityListCheck = 22;// NoPriorityList 错误检查

	public const eLogPosSyn = 23;			// 服务器位置同步
	public const eLogPlaneError = 24;		// 飞机错误
	public const eLogDownload = 25;		// 下载资源
	public const eLogAutoUpdate = 26;		// 自动更新
	public const eLogProfileDebug = 27;	// 配置日志
	public const eLogProfile = 28;			// 配置日志


	// 编辑器日志
	public const eLogEditorBuildPlayer = 29;// 编辑器导出 Player 日志

	// Error
	public const eErrorResLoader = 30;		// 资源加载
	public const eErrorDownload = 31;		// 下载资源错误
	public const eErrorFillIO = 32;		// File IO 操作错误
}

?>