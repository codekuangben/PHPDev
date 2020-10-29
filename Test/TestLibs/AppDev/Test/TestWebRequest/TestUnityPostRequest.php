<?php

/**
 * @brief unity3d和php后台简单交互--一
 * @url https://www.cnblogs.com/fyluyg/p/5865687.html
 */

if(isset($_POST["id"])
    && isset($_POST["cid"]))
{
    echo "post请求成功,id值为:".$_POST["id"].",cid值为:".$_POST["cid"];
} 

?>