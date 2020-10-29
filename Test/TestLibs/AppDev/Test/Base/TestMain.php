<?php

namespace MTest;

use MyLibs\SystemEnv;

require_once (dirname(__FILE__) . "/../Base/TestBase.php");
require_once (dirname(__FILE__) . "/../TestClass/TestClass.php");
require_once (SystemEnv::$MY_PHP_ROOT_PATH . "/Test/TestFrameWork/TestEventDispatch.php");
require_once (SystemEnv::$MY_PHP_ROOT_PATH . "/Test/TestDB/TestDB.php");

class TestMain
{
    protected $mTestClass;
    protected $mTestEventDispatch;
    protected $mTestDB;
    
    public function __construct()
    {
        $this->mTestClass = new TestClass();
        $this->mTestEventDispatch = new TestEventDispatch();
        $this->mTestDB = new TestDB();
    }
    
    public function init()
    {
        $this->mTestClass->init();
        $this->mTestEventDispatch->init();
        $this->mTestDB->init();
    }
    
    public function dispose()
    {
        $this->mTestClass->dispose();
        $this->mTestEventDispatch->dispose();
        $this->mTestDB->dispose();
    }
    
    public function run()
    {
        $this->mTestClass->run();
        $this->mTestEventDispatch->run();
        $this->mTestDB->run();
    }
}

?>