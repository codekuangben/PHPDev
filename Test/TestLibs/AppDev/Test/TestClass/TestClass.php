<?php

namespace Test\TestClass;

use Test\Base\TestBase;

class TestClass extends TestBase
{
    // 静态成员变量名字必须 $ 开始
    protected static $TestStaticMember;
    // 常量成员变量名字不能以 $ 开始,并且需要赋值初始值
    protected const TestConstMember = 0;
    // 成员变量名字必须 $ 开始
    protected $TestMember;
    
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
        
        $this->_testFunctionCall();
        $this->_testFunctionParamDefaultValue($param1 = 20, $param = 10);
        $this->_testFunctionParamDefaultValue(20, 10);
    }
    
    protected function _testFunctionCall()
    {
        
    }
    
    protected function _testFunctionParamDefaultValue($param, $param1, $param2 = 10)
    {
        echo $param;
    }
}

?>