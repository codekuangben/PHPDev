<?php

/**
 * @brief unity3d和php后台简单交互--一
 * @url https://www.cnblogs.com/fyluyg/p/5865687.html 
 */

if(isset($_GET["id"])
    && isset($_GET["cid"]))
{
    echo("get请求成功,id值为:".$_GET["id"].",cid值为:".$_GET["cid"]);
}

?>