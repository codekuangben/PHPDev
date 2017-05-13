<?php

namespace SDK\Lib;

/**
 * @brief 唯一字符串生成器
 */
class UniqueStrIdGen extends UniqueNumIdGen
{
	const PlayerPrefix = "PL";
	const PlayerChildPrefix = "PC";
	const AbandonPlanePrefix = "AP";
	const RobotPrefix = "RT";
	const SnowBlockPrefix = "SM";
	const PlayerTargetPrefix = "PT";

	protected $mPrefix;
	protected $mRetId;
	protected $mStringBuilder;

	public function __construct($prefix, $baseUniqueId)
	{
		parent::__construct($baseUniqueId);
		$this->mPrefix = prefix;
	}

	public function genNewStrId()
	{
		$this->mStringBuilder = new System.Text.StringBuilder();
		$this->mStringBuilder.Append($this->mPrefix);
		$this->mStringBuilder.Append('_');
		$this->mStringBuilder.Append($this->genNewId());
		$this->mRetId = $this->mStringBuilder.ToString();
		return $this->mRetId;
	}

	public function getCurStrId()
	{
		return $this->mRetId;
	}

	public function genStrIdById($id)
	{
		$this->mStringBuilder = new System.Text.StringBuilder();
		$this->mStringBuilder.Append($this->mPrefix);
		$this->mStringBuilder.Append('_');
		$this->mStringBuilder.Append(id);
		$this->mRetId = $this->mStringBuilder.ToString();
		return $this->mRetId;
	}
}

?>