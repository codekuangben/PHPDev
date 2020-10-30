<?php

namespace Test\TestFileVisitor;

//use MyLibs\Network\CmdDispatch\NetCmdDispatchHandle;
use MyLibs\Tools\UtilSysLibWrap;
use MyLibs\Tools\UtilPath;
use MyLibs\Tools\UtilTime;
use MyLibs\Tools\UtilStr;
use MyLibs\FileVisitor\MFileStream;
use MyLibs\Tools\UtilByte;
use MyLibs\FileVisitor\FileMode;
use Test\Base\TestBase;

class TestFileVisitor extends TestBase
{
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
        $this->_TestA();
    }
    
    protected function _TestA()
    {
        if (UtilSysLibWrap::issetInFiles("file"))
        {
            $sourceFileName = UtilSysLibWrap::fileTmpName("file");
            $fileName = UtilSysLibWrap::fileName("file");
            $fileNameNoExtName = UtilPath::getFileNameNoExt($fileName);
            $fileExtName = UtilPath::getFileExt($fileName);
            $newFileName = UtilStr::concat($fileNameNoExtName, "-", UtilTime::getTimeStr(), ".", $fileExtName);
            $destFileName = UtilPath::combine($this->_SaveRootPath, $newFileName);
            //UtilPath::copyFile($sourceFileName, $destFileName);
            $strContent = "";
            $fileStream = new MFileStream($sourceFileName, FileMode::Read);
            $fileStream->init();
            $fileStream->open();
            
            if ($fileStream->isValid())
            {
                $byteContent = $fileStream->readByte();
                $fileStream->close();
                $fileStream = NULL;
                $strContent = UtilByte::decodeUtf8($byteContent);
            }
            
            $fileStream = new MFileStream($destFileName, FileMode::Write);
            $fileStream->init();
            $fileStream->open();
            
            if ($fileStream->isValid())
            {
                $byteContent = $fileStream->writeText($strContent);
                $fileStream->close();
                $fileStream = NULL;
            }
        }
    }
}

?>