<?php

namespace SDK\Lib;

interface ITask
{
	public function runTask();             // 执行任务
	public function handleResult();        // 处理结果
}

?>