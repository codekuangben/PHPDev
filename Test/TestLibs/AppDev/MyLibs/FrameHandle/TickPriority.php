<?php

namespace MyLibs;

/**
 * @brief Tick 的优先级
 * @brief TP TickPriority 缩写
 */
class TickPriority
{
	public const eTPPlayerMgr = 10;       // PlayerMgr
	public const eTPSnowBlockMgr = 10;    // SnowBlockMgr
	public const eTPAbandonPlaneMgr = 10;    // AbandonPlaneMgr
	public const eTPComputerBallMgr = 10;    // TPComputerBallMgr
	public const eTPRobotMgr = 10;        // RobotMgr
	public const eTPFlyBulletMgr = 10;        // FlyBulletMgr
	public const eTPCamController = 10;   // 相机控制器
	public const eTPInputMgr = 10000;   // 相机控制器
	public const eTPResizeMgr = 100000;   // 窗口大小改变
	public const eTPJoyStick = 1;   // 摇杆控制器
	public const eTPForwardForce = 1;   // 反重力控制器
	public const eTPCameraMgr = 1;   // 相机位置更新控制器
	public const eTPDelayTaskMgr = 1;   // 延迟任务
	public const eTPLoadProgressMgr = 1;   // 更新加载进度
	public const eTPSoundLoadStateCheckMgr = 1;   // 更新音乐状态检查
	public const eTPClipRect = 1;
}

?>