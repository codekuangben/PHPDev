<?php

namespace MModule;

use MyLibs\NetCmdDispatchHandle;
use MyLibs\UtilSysLibWrap;
use MyLibs\UtilPath;
use MyLibs\UtilTime;
use MyLibs\UtilStr;

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
        
        $this->addParamHandle(UnityCmdCv::eUnityShaderKeyParamId, $this, "handleMsg");
    }
    
    public function dispose()
    {
        this.removeParamHandle(UnityCmdCv::eUnityShaderKeyParamId, $this, "handleMsg");
        
        parent::dispose();
    }
    
    public function handleMsg($cmdDispatchInfo)
    {
        parent::handleMsg($cmdDispatchInfo);
    }
    
    protected function _HandleUnityShaderKeySave($cmdDispatchInfo)
    {
        $sourceFileName = UtilSysLibWrap::fileTmpName();
        $fileName = UtilSysLibWrap::fileName();
        $fileNameNoExtName = UtilPath::getFileNameNoExt($fileName);
        $newFileName = UtilStr::concat($fileName, "-", UtilTime::getTimeStr(), ".", $fileNameNoExtName);
        $destFileName = UtilPath::combine($this->_SaveRootPath, $newFileName);
        UtilPath::copyFile($sourceFileName, $destFileName);
    }
}

?>