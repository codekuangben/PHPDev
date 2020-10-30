<?php

namespace MyLibs\FileVisitor;

class FileOpState
{
    public const eNoOp = 0;      // 无操作
    public const eOpening = 1;   // 打开中
    public const eOpenSuccess = 2;   // 打开成功
    public const eOpenFail = 3;      // 打开失败
    public const eOpenClose = 4;     // 关闭
}

?>