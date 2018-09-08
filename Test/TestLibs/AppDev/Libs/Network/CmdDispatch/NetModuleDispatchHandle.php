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
	    if (!$this->mId2DispDic.ContainsKey($cmdId))
		{
		    $this->mId2DispDic[cmdId] = new AddOnceEventDispatch();
		}

		$this->mId2DispDic[cmdId].addEventHandle($callee, $handle);
	}

	public function removeCmdHandle($cmdId, $calleeObj = null)
	{
	    if(!$this->mId2DispDic.ContainsKey($cmdId))
		{
		    
		}

		$this->mId2DispDic[$cmdId].removeEventHandle($calleeObj, null);
	}

	public function handleMsg($cmdDispInfo)
	{
	    if($this->mId2DispDic.ContainsKey($cmdDispInfo.byCmd))
		{                
		    $this->mId2DispDic[$cmdDispInfo.byCmd].dispatchEvent($cmdDispInfo);
		}
		else
		{
			
		}
	}
}

?>