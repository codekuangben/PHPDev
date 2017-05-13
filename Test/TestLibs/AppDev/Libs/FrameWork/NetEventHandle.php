using GameBox.Framework;
using GameBox.Service.GiantLightServer;

namespace SDK.Lib
{
/**
 * @brief 不依赖模块的网络事件处理
 */
public class NetEventHandle
{
	public NetEventHandle()
	{

	}

	public void init()
	{

	}

	public void dispose()
	{

	}

	public void Disconnect()
	{
		Ctx.mInstance.mCamSys.getCameraController().refsetOrthographicSize();
		Ctx.mInstance.mSoundMgr.stop("Sound/Music/StudioEIM-myseabed.mp3");

		Ctx.mInstance.mLightServer_GB.Disconnect();
	}

	public void OnDisconnect()
	{
		$this->Disconnect();
		Ctx.mInstance.mLuaSystem.receiveToLua_KBE("notifyNetworkInvalid", null);
		if(null != Ctx.mInstance.mPlayerMgr.getHero())
			(Ctx.mInstance.mPlayerMgr.getHero().mMovement as BeingEntityMovement).Stop = true;
	}
}
}