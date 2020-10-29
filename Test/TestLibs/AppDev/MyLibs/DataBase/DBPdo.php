<?php

namespace MyLibs;

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
    protected $mIsPersistentConnect;        // 保持长连接
    protected $mPdoErrorMode;               // 错误处理方式
    protected $mNeedTransaction;            // 是否需要事务执行 beginTransaction  commit
    protected $mIsDirectExec;               // 是否直接执行 PDO::exec() ，还是 PDOStatement::execute()
    protected $mRowCount;                   // PDO::exec() 影响的行数
    protected $mDataBaseOpMode;             // 增加 删除 修改 查找
    
    protected $mNativePdo;
    protected $mNativePDOStatement;
    
    public function __construct()
    {
        $this->mDataBaseSetting = new DataBaseSetting();
        $this->mIsPersistent = true;
        $this->mPdoErrorMode = PdoErrorMode::ERRMODE_SILENT;
        $this->mNeedTransaction = true;
        $this->mIsDirectExec = false;
        $this->mNativePdo = null;
        $this->mNativePDOStatement = null;
        $this->mPdoQueryList = null;
        $this->mRowCount = 0;
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
    
    public function clear()
    {
        $this->mNativePDOStatement->closeCursor();
    }
    
    public function dispose()
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
    public function execCommand(string $sql, int $opMode)
    {
        if($this->mNeedTransaction)
        {
            $this->_execWithTransaction($sql, $opMode);
        }
        else
        {
            $this->_execWithOutTransaction($sql, $opMode);
        }
    }
    
    public function _execWithOutTransaction(string $sql, int $opMode, bool $isHandleException = true)
    {
        if($isHandleException)
        {
            try
            {
                $this->_execWithOutTransactionNoException($sql, $opMode);
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
            $this->_execWithOutTransactionNoException($sql, $opMode);
        }
    }
    
    public function _execWithOutTransactionNoException(string $sql, int $opMode)
    {
        if($this->mIsDirectExec)
        {
            if(DataBaseOpMode::FIND == $opMode)
            {
                $this->mNativePDOStatement = $this->mNativePdo->query($sql);
            }
            else
            {
                $this->mRowCount = $this->mNativePdo->exec($sql);
            }
        }
        else
        {
            $this->mNativePDOStatement = $this->mNativePdo->prepare($sql);
            //$this->mNativePDOStatement->bindParam();
            $this->mNativePDOStatement->execute();
            //echo($this->mNativePDOStatement->rowCount());
        }
    }
    
    public function _execWithTransaction(string $sql, int $opMode)
    {
        try
        {
            $this->mNativePdo->beginTransaction();//开启事务
            
            $this->_execWithOutTransaction($sql, $opMode, false);
            
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
        $this->mDataBaseOpMode = DataBaseOpMode::ADD;
        $this->execCommand($sql, DataBaseOpMode::ADD);
    }
    
    // 删除
    public function delete(string $sql)
    {
        $this->mDataBaseOpMode = DataBaseOpMode::DELETE;
        $this->execCommand($sql, DataBaseOpMode::DELETE);
    }
    
    // 修改
    public function set(string $sql)
    {
        $this->mDataBaseOpMode = DataBaseOpMode::SET;
        $this->execCommand($sql, DataBaseOpMode::SET);
    }
    
    // 查询
    public function find(string $sql)
    {
        $this->mDataBaseOpMode = DataBaseOpMode::FIND;
        $this->execCommand($sql, DataBaseOpMode::FIND);
    }
    
    public function getRowCount()
    {
        $ret = 0;
        
        if($this->mIsDirectExec)
        {
            if(DataBaseOpMode::FIND == $this->mDataBaseOpMode)
            {
                $ret = $this->mNativePDOStatement->rowcount();
            }
            else
            {
                $ret = $this->mRowCount;
            }
        }
        else
        {
            $ret = $this->mNativePDOStatement->rowcount();
        }
        
        return $ret;
    }
    
    public function getColumnCount()
    {
        $ret = 0;
        
        if($this->mIsDirectExec)
        {
            
        }
        else
        {
            $ret = $this->mNativePDOStatement->columncount();
        }
        
        return $ret;
    }
    
    public function getNextRow()
    {
        $row = null;
        
        // 如果没有返回 false
        $row = $this->mNativePDOStatement->fetch(PDO::FETCH_ASSOC);
        $this->mNativePDOStatement->nextRowset();
        
        return $row;
    }
    
    public function getNextRowOneByOne($eventListener, $eventHandle)
    {
        $functor = new EventDispatchFunctionObject();
        $functor->setFuncObject($eventListener, $eventHandle);
        
        while($row = $this->mNativePDOStatement->fetch(PDO::FETCH_ASSOC))
        {
            $functor->call($row);
        }
    }
    
    public function getAllRow()
    {
        $ret = $this->mNativePDOStatement->fetchAll();
        return $ret;
    }
}

?>