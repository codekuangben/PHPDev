﻿<?php

namespace SDK\Lib;

/**
 * @brief 资源实例化事件分发器
 */
class ResInsEventDispatch extends EventDispatch implements IDispatchObject
{
	protected $mIsValid;
	protected $mInsGO;

	public function __construct()
	{
		$this->mIsValid = true;
	}

	public function setIsValid($value)
	{
		$this->mIsValid = value;
	}

	public function getIsValid()
	{
		return $this->mIsValid;
	}

	public function setInsGO($go)
	{
		$this->mInsGO = go;
	}

	public function getInsGO()
	{
		return $this->mInsGO;
	}

	public function dispatchEvent($dispatchObject)
	{
		if($this->mIsValid)
		{
			parent::dispatchEvent($dispatchObject);
		}
		else
		{
			UtilApi.Destroy($this->mInsGO);
			$this->mInsGO = null;
		}
	}
}

?>