<?php

namespace SDK\Lib;

class SystemEndian
{
	public const msLocalEndian = EEndian::eLITTLE_ENDIAN;   // 本地字节序
	public const msNetEndian = EEndian::eBIG_ENDIAN;        // 网络字节序
	public const msServerEndian = SystemEndian::msNetEndian;// 服务器字节序，规定服务器字节序就是网络字节序
}

?>