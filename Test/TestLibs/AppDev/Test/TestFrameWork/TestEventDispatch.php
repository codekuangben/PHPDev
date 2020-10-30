<?php

namespace Test\TestFrameWork;

use MyLibs\EventHandle\EventDispatch;
use Test\Base\TestBase;

//public function globalHandle($param)
function globalHandle($param)
{
    echo("aa");
}


// 测试回调全局
// call_user_func('globalHandle', "asdf");  // 一定要加名字空间
//call_user_func('MTest\globalHandle', "asdf");

class TestEventDispatch extends TestBase
{
    public function __construct()
    {
        parent::__construct();
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
        
        $this->_testA();
        //$this->_testE();
    }

    protected function _testA()
    {
        $aaa = new EventDispatch();
        //$aaa->addEventHandle($this, "testB", 10);
        $aaa->addEventHandle("Test\TestFrameWork\TestEventDispatch", "testC");
        $aaa->dispatchEvent("asdfasdf");
    }
    
    //protected function testB($dispatchObject, $eventId)
    public function testB($dispatchObject, $eventId)
    {
        echo("aa");
    }
    
    //protected static function testC($dispatchObject, $eventId)
    public static function testC($dispatchObject, $eventId)
    {
        echo("aa");
    }
    
    protected function _testE()
    {
        // 测试静态回调
        // $classname = "TestEventDispatch";    // 一定要添加名字空间，否则报错
        $classname = "MTest\TestEventDispatch";
        // call_user_func($classname . '::testC', "ads", 555);
        call_user_func(array($classname, 'testC'), "ads", 555);
        
        // 测试类回调
        call_user_func(array($this,'testB'), "ads", 555);
        
        // 测试回调全局
        //call_user_func('globalHandle', "asdf");   // 一定要添加名字空间，否则报错
        call_user_func('MTest\globalHandle', "asdf");
    }
}

?>