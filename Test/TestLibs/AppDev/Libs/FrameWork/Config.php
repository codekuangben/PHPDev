<?php

namespace SDK\Lib;

/**
 * @brief 宏定义说明区域
 */

/**
 * @brief 基本的配置
 */
class Config
{
	public static $StreamingAssets;

	public $mIp;
	public $mPort;
	public $mZone;

	public $mWebIP;               // web 服务器
	public $mWebPort;

	public $mPathLst;
	public $mResLoadType;   // 资源加载类型
	public $mDataPath;
	//public bool m_bNeedNet = false;                       // 是否需要网络
	public $mNetLogPhp;       // Php 处理文件
	public $mPakExtNameList;       // 打包的扩展名字列表

	public $mIsActorMoveUseFixUpdate;    // Actor 移动是否使用固定更新，主要是方便参与物理运算
	public $mDownloadURL;

	public function __construct()
	{
	    Config::$StreamingAssets = "StreamingAssets/";

		$this->mIp = "192.168.96.14";
		$this->mPort = 20013;
		$this->mZone = 30;

		$this->mWebIP = "http://127.0.0.1/UnityServer/";
		$this->mWebPort = 80;
		$this->mNetLogPhp = "/netlog/NetLog.php";
		$this->mPakExtNameList = new MList();

		$this->mPathList = new MList();
		$this->mPathList->add("Scenes/");
		
		$this->mPakExtNameList->add("prefab");
		$this->mPakExtNameList->add("png");
		$this->mPakExtNameList->add("shader");
		$this->mPakExtNameList->add("unity");

		$this->mIsActorMoveUseFixUpdate = false;
		$this->mDownloadURL = "git5.club";
	}
}

?>