<?php

namespace MyLibs\Network\CmdDispatch;

use MyLibs\DataStruct\MDictionary;
use MyLibs\EventHandle\AddOnceEventDispatch;

class NetModuleDispatchHandle
{
	protected $mId2DispatchDic;
	
	public function __construct()
	{
		$this->mId2DispatchDic = new MDictionary();
	}

	public function init()
	{

	}

	public function dispose()
	{

	}

	public function addCmdHandle($cmdId, $eventListener, $eventHandle)
	{
	    if (!$this->mId2DispatchDic->containsKey($cmdId))
		{
		    $this->mId2DispatchDic->add($cmdId, new AddOnceEventDispatch());
		}

		$this->mId2DispatchDic->value($cmdId)->addEventHandle($eventListener, $eventHandle);
	}

	public function removeCmdHandle($cmdId, $eventListener = null)
	{
	    if(!$this->mId2DispatchDic->containsKey($cmdId))
		{
		    
		}

		$this->mId2DispatchDic->value($cmdId)->removeEventHandle($eventListener, null);
	}

	public function handleMsg($cmdDispatchInfo)
	{
	    if($this->mId2DispatchDic->containsKey($cmdDispatchInfo->CmdId))
		{                
		    $this->mId2DispatchDic->value($cmdDispatchInfo->CmdId)->dispatchEvent($cmdDispatchInfo);
		}
		else
		{
			
		}
	}
}

?>