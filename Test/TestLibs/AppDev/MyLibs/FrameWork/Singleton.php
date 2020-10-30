<?php

namespace MyLibs\FrameWork;

class Singleton
{
	protected static $msSingleton;
	
	public static function setSingletonPtr($value)
	{
	    Singleton::$msSingleton = $value;
	}

	public static function getSingletonPtr()
	{
	    return Singleton::$msSingleton;
	}

	public static function deleteSingletonPtr()
	{
	    if (null != Singleton::$msSingleton)
		{
		    Singleton::$msSingleton->dispose();
		    Singleton::$msSingleton= null;
		}
	}
}

?>