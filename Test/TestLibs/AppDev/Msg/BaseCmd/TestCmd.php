<?php

namespace Msg\BaseCmd;

use \MyLibs\Network\CmdDispatch\NullUserCmd;

class TestCmd extends NullUserCmd
{
    public function derializeGet()
    {
        parent::derializeGet();
    }
}

?>