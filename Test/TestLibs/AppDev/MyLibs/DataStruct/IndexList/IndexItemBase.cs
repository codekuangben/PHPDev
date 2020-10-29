<?php

namespace MyLibs

public class IndexItemBase
{
	protected int mIndex;   // 索引

	public IndexItemBase()
	{
		this.mIndex = -1;
	}

	public int getIndex()
	{
		return this.mIndex;
	}

	public void setIndex(int value)
	{
		this.mIndex = value;
	}

	public void resetIndex()
	{
		this.mIndex = -1;
	}
}

?>