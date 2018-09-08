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
		$StreamingAssets = "StreamingAssets/";

		$this->mIp = "192.168.96.14";
		$this->mPort = 20013;
		$this->mZone = 30;

		$this->mWebIP = "http://127.0.0.1/UnityServer/";
		$this->mWebPort = 80;
		$this->mNetLogPhp = "/netlog/NetLog.php";
		$this->mPakExtNameList = new MList();

		$this->mResLoadType = ResLoadType.eLoadResource;
		$this->mPathLst = new MList();
		$this->mPathLst[(int)ResPathType.ePathScene] = "Scenes/";
		$this->mPathLst[(int)ResPathType.ePathSceneXml] = "Scenes/Xml/";
		$this->mPathLst[(int)ResPathType.ePathModule] = "Module/";
		$this->mPathLst[(int)ResPathType.ePathComUI] = "UI/";
		$this->mPathLst[(int)ResPathType.ePathComUIScene] = "UIScene/";
		$this->mPathLst[(int)ResPathType.ePathBeingPath] = "Being/";
		$this->mPathLst[(int)ResPathType.ePathAIPath] = "AI/";
		$this->mPathLst[(int)ResPathType.ePathTablePath] = "Table/";
		$this->mPathLst[(int)ResPathType.ePathLangXml] = "Languages/";
		$this->mPathLst[(int)ResPathType.ePathXmlCfg] = "XmlConfig/";
		$this->mPathLst[(int)ResPathType.ePathModel] = "Model/";
		$this->mPathLst[(int)ResPathType.ePathMaterial] = "Model/Materials/";
		$this->mPathLst[(int)ResPathType.ePathBuildImage] = "Image/Build/";
		$this->mPathLst[(int)ResPathType.ePathCardImage] = "Image/Card/";
		$this->mPathLst[(int)ResPathType.ePathWord] = "Word/";
		$this->mPathLst[(int)ResPathType.ePathAudio] = "Sound/";
		$this->mPathLst[(int)ResPathType.ePathAtlas] = "Atlas/";
		$this->mPathLst[(int)ResPathType.ePathSpriteAni] = "Effect/SpriteEffect/";
		$this->mPathLst[(int)ResPathType.ePathSceneAnimatorController] = "Animation/Scene/";
		$this->mPathLst[(int)ResPathType.ePathULua] = "LuaScript/";
		$this->mPathLst[(int)ResPathType.ePathLuaScript] = "LuaScript/";
		$this->mPathLst[(int)ResPathType.ePathSkillAction] = "SkillAction/";

		$this->mDataPath = Application.dataPath;

		$this->mPakExtNameList.Add("prefab");
		$this->mPakExtNameList.Add("png");
		$this->mPakExtNameList.Add("shader");
		$this->mPakExtNameList.Add("unity");

		$this->mIsActorMoveUseFixUpdate = false;
		$this->mDownloadURL = "git5.club";
	}
}

?>