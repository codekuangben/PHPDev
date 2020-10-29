<?php

namespace MyLibs;

class MsgRouteBase implements IDispatchObject
{
	public $mMsgType;
	public $mMsgId;          // 只需要一个 ID 就行了
	public $mIsMainThreadImmeHandle;    // 是否主线程立即处理消息

	public function __construct($id, $type = MsgRouteType::eMRT_BASIC)
	{
		$this->mMsgType = $type;
		$this->mMsgId = $id;

		$this->mIsMainThreadImmeHandle = true;
	}

	public function resetDefault()
	{

	}

	public function setIsMainThreadImmeHandle($value)
	{
		$this->mIsMainThreadImmeHandle = $value;
	}

	public function isMainThreadImmeHandle()
	{
		return $this->mIsMainThreadImmeHandle;
	}
}

?>