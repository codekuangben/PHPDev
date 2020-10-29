<?php

namespace MyLibs;

class NetCmdNotify
{
	protected $mRevMsgCnt;      // 接收到消息的数量
	protected $mHandleMsgCnt;   // 处理的消息的数量

	protected $mNetModuleDispatchList;
	protected $mIsStopNetHandle;       // 是否停止网络消息处理
	protected $mCmdDispInfo;

	public function __construct()
	{
		$this->mRevMsgCnt = 0;
		$this->mHandleMsgCnt = 0;
		$this->mNetModuleDispatchList = new MList();
		$this->mIsStopNetHandle = false;
		$this->mCmdDispInfo = new CmdDispatchInfo();
	}

	public function isStopNetHandle()
	{
		return $this->mIsStopNetHandle;
	}
	
	public function setIsStopNetHandle($value)
	{
	    $this->mIsStopNetHandle = $value;
	}

	public function addOneNofity($dispatchObject)
	{
	    if ($this->mNetModuleDispatchList->indexOf($dispatchObject) == -1)
		{
		    $this->mNetModuleDispatchList->add($dispatchObject);
		}
	}

	public function removeOneNotify($dispatchObject)
	{
	    if ($this->mNetModuleDispatchList->indexOf($dispatchObject) != -1)
		{
		    $this->mNetModuleDispatchList->remove($dispatchObject);
		}
	}

	public function handleMsg($msg)
	{
		//if (false == mIsStopNetHandle)  // 如果没有停止网络处理
		//{
		//$CmdId = 0;
		//$msg->readUnsignedInt8($CmdId);
		//$ParamId = 0;
		//$msg->readUnsignedInt8($ParamId);
		//$msg->setPos(0);

		$this->mCmdDispInfo->bu = $msg;
		$this->mCmdDispInfo->CmdId = $msg->CmdId;
		$this->mCmdDispInfo->ParamId = $msg->ParamId;

		$index = 0;
		$listLen = $this->mNetModuleDispatchList->count();
		$item = null;
		
		while ($index < $listLen)
		{
		    $item = $this->mNetModuleDispatchList->get($index);
			$item->handleMsg($this->mCmdDispInfo);
			
			$index += 1;
		}
		//}
	}

	public function addOneRevMsg()
	{
	    ++$this->mRevMsgCnt;            
	}

	public function addOneHandleMsg()
	{
		++$this->mHandleMsgCnt;
	}

	public function clearOneRevMsg()
	{
		$this->mRevMsgCnt = 0;
	}

	public function clearOneHandleMsg()
	{
		$this->mHandleMsgCnt = 0;
	}
}

?>