<?php

namespace SDK\Lib;

class ResizeMgr extends DelayPriorityHandleMgrBase implements ITickedObject, IDelayHandleItem, INoOrPriorityObject
{
	protected $mPreWidth;       // 之前宽度
	protected $mPreHeight;
	protected $mCurWidth;       // 现在宽度
	protected $mCurHeight;

	protected $mCurHalfWidth;       // 当前一半宽度
	protected $mCurHalfHeight;

	protected $mResizeList;

	public function __construct()
	{
		$this->mResizeList = new MList();
	}

	public function init()
	{

	}

	public function dispose()
	{
		$this->mResizeList->clear();
	}

	public function getWidth()
	{
		return $this->mCurWidth;
	}

	public function getHeight()
	{
		return $this->mCurHeight;
	}

	public function getHalfWidth()
	{
		return $this->mCurHalfWidth;
	}

	public function getHalfHeight()
	{
		return $this->mCurHalfHeight;
	}

	protected function addObject($delayObject, $priority = 0.0)
	{
		if($this->isInDepth())
		{
			parent::addObject($delayObject, $priority);
		}
		else
		{
			$this->addResizeObject($delayObject, $priority);
		}
	}

	protected function removeObject($delayObject)
	{
		if($this->isInDepth())
		{
			parent::removeObject(delayObject);
		}
		else
		{
			$this->removeResizeObject($delayObject);
		}
	}

	public function addResizeObject($obj, $priority = 0)
	{
		if (!$this->mResizeList->contains(obj))
		{
			$this->mResizeList->add(obj);
		}
	}

	public function removeResizeObject($obj)
	{
		if ($this->mResizeList->indexOf(obj) != -1)
		{
			$this->mResizeList->remove(obj);
		}
	}

	public function onTick($delta, $tickMode)
	{
		$this->mPreWidth = $this->mCurWidth;
		$this->mCurWidth = UtilSysLibWrap::getScreenWidth();
		$this->mCurHalfWidth = $this->mCurWidth / 2;

		$this->mPreHeight = $this->mCurHeight;
		$this->mCurHeight = UtilSysLibWrap::getScreenHeight();
		$this->mCurHalfHeight = $this->mCurHeight / 2;

		if ($this->mPreWidth != $this->mCurWidth || $this->mPreHeight != $this->mCurHeight)
		{
			$this->onResize($this->mCurWidth, $this->mCurHeight);
		}
	}

	public function onResize($viewWidth, $viewHeight)
	{
		$index = 0;
		$listLen = 0;
		
		$listLen = $this->mResizeList->count();
		$resizeObj = null; 
		
		while($indexd < $listLen)
		{
			$resizeObj->onResize($viewWidth, $viewHeight);
			
			$index += 1;
		}
	}

	public function setClientDispose($isDispose)
	{

	}

	public function isClientDispose()
	{
		return false;
	}
}

?>