<?php

namespace MTest;

use MyLibs\Ctx;
use MyLibs\DataBaseType;

class TestDB extends TestBase
{
    public function run()
    {
        parent::run();
        
        $this->_testFunctionCall();
    }
    
    protected function _testFunctionCall()
    {
        Ctx::$msInstance->mDBPdo->setDataBaseType(DataBaseType::MYSQL);
        Ctx::$msInstance->mDBPdo->connect();
        
        $addSql = "insert into baseinfo(id,name)values(23,'Joe')";
        Ctx::$msInstance->mDBPdo->add($addSql);
        
        //$deleteSql = "DELETE FROM baseinfo WHERE id=23";
        //Ctx::$msInstance->mDBPdo->delete($deleteSql);
        
        $setSql = "UPDATE `baseinfo` SET `name`=aaaa WHERE `id`=23";
        Ctx::$msInstance->mDBPdo->set($setSql);
        
        //$findSql = "SELECT * FROM `baseinfo` WHERE `id`=23";
        //Ctx::$msInstance->mDBPdo->find($findSql);
        //while($row = Ctx::$msInstance->mDBPdo->getNextRow())
        //{
        //    print_r($row);
        //}
        
        //Ctx::$msInstance->mDBPdo->getNextRowOneByOne(null, null);
    }
}

?>