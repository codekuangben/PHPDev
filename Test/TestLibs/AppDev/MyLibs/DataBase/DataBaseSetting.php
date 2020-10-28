<?php

namespace SDK\Lib;
/**
 * @brief 数据库基本设置
 */
class DataBaseSetting
{
    protected $mDataBaseType;
    
    protected $mHost;
    protected $mPort;
    protected $mDataBaseName;
    protected $mCharSet;
    
    protected $mUserName;
    protected $mPassWord;
    
    public function __construct()
    {
        $this->mHost = "127.0.0.1";
        $this->mPort = "3306";
        $this->mDataBaseName = "mytest";
        $this->mCharSet = "utf8";
        
        $this->mUserName = "aaaa";
        $this->mPassWord = "aaaaaa";
    }
    
    public function setDataBaseType($value)
    {
        $this->mDataBaseType = $value;
    }
    
    public function getUserName()
    {
        return $this->mUserName;
    }
    
    public function getPassWord()
    {
        return $this->mPassWord;
    }
    
    public function getDsn()
    {
        $dsn = "";
        
        if(DataBaseType::MYSQL == $this->mDataBaseType)
        {
            $dsn ="mysql:host=".$this->mHost.";port=".$this->mPort.";dbname=".$this->mDataBaseName.";charset=".$this->mCharSet; 
        }
        
        return $dsn;
    }
}

?>