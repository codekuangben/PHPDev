<?php

namespace SDK\Lib;

/**
 * @brief 生成一些需要的数据结构
 */
class FactoryBuild
{
	public function buildByteBuffer()
	{
		return new ByteBuffer();
	}
}

?>