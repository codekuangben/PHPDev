<?php

namespace SDK\Test;

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