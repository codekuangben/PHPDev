<?php

namespace SDK\Test;

use SDK\Lib\SystemEnv;

require_once (dirname(__FILE__) . "/../Base/TestBase.php");
require_once (dirname(__FILE__) . "/../TestClass/TestClass.php");
require_once (SystemEnv::$MY_PHP_ROOT_PATH . "/Test/TestFrameWork/TestEventDispatch.php");

class TestMain
{
    protected $mTestClass;
    protected $mTestEventDispatch;
    
    public function __construct()
    {
        $this->mTestClass = new TestClass();
        $this->mTestEventDispatch = new TestEventDispatch();
    }
    
    public function init()
    {
        $this->mTestClass->init();
        $this->mTestEventDispatch->init();
    }
    
    public function dispose()
    {
        $this->mTestClass->dispose();
        $this->mTestEventDispatch->dispose();
    }
    
    public function run()
    {
        $this->mTestClass->run();
        $this->mTestEventDispatch->run();
    }
}

?>