<?php

/**
 * @brief PHP发送POST请求的三种方式
 * @url https://www.cnblogs.com/linux-centos/p/5604511.html
 * @brief php发送http请求
 * @url https://www.cnblogs.com/simpman/p/3549816.html
 */
class UtilHttpRequest
{
    public static function post($url, $post_data = '', $timeout = 5)
    {
        //curl
        $curlEnv = curl_init();
        curl_setopt ($curlEnv, CURLOPT_URL, $url);
        curl_setopt ($curlEnv, CURLOPT_POST, 1);
        
        if($post_data != '')
        {
            curl_setopt($curlEnv, CURLOPT_POSTFIELDS, $post_data);
        }
        
        curl_setopt ($curlEnv, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt ($curlEnv, CURLOPT_CONNECTTIMEOUT, $timeout);
        curl_setopt($curlEnv, CURLOPT_HEADER, false);
        
        $file_contents = curl_exec($curlEnv);
        curl_close($curlEnv);
        
        return $file_contents;
    }
    
    public static function post2($url, $data)
    {
        //file_get_content
        $postdata = http_build_query(
            $data
            );

        $opts = array('http' =>
            array(
                'method'  => 'POST',
                'header'  => 'Content-type: application/x-www-form-urlencoded',
                'content' => $postdata
            )
        );

        $context = stream_context_create($opts);
        $result = file_get_contents($url, false, $context);

        return $result;
    }
    
    public static function post3($host,$path,$query,$others='')
    {
        //fsocket
        $post="POST $path HTTP/1.1\r\nHost: $host\r\n";
        $post.="Content-type: application/x-www-form-";
        $post.="urlencoded\r\n${others}";
        $post.="User-Agent: Mozilla 4.0\r\nContent-length: ";
        $post.=strlen($query)."\r\nConnection: close\r\n\r\n$query";
        
        $h=fsockopen($host,80);
        fwrite($h,$post);
        
        for($a=0,$r='';!$a;)
        {
            $b=fread($h,8192);
            $r.=$b;
            $a=(($b=='')?1:0);
        }

        fclose($h);
        
        return $r;
    }
}

?>