<?php

namespace SDK\Test;

require_once (dirname(__FILE__) . "/../../Libs/FrameWork/Ctx.php");

use SDK\Lib\Ctx;

Ctx::instance()->init();
Ctx::$mInstance->run();

?>