<?php

namespace MyLibs\FrameHandle;

interface ITickedObject
{
	function onTick($delta, $tickMode);
}

?>