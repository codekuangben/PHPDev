<?php

namespace Module\Frame\EventNotify;

use MyLibs\Network\CmdDispatch\NetModuleDispatchHandle;
use Module\Frame\NetHandle\GameBaseCmdHandle;

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
        //$this->addCmdHandle(0, $this->mGameBaseCmdHandle, "call");
        $this->addCmdHandle(0, $this->mGameBaseCmdHandle, "handleMsg");
    }
    
    public function dispose()
    {
        if (null != $this->mNetCmdDispatchHandle)
        {
            //$this->removeCmdHandle(0, $this->mGameBaseCmdHandle, "call");
            $this->removeCmdHandle(0, $this->mGameBaseCmdHandle, "handleMsg");
            $this->mGameBaseCmdHandle->dispose();
            $this->mGameBaseCmdHandle = null;
        }
        
        parent::dispose();
    }
}

?>