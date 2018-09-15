<?php

namespace SDK\Test;

use SDK\Lib\EventDispatch;

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
        
        $this->testA();
    }
    
    protected function testA()
    {
        $aaa = new EventDispatch();
        $aaa->addEventHandle($this, "testB");
        //$aaa->addEventHandle(null, "TestEventDispatch::testB");
        $aaa->dispatchEvent(null);
    }
    
    protected function testB($dispObj, $eventId)
    {
        echo("aa");
    }
    
    protected static function testC($dispObj, $eventId)
    {
        echo("aa");
    }
}

?>