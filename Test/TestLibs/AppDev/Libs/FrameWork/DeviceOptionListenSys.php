using System;
using UnityEngine;

namespace SDK\Lib;
{
/**
 * @brief Android返回键
 */
public class DeviceOptionListenSys:MonoBehaviour
{
	public float first = 0;
	public float second = 0;
	public int times = 0;//次数

	public DeviceOptionListenSys()
	{
		
	}

	public void init()
	{
		Ctx.mInstance.mInputMgr.mOnDeviceOpDispatch.addEventHandle(null, $this->QuitApp);
	}

	private void QuitApp(IDispatchObject dispObj)
	{
		times += 1;//按一次就加一次
		if (times == 1)//记录第一次按下返回键的时间
		{
			first = Time.time;
			Ctx.mInstance.mLuaSystem.receiveToLua_KBE("notifySomeMessage", new object[] { "再次点击返回键退出" });
		}

		if (times == 2)
		{
			second = Time.time;//记录第二次按下返回键的时间
			times = 0;
			if (second - first <= 1f)
			{
				//第二次 减 第一次的时间在1秒内，就执行
				Application.Quit();//退出
			}
		}
	}
	
	public void dispose()
	{
		Ctx.mInstance.mInputMgr.mOnDeviceOpDispatch.removeEventHandle(null, $this->QuitApp);
	}
}
}