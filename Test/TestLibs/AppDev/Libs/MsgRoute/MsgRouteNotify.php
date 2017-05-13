namespace SDK\Lib;
{
public class MsgRouteNotify
{
	protected MList<MsgRouteDispHandle> mDispList;

	public MsgRouteNotify()
	{
		$this->mDispList = new MList<MsgRouteDispHandle>();
	}

	public void addOneNofity(MsgRouteDispHandle disp)
	{
		if(!$this->mDispList.Contains(disp))
		{
			$this->mDispList.Add(disp);
		}
	}

	public void removeOneNotify(MsgRouteDispHandle disp)
	{
		if($this->mDispList.Contains(disp))
		{
			$this->mDispList.Remove(disp);
		}
	}

	public void handleMsg(MsgRouteBase msg)
	{
		int index = 0;
		int liseLen = $this->mDispList.Count();

		while(index < liseLen)
		{
			$this->mDispList[index].handleMsg(msg);

			++index;
		}

		// 暂时不用缓存，非资源数据结构重新申请内存应该不会太耗时
		//Ctx.mInstance.mPoolSys.deleteObj(msg);
	}
}
}