<?php

namespace SDK\Test;

use SDK\Lib\Ctx;
use SDK\Lib\DataBaseType;

class TestDB extends TestBase
{
    public function run()
    {
        parent::run();
        
        $this->_testFunctionCall();
    }
    
    protected function _testFunctionCall()
    {
        Ctx::$mInstance->mDBPdo->setDataBaseType(DataBaseType::MYSQL);
        Ctx::$mInstance->mDBPdo->connect();
        
        $addSql = "insert into baseinfo(id,name)values(23,'Joe')";
        Ctx::$mInstance->mDBPdo->add($addSql);
        
        $deleteSql = "DELETE FROM baseinfo WHERE id=23";
        Ctx::$mInstance->mDBPdo->delete($deleteSql);
        
        //$findSql = "insert into baseinfo(id,name)values(23,'Joe')";
        //Ctx::$mInstance->mDBPdo->add($addSql);
        
        //$setSql = "insert into baseinfo(id,name)values(23,'Joe')";
        //Ctx::$mInstance->mDBPdo->add($addSql);
    }
}

?>