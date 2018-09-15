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

// 是否开启 EnableProtoBuf
const ENABLE_PROTOBUF = false;

// 是否开启 SharpZipLib
const ENABLE_SHARP_ZIP_LIB = false;

// 单元测试，这个需要宏定义
const UNIT_TEST = true;

// Profile 
const ENABLE_PROFILE = false;

// Profile
const ENABLE_LOOP = true;

// 宏定义结束
}

?>