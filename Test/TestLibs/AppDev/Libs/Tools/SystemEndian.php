<?php

namespace SDK\Lib;

class SystemEndian
{
    public static $msLocalEndian;   // 本地字节序
	public static $msNetEndian;        // 网络字节序
	public static $msServerEndian;// 服务器字节序，规定服务器字节序就是网络字节序
	
	public static function init()
	{
	    SystemEndian::$msLocalEndian = MEndian::eLITTLE_ENDIAN;
	    SystemEndian::$msNetEndian = MEndian::eBIG_ENDIAN;
	    SystemEndian::$msServerEndian = MEndian::eLITTLE_ENDIAN;
	}
}

?>