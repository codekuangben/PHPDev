<?php

namespace SDK\Lib;

class MDictionary
{
	protected $mData;

	public function __construct()
	{
		$this->mData = array();
	}

	public function getData()
	{
		return $this->mData;
	}

	public function getCount()
	{
		return count($this->mData);
	}

	public function value($key)
	{
		if(array_key_exists($key, $this->mData))
		{
			return $this->mData[$key];
		}

		return null;
	}

	public function key($value)
	{
		while(list($key, $val)= each($this->mData))
		{
			if (UtilApi::isObjectEqual($val, $value))
			{
				return $key;
			}
		}
		
		return null;
	}

	public function getKeys()
	{
		return array_keys($this->mData);
	}

	public function getValues()
	{
		return array_values($this->mData);
	}

	public function Count()
	{
		return count($this->mData);
	}

	public function Add($key, $value)
	{
		$this->mData[$key] = $value;
	}

	public function Remove($key)
	{
		if(array_key_exists($key, $this->mData))
		{
			$index = array_search($key, $this->mData);
			array_splice($this->mData, $index, 1);
		}
	}

	public function Clear()
	{
		unset($this->mData);
		$this->mData = array();
	}

	public function TryGetValue($key, $value)
	{
		$ret = false;
		
		if(array_key_exists($key, $this->mData))
		{
			$value = $this->mData[$key];
			$ret = true;
		}
		
		return $ret;
	}

	public function ContainsKey($key)
	{
		return array_key_exists($key, $this->mData);
	}

	public function ContainsValue($value)
	{
		while(list($key, $val)= each($this->mData))
		{
			if(UtilApi::isObjectEqual($val, $value))
			{
				return true;
			}
		}
	
		return false;
	}

	public function at($idx)
	{
		$curidx = 0;
		$ret = null;

		while(list($key, $val)= each($this->mData))
		{
			if($curidx == $idx)
			{
				$ret = $val;
				break;
			}
		}

		return $ret;
	}
}

?>