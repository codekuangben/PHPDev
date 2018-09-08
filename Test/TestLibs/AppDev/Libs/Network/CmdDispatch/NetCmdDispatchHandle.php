<?php

namespace SDK\Lib;

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

	public function addParamHandle($paramId, $handle)
	{
	    if(!$this->mId2HandleDic.containsKey($paramId))
		{
		    $this->mId2HandleDic[paramId] = new AddOnceEventDispatch();   
		}
		else
		{
		}

		$this->mId2HandleDic[paramId].addEventHandle(null, $handle);
	}

	public function removeParamHandle($paramId, $handle)
	{
	    if($this->mId2HandleDic.containsKey($paramId))
		{
		    $this->mId2HandleDic[$paramId].removeEventHandle(null, $handle);
		}
		else
		{
		}
	}

	public function call($dispObj)
	{

	}

	public function handleMsg($cmd)
	{
	    if($this->mId2HandleDic.containsKey($cmd.byParam))
		{
		    $this->mId2HandleDic[$cmd.byParam].dispatchEvent($cmd.bu);
		}
		else
		{
			
		}
	}
}

?>