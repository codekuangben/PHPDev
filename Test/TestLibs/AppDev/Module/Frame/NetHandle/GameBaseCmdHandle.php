<?php

namespace Module\Frame\NetHandle;

use MyLibs\Network\CmdDispatch\NetCmdDispatchHandle;

class GameBaseCmdHandle extends NetCmdDispatchHandle
{
    public function __construct()
    {
        parent::__construct();
    }
    
    public function init()
    {
        parent::init();
        
        $this->addParamHandle(0, $this, "handleA");
    }
    
    public function dispose()
    {
        $this->removeParamHandle(0, $this, "handleA");
        
        parent::dispose();
    }
    
    public function handleA()
    {
        
    }
}

?>