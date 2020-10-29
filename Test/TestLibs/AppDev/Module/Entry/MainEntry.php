<?php

namespace MModule;

require_once (dirname(__FILE__) . "/../App/AppFrame.php");

/**
 * @brief 入口 
 */
$msAppFrame = new AppFrame();
$msAppFrame->init();
$msAppFrame->run();

?>