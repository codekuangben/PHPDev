<?php

namespace MModule;

use MyLibs\NetCmdDispatchHandle;

class UnityCmdHandle extends NetCmdDispatchHandle
{
    public function __construct()
    {
        parent::__construct();
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
    
    public function handleMsg()
    {
        
    }
}

?>