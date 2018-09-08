<?php

namespace SDK\Lib;

class MacroDef
{
// Release 自己使用的定义
//NET_MULTHREAD;MSG_ENCRIPT;MSG_COMPRESS

// Debug 自己使用的定义
//NET_MULTHREAD;MSG_ENCRIPT;MSG_COMPRESS;THREAD_CALLCHECK;ENABLE_WINLOG;ENABLE_NETLOG;UNIT_TEST;ENABLE_FILELOG

// 宏定义开始
// 调试不需要网络
const DEBUG_NOTNET = false;

// 网络处理多线程，主要是调试的时候使用单线程，方便调试，运行的时候使用多线程
const NET_MULTHREAD = true;

// 是否检查函数接口调用线程
const THREAD_CALLCHECK = true;

// 消息加密
const MSG_ENCRIPT = false;

// 消息压缩
const MSG_COMPRESS = false;

// 开启日志
const ENABLE_LOG = true;

// 开启警告
const ENABLE_WARN = false;

// 开启错误
const ENABLE_ERROR = false;

// 开启窗口日志
const ENABLE_WINLOG = true;

// 开启网络日志
const ENABLE_NETLOG = false;

// 开启文件日志
const ENABLE_FILELOG = false;

// 使用打包模式加载资源
const PKG_RES_LOAD = false;

// 非打包文件系统资源加载
const UNPKG_RES_LOAD = false;

// 是否开启 EnableProtoBuf
const ENABLE_PROTOBUF = false;

// 是否开启 SharpZipLib
const ENABLE_SHARP_ZIP_LIB = false;

// 单元测试，这个需要宏定义
const UNIT_TEST = false;

// 不使用的代码
const DEPRECATE_CODE = false;

// 多线程裁剪场景
const MULTITHREADING_CULL = false;

// Lua 加载方式
const LUA_EDITOR = true;

// 绘制调试信息
const DRAW_DEBUG = false;

const ENABLE_BUGLY = false;

// 坐标模式
const XZ_MODE = false;
const XY_MODE = true;

// 物理运行
const PHYSIX_MOVE = false;

// 场景裁剪
const ENABLE_SCENE2D_CLIP = true;

// Lua 控制台
const ENABLE_LUA_CONSOLE = false;

// 关闭测试场景
const DISABLE_TEST_SCENE = true;

// 热更新
const ENABLE_HOT_UPDATE = false;

const MOBILE_PLATFORM = false;

// Profile 
const ENABLE_PROFILE = true;

// 宏定义结束
}

?>