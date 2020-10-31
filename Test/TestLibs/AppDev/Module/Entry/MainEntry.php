<?php

namespace Module\Entry;

require_once (dirname(__FILE__) . "/../Frame/AppFrame.php");

use Module\Frame\AppFrame;

/**
 * @brief 入口 
 */
$msAppFrame = new AppFrame();
$msAppFrame->init();
$msAppFrame->run();

?>