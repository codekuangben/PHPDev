<?php

namespace MTest;

/**
 * @brief PHP 中成员变量的访问不允许有 . ,静态成员访问使用 :: 
 */
class TestSyntax extends TestBase
{
    public function __construct()
    {
        parent::__construct();
    }
    
    public function __destruct()
    {
        
    }
    
    public function init()
    {
        parent::init();
    }
    
    public function dispose()
    {
        parent::dispose();
    }
    
    public function run()
    {
        parent::run();
    }
}

?>