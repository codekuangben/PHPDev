<?php

namespace MModule;

use MyLibs\NetCmdDispatchHandle;
use MyLibs\UtilSysLibWrap;
use MyLibs\UtilPath;
use MyLibs\UtilTime;
use MyLibs\UtilStr;
use MyLibs\MFileStream;
use MyLibs\UtilByte;

class UnityCmdHandle extends NetCmdDispatchHandle
{
    protected $_SaveRootPath;
    
    public function __construct()
    {
        parent::__construct();
        
        if (UtilSysLibWrap::isWin())
        {
            $this->_SaveRootPath = "Y:/ShaderVariantInfo";
        }
    }
    
    public function init()
    {
        parent::init();
        
        $this->addParamHandle(UnityCmdCv::eUnityShaderKeyParamId, $this, "HandleUnityShaderKeySave");
    }
    
    public function dispose()
    {
        this.removeParamHandle(UnityCmdCv::eUnityShaderKeyParamId, $this, "HandleUnityShaderKeySave");
        
        parent::dispose();
    }
    
    public function handleMsg($cmdDispatchInfo)
    {
        parent::handleMsg($cmdDispatchInfo);
    }
    
    public function HandleUnityShaderKeySave($cmdDispatchInfo)
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
            $fileStream = new MFileStream($sourceFileName);
            $fileStream->init();
            $fileStream->open();
            
            if ($fileStream->isValid())
            {
                $byteContent = $fileStream->readByte();
                $fileStream->close();
                $fileStream = NULL;
                $strContent = UtilByte::decodeUtf8($byteContent);
            }
            
            $fileStream = new MFileStream($destFileName);
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