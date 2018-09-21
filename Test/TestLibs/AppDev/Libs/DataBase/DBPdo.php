<?php

namespace SDK\Lib;

use PDO;
use Exception;
use PDOException;

class DBPdo
{
    protected $mDataBaseType;
    protected $mDataBaseSetting;
    protected $mNativePdo;
    
    public function __construct()
    {
        $this->mDataBaseSetting = new DataBaseSetting();
    }
    
    public function __destruct()
    {
        
    }
    
    public function __clone()
    {
        
    }
    
    public function setDataBaseType($value)
    {
        $this->mDataBaseType = $value;
        $this->mDataBaseSetting->setDataBaseType($this->mDataBaseType);
    }
    
    public function init()
    {
        
    }
    
    public function connect()
    {
        try
        {
            $dsn = $this->mDataBaseSetting->getDsn();
            $userName = getUserName();
            $passWord = getPassWord();
            
            $this->mNativePdo = new PDO(
                $dsn, 
                $userName, 
                $passWord, 
                array(PDO::ATTR_PERSISTENT => true, PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")
            ); //保持长连接
            
            $this->mNativePdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
        }
        catch (PDOException $e)
        {
            print("Error:".$e->getMessage()."<br/>");
            die();
        } 
    }
}

?>