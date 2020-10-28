<?php

namespace SDK\Lib;

/**
 * @brief Tick 的优先级
 * @brief TP TickPriority 缩写
 */
class TickPriority
{
	const eTPPlayerMgr = 10;       // PlayerMgr
	const eTPSnowBlockMgr = 10;    // SnowBlockMgr
	const eTPAbandonPlaneMgr = 10;    // AbandonPlaneMgr
	const eTPComputerBallMgr = 10;    // TPComputerBallMgr
	const eTPRobotMgr = 10;        // RobotMgr
	const eTPFlyBulletMgr = 10;        // FlyBulletMgr
	const eTPCamController = 10;   // 相机控制器
	const eTPInputMgr = 10000;   // 相机控制器
	const eTPResizeMgr = 100000;   // 窗口大小改变
	const eTPJoyStick = 1;   // 摇杆控制器
	const eTPForwardForce = 1;   // 反重力控制器
	const eTPCameraMgr = 1;   // 相机位置更新控制器
	const eTPDelayTaskMgr = 1;   // 延迟任务
	const eTPLoadProgressMgr = 1;   // 更新加载进度
	const eTPSoundLoadStateCheckMgr = 1;   // 更新音乐状态检查
	const eTPClipRect = 1;
}

?>