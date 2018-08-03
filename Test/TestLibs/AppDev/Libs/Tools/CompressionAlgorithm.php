<?php

namespace SDK\Lib;

class CompressionAlgorithm
{
    public const DEFLATE = 0;            // 这个好像也是使用的是 ZLIB 这个压缩算法
    public const ZLIB = 1;               // ZLIB 压缩算法
    public const LZMA = 2;               // 7Z 默认使用的压缩算法
}

?>