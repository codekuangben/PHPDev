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
        
        //$deleteSql = "DELETE FROM baseinfo WHERE id=23";
        //Ctx::$mInstance->mDBPdo->delete($deleteSql);
        
        $setSql = "UPDATE `baseinfo` SET `name`=aaaa WHERE `id`=23";
        Ctx::$mInstance->mDBPdo->set($setSql);
        
        //$findSql = "SELECT * FROM `baseinfo` WHERE `id`=23";
        //Ctx::$mInstance->mDBPdo->find($findSql);
        //while($row = Ctx::$mInstance->mDBPdo->getNextRow())
        //{
        //    print_r($row);
        //}
        
        //Ctx::$mInstance->mDBPdo->getNextRowOneByOne(null, null);
    }
}

?>