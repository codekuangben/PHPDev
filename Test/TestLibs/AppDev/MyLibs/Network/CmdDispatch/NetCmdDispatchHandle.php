<?php

namespace MyLibs\Network\CmdDispatch;

use MyLibs\EventHandle\AddOnceEventDispatch;
use MyLibs\EventHandle\ICalleeObject;
use MyLibs\DataStruct\MDictionary;

class NetCmdDispatchHandle implements ICalleeObject
{
	protected $mId2HandleDic;

	public function __construct()
	{
		$this->mId2HandleDic = new MDictionary();
	}

	public function init()
	{

	}

	public function dispose()
	{

	}

	public function addParamHandle($paramId, $eventListener, $eventHandle)
	{
	    $eventDispatch = null;
	    
	    if(!$this->mId2HandleDic->containsKey($paramId))
		{
		    $eventDispatch = new AddOnceEventDispatch();
		    $this->mId2HandleDic->add($paramId, $eventDispatch);
		}
		else
		{
		    $eventDispatch = $this->mId2HandleDic->value($paramId);
		}

		$eventDispatch->addEventHandle($eventListener, $eventHandle, 0);
	}

	public function removeParamHandle($paramId, $eventListener, $eventHandle)
	{
	    if($this->mId2HandleDic->containsKey($paramId))
		{
		    $this->mId2HandleDic->value($paramId)->removeEventHandle(null, $eventListener, $eventHandle);
		}
		else
		{
		}
	}

	public function call($dispatchObject)
	{

	}

	public function handleMsg($cmd)
	{
	    if($this->mId2HandleDic->containsKey($cmd->ParamId))
		{
		    $this->mId2HandleDic->value($cmd->ParamId)->dispatchEvent($cmd->byteBuffer);
		}
		else
		{
			
		}
	}
}

?>