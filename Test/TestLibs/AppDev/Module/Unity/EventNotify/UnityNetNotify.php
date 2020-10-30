<?php

namespace Module\Unity\EventNotify;

use Module\Unity\NetHandle\UnityCmdCv;
use MyLibs\Network\CmdDispatch\NetModuleDispatchHandle;
use Module\Unity\NetHandle\UnityCmdHandle;

class UnityNetNotify extends NetModuleDispatchHandle
{
    protected $_UnityCmdHandle;
    
    public function __construct()
    {
        parent::__construct();
    }
    
    public function init()
    {
        parent::init();
        
        $this->_UnityCmdHandle = new UnityCmdHandle();
        $this->_UnityCmdHandle->init();
        $this->addCmdHandle(UnityCmdCv::eUnityCmdId, $this->_UnityCmdHandle, "handleMsg");
    }
    
    public function dispose()
    {
        if (null != $this->_UnityCmdHandle)
        {
            $this->removeCmdHandle(UnityCmdCv::eUnityCmdId, $this->_UnityCmdHandle, "handleMsg");
            $this->_UnityCmdHandle->dispose();
            $this->_UnityCmdHandle = null;
        }
        
        parent::dispose();
    }
}

?>