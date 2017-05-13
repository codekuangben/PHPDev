<?php

namespace SDK\Lib;

public class FixedTickMgr : TickMgr
{
	public FixedTickMgr()
	{

	}

	override protected void onExecAdvance(float delta, TickMode tickMode)
	{
		base.onExecAdvance(delta, tickMode);
	}
}

?>