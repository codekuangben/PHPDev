<?php

namespace SDK\Module;

use SDK\Lib\NetModuleDispatchHandle;

class NetNotify extends NetModuleDispatchHandle
{
    protected $mGameBaseCmdHandle;
    
    public function __construct()
    {
        parent::__construct();
    }
    
    public function init()
    {
        parent::init();
        
        $this->mGameBaseCmdHandle = new GameBaseCmdHandle();
        $this->mGameBaseCmdHandle->init();
        $this->addCmdHandle(0, $this->mGameBaseCmdHandle, "call");
    }
    
    public function dispose()
    {
        if (null != this.mNetCmdDispatchHandle)
        {
            $this->removeCmdHandle(0, $this->mGameBaseCmdHandle, "call");
            $this->mGameBaseCmdHandle.dispose();
            $this->mGameBaseCmdHandle = null;
        }
        
        parent::dispose();
    }
}

?>