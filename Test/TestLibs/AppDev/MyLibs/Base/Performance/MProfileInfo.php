<?php

namespace MyLibs;

class MProfileInfo
{
	public $mName;
	public $mChildren;
	public $mParent;

	public $mStartTime;       // 进入块开始时间
	public $mTotalTime;       // 当前块总共执行时间,包括子函数调用
	public $mSelfTime;        // 当前块自己执行时间,不包括子函数调用
	public $mActivations;        // 调用次数
	public $mMaxTime;         // 最大一次执行时间
	public $mMinTime;         // 最小一次执行时间

	public function __construct($name, $parent = null)
	{
		$this->mChildren = new MDictionary();
		$this->mName = $name;
		$this->mParent = $parent;
	}

	public function wipe()
	{
		if(MacroDef::ENABLE_LOG)
		{
			Ctx::$msIns->mLogSys->log(string.Format("MProfileInfo::wipe, name = {0}", mName), LogTypeId::eLogProfileDebug);
		}

		$this->mStartTime = 0;
		$this->mTotalTime = 0;
		$this->mSelfTime = 0;
		$this->mActivations = 0;
		// 如果清除后，从没有进入这个块，那么 maxTime 值就是 int.MinValue，minTime 值就是  int.MaxValue
		$this->mMaxTime = PHP_INT_MAX;
		$this->mMinTime = PHP_INT_MAX;
	}
}

?>