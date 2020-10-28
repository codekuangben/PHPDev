<?php

namespace MyLibs;

interface ITickedObject
{
	function onTick($delta, $tickMode);
}

?>