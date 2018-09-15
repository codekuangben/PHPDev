<?php

namespace SDK\Lib;

class Singleton
{
	protected static $msSingleton;
	
	public static function setSingletonPtr($value)
	{
		$msSingleton = $value;
	}

	public static function getSingletonPtr()
	{
		return $msSingleton;
	}

	public static function deleteSingletonPtr()
	{
		if (null != $msSingleton)
		{
			$msSingleton->dispose();
			$msSingleton= null;
		}
	}
}

?>