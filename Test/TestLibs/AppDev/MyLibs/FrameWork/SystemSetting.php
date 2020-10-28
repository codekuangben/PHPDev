<?php

namespace SDK\Lib;

/**
 * @brief 系统设置
 */
class SystemSetting
{
	public const USERNAME = "username";
	public const PASSWORD = "password";
	public const NICKNAME = "nickname";

	public function setString($key, $value)
	{
		//PlayerPrefs::SetString($key, $value);
	}

	public function getString($key)
	{
		if (hasKey(key))
		{
			//return PlayerPrefs::GetString(key);
		}

		return "";
	}

	public function setInt($key, $value)
	{
		//PlayerPrefs::SetInt($key, $value);
	}

	public function getInt($key)
	{
		if(hasKey(key))
		{
		    //return PlayerPrefs::GetInt($key);
		}
		return 0;
	}

	public function setFloat($key, $value)
	{
		//PlayerPrefs::SetFloat($key, $value);
	}

	public function getFloat($key)
	{
		if (hasKey($key))
		{
			//return PlayerPrefs::GetFloat($key);
		}
		return 0;
	}

	public function hasKey($key)
	{
		//return PlayerPrefs->HasKey($key);
	}
}

?>