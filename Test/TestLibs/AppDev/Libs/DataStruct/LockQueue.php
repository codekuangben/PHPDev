<?php

namespace SDK\Lib;

public class LockQueue<T>
{
	protected LockList<T> mList;

	public LockQueue(string name)
	{
		$this->mList = new LockList<T>(name);
	}

	public void push(T item)
	{
		$this->mList.Add(item);
	}

	public T pop()
	{
		return mList.RemoveAt(0);
	}
}

?>