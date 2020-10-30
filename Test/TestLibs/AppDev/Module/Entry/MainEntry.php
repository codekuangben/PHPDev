<?php

namespace Module\Entry;

require_once (dirname(__FILE__) . "/../App/AppFrame.php");

use Module\App\AppFrame;

/**
 * @brief 入口 
 */
$msAppFrame = new AppFrame();
$msAppFrame->init();
$msAppFrame->run();

?>