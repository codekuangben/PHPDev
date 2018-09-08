<?php

namespace SDK\Test;

require_once (dirname(__FILE__) . "/../Base/TestBase.php");
require_once (dirname(__FILE__) . "/../TestClass/TestClass.php");

class TestMain
{
    protected $mTestClass;
    
    public function __construct()
    {
        $this->mTestClass = new TestClass();
    }
    
    public function init()
    {
        $this->mTestClass->init();
    }
    
    public function dispose()
    {
        $this->mTestClass->dispose();
    }
    
    public function run()
    {
        $this->mTestClass->run();
    }
}

?>