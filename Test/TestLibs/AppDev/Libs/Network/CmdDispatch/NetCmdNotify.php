<?php

namespace SDK\Lib;

class NetCmdNotify
{
	protected $mRevMsgCnt;      // 接收到消息的数量
	protected $mHandleMsgCnt;   // 处理的消息的数量

	protected $mNetModuleDispList;
	protected $mIsStopNetHandle;       // 是否停止网络消息处理
	protected $mCmdDispInfo;

	public function __construct()
	{
		$this->mRevMsgCnt = 0;
		$this->mHandleMsgCnt = 0;
		$this->mNetModuleDispList = new MList();
		$this->mIsStopNetHandle = false;
		$this->mCmdDispInfo = new CmdDispInfo();
	}

	public function isStopNetHandle()
	{
		return $this->mIsStopNetHandle;
	}
	
	public function setIsStopNetHandle($value)
	{
	    $this->mIsStopNetHandle = $value;
	}

	public function addOneNofity($disp)
	{
		if ($this->mNetModuleDispList.indexOf($disp) == -1)
		{
			$this->mNetModuleDispList.add($disp);
		}
	}

	public function removeOneNotify($disp)
	{
		if ($this->mNetModuleDispList.indexOf($disp) != -1)
		{
			$this->mNetModuleDispList.remove($disp);
		}
	}

	public function handleMsg($msg)
	{
		//if (false == mIsStopNetHandle)  // 如果没有停止网络处理
		//{
		$byCmd = 0;
		$msg.readUnsignedInt8($byCmd);
		$byParam = 0;
		$msg.readUnsignedInt8($byParam);
		$msg.setPos(0);

		$mCmdDispInfo->bu = $msg;
		$mCmdDispInfo->byCmd = $byCmd;
		$mCmdDispInfo->byParam = $byParam;

		$index = 0;
		$listLen = $this->mNetModuleDispList.count();
		$item = null;
		
		while ($index < $listLen)
		{
		    $item = $this->mNetModuleDispList.get($index);
			$item.handleMsg($this->mCmdDispInfo);
			
			$index += 1;
		}
		//}
	}

	public function addOneRevMsg()
	{
	    ++$this->$mRevMsgCnt;            
	}

	public function addOneHandleMsg()
	{
		++$this->$mHandleMsgCnt;
	}

	public function clearOneRevMsg()
	{
		$this->$mRevMsgCnt = 0;
	}

	public function clearOneHandleMsg()
	{
		$this->$mHandleMsgCnt = 0;
	}
}

?>