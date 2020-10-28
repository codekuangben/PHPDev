<?php

namespace MyLibs;

class ProtoCV
{
	public const GAME_VERSION = 1999;
	public const MAX_IP_LENGTH = 16;
	public const MAX_ACCNAMESIZE = 48;
	public const MAX_PASSWORD = 16;
	public const MAX_CHARINFO = 3;
	public const MAX_NAMESIZE = 32;
	public const MAX_CHATINFO = 256;
}

class ERetResult
{
    public const LOGIN_RETURN_UNKNOWN = 0;   /// 未知错误
    public const LOGIN_RETURN_VERSIONERROR = 0; /// 版本错误
    public const LOGIN_RETURN_UUID = 0;     /// UUID登陆方式没有实现
    public const LOGIN_RETURN_DB = 0;     /// 数据库出错
    public const LOGIN_RETURN_PASSWORDERROR = 0;/// 帐号密码错误
    public const LOGIN_RETURN_CHANGEPASSWORD = 0;/// 修改密码成功
    public const LOGIN_RETURN_IDINUSE = 0;   /// ID正在被使用中
    public const LOGIN_RETURN_IDINCLOSE = 0;   /// ID被封
    public const LOGIN_RETURN_GATEWAYNOTAVAILABLE = 0;/// 网关服务器未开
    public const LOGIN_RETURN_USERMAX = 0;   /// 用户满
    public const LOGIN_RETURN_ACCOUNTEXIST = 0; /// 账号已经存在
    public const LOGON_RETURN_ACCOUNTSUCCESS = 0;/// 注册账号成功

    public const LOGIN_RETURN_CHARNAMEREPEAT = 0;/// 角色名称重复
    public const LOGIN_RETURN_USERDATANOEXIST = 0;/// 用户档案不存在
    public const LOGIN_RETURN_USERNAMEREPEAT = 0;/// 用户名重复
    public const LOGIN_RETURN_TIMEOUT = 0;   /// 连接超时
    public const LOGIN_RETURN_PAYFAILED = 0;   /// 计费失败
    public const LOGIN_RETURN_JPEG_PASSPORT = 0; /// 图形验证码输入错误
    public const LOGIN_RETURN_LOCK = 0;         /// 帐号被锁定
    public const LOGIN_RETURN_WAITACTIVE = 0; /// 帐号待激活
    public const LOGIN_RETURN_NEWUSER_OLDZONE = 0;      ///新账号不允许登入旧的游戏区 
    public const LOGIN_RETURN_CHARNAME_FORBID = 36;
}

?>