<?php

namespace MyLibs\Task;

interface ITask
{
	public function runTask();             // 执行任务
	public function handleResult();        // 处理结果
}

?>