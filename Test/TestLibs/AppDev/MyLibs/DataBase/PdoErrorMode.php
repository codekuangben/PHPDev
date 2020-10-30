<?php

namespace MyLibs\DataBase;

class PdoErrorMode
{
    public const ERRMODE_SILENT = 0;  // 不显示错误
    public const ERRMODE_WARNING = 1;  // 显示警告错误，并继续执行
    public const ERRMODE_EXCEPTION = 2;  // 产生致命错误，PDOException
}

?>