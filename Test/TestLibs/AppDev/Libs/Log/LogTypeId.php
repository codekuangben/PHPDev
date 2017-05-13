<?php

namespace SDK\Lib;

class LogTypeId
{
	const eLogCommon = 0;         	// 通用日志
	const eLogSceneCull = 1;      	// 场景裁剪日志
	const eLogMSceneManager = 2;  	// 场景管理器
	const eLogTest = 3;				// 测试日志
	const eLogTestRL = 4;			// 测试资源加载
	const eLogResLoader = 5;		// 资源加载
	const eLogLocalFile = 6;		// 本地文件系统， MDataStream
	const eLogAcceleration = 7;		// 重力
	const eLogSplitMergeEmit = 8;	// 分裂融合
	const eLogSceneInterActive = 9;	// 场景交互
	const eLogBeingMove = 10;		// Being 移动
	const eLogKBE = 11;				// KBEngine 日志
	const eLogScene = 12;			// Scene 日志
	const eLogUnityCB = 13;			// Unity回调日志
	const eLogEventRemove = 14;		// 事件移除日志
	const eLogMusicBug = 15;		// 手机音乐没有 bug
	const eLogLoadBug = 16;			// 加载错误 bug
	const eLogMergeBug = 17;		// 手机不能融合 bug
	const eLogEatBug = 18;			// 手机不能吃 bug
	const eLogSimHitBullet = 19;	// 模拟集中日志
	const eLogTwoDTerrain = 20;		// 2D 地形
	const eLogPriorityListCheck = 21;// PriorityList 错误检查
	const eLogNoPriorityListCheck = 22;// NoPriorityList 错误检查

	const eLogPosSyn = 23;			// 服务器位置同步
	const eLogPlaneError = 24;		// 飞机错误
	const eLogDownload = 25;		// 下载资源
	const eLogAutoUpdate = 26;		// 自动更新
	const eLogProfileDebug = 27;	// 配置日志
	const eLogProfile = 28;			// 配置日志


	// 编辑器日志
	const eLogEditorBuildPlayer = 29;// 编辑器导出 Player 日志

	// Error
	const eErrorResLoader = 30;		// 资源加载
	const eErrorDownload = 31;		// 下载资源错误
	const eErrorFillIO = 32;		// File IO 操作错误
}

?>