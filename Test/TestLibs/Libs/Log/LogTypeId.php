namespace SDK.Lib
{
    public enum LogTypeId
    {
        eLogCommon,         // 通用日志
        eLogSceneCull,      // 场景裁剪日志
        eLogMSceneManager,  // 场景管理器
        eLogTest,           // 测试日志
        eLogTestRL,         // 测试资源加载
        eLogResLoader,      // 资源加载
        eLogLocalFile,      // 本地文件系统， MDataStream
        eLogAcceleration,   // 重力
        eLogSplitMergeEmit, // 分裂融合
        eLogSceneInterActive,   // 场景交互
        eLogBeingMove,      // Being 移动
        eLogKBE,            // KBEngine 日志
        eLogScene,          // Scene 日志
        eLogUnityCB,        // Unity回调日志
        eLogEventRemove,    // 事件移除日志
        eLogMusicBug,       // 手机音乐没有 bug
        eLogLoadBug,        // 加载错误 bug
        eLogMergeBug,       // 手机不能融合 bug
        eLogEatBug,         // 手机不能吃 bug
        eLogSimHitBullet,   // 模拟集中日志
        eLogTwoDTerrain,    // 2D 地形
        eLogPriorityListCheck,  // PriorityList 错误检查
        eLogNoPriorityListCheck,// NoPriorityList 错误检查

        eLogPosSyn,         // 服务器位置同步
        eLogPlaneError,     // 飞机错误
        eLogDownload,       // 下载资源
        eLogAutoUpdate,     // 自动更新
        eLogProfileDebug,        // 配置日志
        eLogProfile,        // 配置日志


        // 编辑器日志
        eLogEditorBuildPlayer,      // 编辑器导出 Player 日志

        // Error
        eErrorResLoader,    // 资源加载
        eErrorDownload,     // 下载资源错误
        eErrorFillIO,       // File IO 操作错误
    }
}