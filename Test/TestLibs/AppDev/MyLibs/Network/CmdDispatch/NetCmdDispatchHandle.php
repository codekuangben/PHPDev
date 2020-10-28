<?php

namespace MyLibs;

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

	public function addParamHandle($paramId, $pThis, $handle)
	{
	    $dispatch = null;
	    
	    if(!$this->mId2HandleDic->containsKey($paramId))
		{
		    $dispatch = new AddOnceEventDispatch();
		    $this->mId2HandleDic->add($paramId, $dispatch);
		}
		else
		{
		    $dispatch = $this->mId2HandleDic->value($paramId);
		}

		$dispatch->addEventHandle($pThis, $handle, 0);
	}

	public function removeParamHandle($paramId, $pThis, $handle)
	{
	    if($this->mId2HandleDic->containsKey($paramId))
		{
		    $this->mId2HandleDic->value($paramId)->removeEventHandle(null, $pThis, $handle);
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
	    if($this->mId2HandleDic->containsKey($cmd->byParam))
		{
		    $this->mId2HandleDic->value($cmd->byParam)->dispatchEvent($cmd->bu);
		}
		else
		{
			
		}
	}
}

?>