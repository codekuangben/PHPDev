<?php

namespace SDK\Lib;

use PDO;
use Exception;
use PDOException;

/**
 * @url https://www.cnblogs.com/hellohell/p/5718205.html
 * @url https://www.jb51.net/article/124388.htm
 */
class DBPdo
{
    protected $mDataBaseType;
    protected $mDataBaseSetting;
    protected $mIsPersistentConnect;       // 保持长连接
    protected $mPdoErrorMode;
    protected $mNeedTransaction;
    protected $mIsDirectExec;           // 是否直接执行
    
    protected $mNativePdo;
    protected $mNativePDOStatement;
    
    public function __construct()
    {
        $this->mDataBaseSetting = new DataBaseSetting();
        $this->mIsPersistent = true;
        $this->mPdoErrorMode = PdoErrorMode::ERRMODE_SILENT;
        $this->mNeedTransaction = false;
        $this->mIsDirectExec = true;
        $this->mNativePdo = null;
        $this->mNativePDOStatement = null;
    }
    
    public function __destruct()
    {
        
    }
    
    public function __clone()
    {
        
    }
    
    public function setDataBaseType(int $value)
    {
        $this->mDataBaseType = $value;
        $this->mDataBaseSetting->setDataBaseType($this->mDataBaseType);
    }
    
    public function init()
    {
        
    }
    
    public function connect()
    {
        if(DataBaseType::MYSQL == $this->mDataBaseType)
        {
            $this->_connectMySql();
        }
    }
    
    public function _connectMySql()
    {
        try
        {
            $dsn = $this->mDataBaseSetting->getDsn();
            $userName = $this->mDataBaseSetting->getUserName();
            $passWord = $this->mDataBaseSetting->getPassWord();
            
            //$this->mNativePdo = new PDO(
            //    $dsn,
            //    $userName,
            //    $passWord,
            //    array(PDO::ATTR_PERSISTENT => true, PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")
            //);
            
            if($this->mIsPersistentConnect)
            {
                $this->mNativePdo = new PDO(
                    $dsn,
                    $userName,
                    $passWord,
                    array(PDO::ATTR_PERSISTENT => true)
                );
            }
            else
            {
                $this->mNativePdo = new PDO(
                    $dsn,
                    $userName,
                    $passWord
                );
            }
            
            // 错误处理方式
            if(PdoErrorMode::ERRMODE_SILENT == $this->mPdoErrorMode)
            {
                $this->mNativePdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_SILENT);
            }
            if(PdoErrorMode::ERRMODE_WARNING == $this->mPdoErrorMode)
            {
                $this->mNativePdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
            }
            if(PdoErrorMode::ERRMODE_EXCEPTION == $this->mPdoErrorMode)
            {
                $this->mNativePdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            }
            
            $this->mNativePdo->exec('set names utf8');
        }
        catch (PDOException $e)
        {
            print("Error:".$e->getMessage()."<br/>");
            die();
        }
    }
    
    // 执行
    public function execCommand(string $sql)
    {
        if($this->mNeedTransaction)
        {
            $this->_execWithTransaction($sql);
        }
        else
        {
            $this->_execWithOutTransaction($sql);
        }
    }
    
    public function _execWithOutTransaction(string $sql, bool $isHandleException = true)
    {
        if($isHandleException)
        {
            try
            {
                $this->_execWithOutTransactionNoException($sql);
            }
            catch (PDOException $e)
            {
                echo('PDO Exception Caught. ');
                echo('Error with the database: <br />');
                echo('SQL Query: '. $sql);
                echo('Error: ' . $e->getMessage());
            }
        }
        else
        {
            $this->_execWithOutTransactionNoException($sql);
        }
    }
    
    public function _execWithOutTransactionNoException(string $sql)
    {
        if($this->mIsDirectExec)
        {
            $this->mNativePdo->exec($sql);
        }
        else
        {
            $this->mNativePDOStatement = $this->mNativePdo->prepare($sql);
            //$this->mNativePDOStatement->bindParam();
            $this->mNativePDOStatement->execute();
            //echo($this->mNativePDOStatement->rowCount());
        }
    }
    
    public function _execWithTransaction(string $sql)
    {
        try
        {
            $this->mNativePdo->beginTransaction();//开启事务
            
            $this->_addWithOutTransaction($sql, false);
            
            $this->mNativePdo->commit();//提交事务
        }
        catch(Exception $e)
        {
            $this->mNativePdo->rollBack();//错误回滚
            echo("Failed:".$e->getMessage());
        }
    }
    
    // 增加
    public function add(string $sql)
    {
        if($this->mNeedTransaction)
        {
            $this->_addWithTransaction($sql);
        }
        else
        {
            $this->_addWithOutTransaction($sql);
        }
    }
    
    public function _addWithOutTransaction(string $sql, bool $isHandleException = true)
    {
        if($isHandleException)
        {
            try
            {
                $this->_addWithOutTransactionNoException($sql);
            }
            catch (PDOException $e)
            {
                echo('PDO Exception Caught. ');
                echo('Error with the database: <br />');
                echo('SQL Query: '. $sql);
                echo('Error: ' . $e->getMessage());
            }
        }
        else
        {
            $this->_addWithOutTransactionNoException($sql);
        }
    }
    
    public function _addWithOutTransactionNoException(string $sql)
    {
        if($this->mIsDirectExec)
        {
            $this->mNativePdo->exec($sql);
        }
        else
        {
            $this->mNativePDOStatement = $this->mNativePdo->prepare($sql);
            //$this->mNativePDOStatement->bindParam();
            $this->mNativePDOStatement->execute();
            //echo($this->mNativePDOStatement->rowCount());
        }
    }
    
    public function _addWithTransaction(string $sql)
    {
        try
        {
            $this->mNativePdo->beginTransaction();//开启事务
            
            $this->_addWithOutTransaction($sql, false);
            
            $this->mNativePdo->commit();//提交事务
        }
        catch(Exception $e)
        {
            $this->mNativePdo->rollBack();//错误回滚
            echo("Failed:".$e->getMessage());
        } 
    }
    
    // 删除
    public function delete(string $sql)
    {
        
    }
    
    public function _deleteWithOutTransaction(string $sql)
    {
        
    }
    
    public function _deleteWithTransaction(string $sql)
    {
        
    }
    
    // 修改
    public function set(string $sql)
    {
        
    }
    
    public function _setWithOutTransaction(string $sql)
    {
        
    }
    
    public function _setWithTransaction(string $sql)
    {
        
    }
    
    // 查询
    public function find(string $sql)
    {
        
    }
    
    public function _findWithOutTransaction(string $sql)
    {
        
    }
    
    public function _findWithTransaction(string $sql)
    {
        
    }
}

?>