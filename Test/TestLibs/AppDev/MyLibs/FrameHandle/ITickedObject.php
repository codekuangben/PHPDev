<?php

namespace SDK\Lib;

interface ITickedObject
{
	function onTick($delta, $tickMode);
}

?>