<?php

namespace SDK\Lib;

class NetModuleDispatchHandle
{
	protected $mId2DispDic;
	
	public function __construct()
	{
		$this->mId2DispDic = new MDictionary();
	}

	public function init()
	{

	}

	public function dispose()
	{

	}

	public function addCmdHandle($cmdId, $callee, $handle)
	{
	    if (!$this->mId2DispDic->containsKey($cmdId))
		{
		    $this->mId2DispDic->add($cmdId, new AddOnceEventDispatch());
		}

		$this->mId2DispDic->value($cmdId)->addEventHandle($callee, $handle);
	}

	public function removeCmdHandle($cmdId, $calleeObj = null)
	{
	    if(!$this->mId2DispDic->containsKey($cmdId))
		{
		    
		}

		$this->mId2DispDic->value($cmdId)->removeEventHandle($calleeObj, null);
	}

	public function handleMsg($cmdDispInfo)
	{
	    if($this->mId2DispDic->containsKey($cmdDispInfo->byCmd))
		{                
		    $this->mId2DispDic->value($cmdDispInfo->byCmd)->dispatchEvent($cmdDispInfo);
		}
		else
		{
			
		}
	}
}

?>