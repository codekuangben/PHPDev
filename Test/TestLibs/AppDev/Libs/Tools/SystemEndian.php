<?php

namespace SDK\Lib;

class SystemEndian
{
    public static $msLocalEndian = MEndian::eLITTLE_ENDIAN;   // 本地字节序
	public static $msNetEndian = MEndian::eBIG_ENDIAN;        // 网络字节序
	public static $msServerEndian = SystemEndian::msNetEndian;// 服务器字节序，规定服务器字节序就是网络字节序
}

?>