﻿namespace SDK.Lib
{
    public class ProtoCV
    {
        public const int GAME_VERSION = 1999;
        public const int MAX_IP_LENGTH = 16;
        public const int MAX_ACCNAMESIZE = 48;
        public const int MAX_PASSWORD = 16;
        public const ushort MAX_CHARINFO = 3;
        public const int MAX_NAMESIZE = 32;
        public const int MAX_CHATINFO = 256;
    }

    public enum ERetResult
    {
        LOGIN_RETURN_UNKNOWN,   /// 未知错误
        LOGIN_RETURN_VERSIONERROR, /// 版本错误
        LOGIN_RETURN_UUID,     /// UUID登陆方式没有实现
        LOGIN_RETURN_DB,     /// 数据库出错
        LOGIN_RETURN_PASSWORDERROR,/// 帐号密码错误
        LOGIN_RETURN_CHANGEPASSWORD,/// 修改密码成功
        LOGIN_RETURN_IDINUSE,   /// ID正在被使用中
        LOGIN_RETURN_IDINCLOSE,   /// ID被封
        LOGIN_RETURN_GATEWAYNOTAVAILABLE,/// 网关服务器未开
        LOGIN_RETURN_USERMAX,   /// 用户满
        LOGIN_RETURN_ACCOUNTEXIST, /// 账号已经存在
        LOGON_RETURN_ACCOUNTSUCCESS,/// 注册账号成功

        LOGIN_RETURN_CHARNAMEREPEAT,/// 角色名称重复
        LOGIN_RETURN_USERDATANOEXIST,/// 用户档案不存在
        LOGIN_RETURN_USERNAMEREPEAT,/// 用户名重复
        LOGIN_RETURN_TIMEOUT,   /// 连接超时
        LOGIN_RETURN_PAYFAILED,   /// 计费失败
        LOGIN_RETURN_JPEG_PASSPORT, /// 图形验证码输入错误
        LOGIN_RETURN_LOCK,         /// 帐号被锁定
        LOGIN_RETURN_WAITACTIVE, /// 帐号待激活
        LOGIN_RETURN_NEWUSER_OLDZONE,      ///新账号不允许登入旧的游戏区 
        LOGIN_RETURN_CHARNAME_FORBID = 36,
    }
}