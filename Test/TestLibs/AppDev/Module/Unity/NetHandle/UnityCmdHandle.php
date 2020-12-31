<?php

namespace Module\Unity\NetHandle;

use MyLibs\Network\CmdDispatch\NetCmdDispatchHandle;
use MyLibs\Tools\UtilSysLibWrap;
use MyLibs\Tools\UtilPath;
use MyLibs\Tools\UtilTime;
use MyLibs\Tools\UtilStr;
//use MyLibs\FileVisitor\MFileStream;
//use MyLibs\Tools\UtilByte;
//use MyLibs\FileVisitor\FileMode;
use MyLibs\Common\SystemEnv;

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
        
        $this->_FileCompleteSuffix = "-Complete";
    }
    
    public function init()
    {
        parent::init();
        
        $this->addParamHandle(UnityCmdCv::eUnityShaderKeyParamId, $this, "HandleUnityShaderKeySave");
    }
    
    public function dispose()
    {
        $this->removeParamHandle(UnityCmdCv::eUnityShaderKeyParamId, $this, "HandleUnityShaderKeySave");
        
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
            //$destFileName = UtilPath::combine($this->_SaveRootPath, $newFileName);
            
            $destDirPath = SystemEnv::$MY_PHP_ROOT_PATH . "/SVCReport";
            
            if (!UtilPath::existDirectory($destDirPath))
            {
                UtilPath::createDirectory($destDirPath);
            }
            
            $destFileName = UtilPath::combine($destDirPath, $newFileName);
            UtilPath::copyFile($sourceFileName, $destFileName);
            $destFileName = $destFileName . $this->_FileCompleteSuffix;
            UtilPath::copyFile($sourceFileName, $destFileName);
        }
    }
}

?>