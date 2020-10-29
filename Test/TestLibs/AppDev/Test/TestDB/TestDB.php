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
        Ctx::$msIns->mDBPdo->setDataBaseType(DataBaseType::MYSQL);
        Ctx::$msIns->mDBPdo->connect();
        
        $addSql = "insert into baseinfo(id,name)values(23,'Joe')";
        Ctx::$msIns->mDBPdo->add($addSql);
        
        //$deleteSql = "DELETE FROM baseinfo WHERE id=23";
        //Ctx::$msIns->mDBPdo->delete($deleteSql);
        
        $setSql = "UPDATE `baseinfo` SET `name`=aaaa WHERE `id`=23";
        Ctx::$msIns->mDBPdo->set($setSql);
        
        //$findSql = "SELECT * FROM `baseinfo` WHERE `id`=23";
        //Ctx::$msIns->mDBPdo->find($findSql);
        //while($row = Ctx::$msIns->mDBPdo->getNextRow())
        //{
        //    print_r($row);
        //}
        
        //Ctx::$msIns->mDBPdo->getNextRowOneByOne(null, null);
    }
}

?>