<?php

namespace MyLibs;

class TextCompTimer extends DaoJiShiTimer
{
	protected $mText;

	protected function onPreCallBack()
	{
		parent::onPreCallBack();
		$this->mText->text = UtilLogic::formatTime((int)$this->mCurRunTime);
	}
}

?>