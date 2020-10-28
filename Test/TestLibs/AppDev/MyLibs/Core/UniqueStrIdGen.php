<?php

namespace MyLibs;

/**
 * @brief 唯一字符串生成器
 */
class UniqueStrIdGen extends UniqueNumIdGen
{
	public const PlayerPrefix = "PL";
	public const PlayerChildPrefix = "PC";
	public const AbandonPlanePrefix = "AP";
	public const RobotPrefix = "RT";
	public const SnowBlockPrefix = "SM";
	public const PlayerTargetPrefix = "PT";

	protected $mPrefix;
	protected $mRetId;
	protected $mStringBuilder;

	public function __construct($prefix, $baseUniqueId)
	{
		parent::__construct($baseUniqueId);
		$this->mPrefix = $prefix;
	}

	public function genNewStrId()
	{
	    $this->mRetId = $this->mPrefix . '_' . $this->genNewId();
		return $this->mRetId;
	}

	public function getCurStrId()
	{
		return $this->mRetId;
	}

	public function genStrIdById($id)
	{
	    $this->mRetId = $this->mPrefix . '_' . id;
		return $this->mRetId;
	}
}

?>