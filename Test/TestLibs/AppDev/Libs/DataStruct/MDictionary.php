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
		return $this->mData.Count;
	}

	public function value($key)
	{
		if ($this->mData.ContainsKey($key))
		{
			return $this->mData[$key];
		}

		return null;
	}

	public function key($value)
	{
		while(list($key, $val)= each($this->mData))
		{
			if ($val.Equals($value))
				//if ($val== == $value)
			{
				return $key;
			}
		}
		return null;
	}

	public function getKeys()
	{
		return $this->mData.Keys;
	}

	public function getValues()
	{
		return $this->mData.Values;
	}

	public function Count()
	{
		return $this->mData.Keys.Count;
	}

	public function GetEnumerator()
	{
		return $this->mData.GetEnumerator();
	}

	public function Add($key, $value)
	{
		$this->mData[$key] = $value;
	}

	public function Remove($key)
	{
		$this->mData.Remove($key);
	}

	public function Clear()
	{
		$this->mData.Clear();
	}

	public function TryGetValue($key, $value)
	{
		return $this->mData.TryGetValue($key, $value);
	}

	public function ContainsKey($key)
	{
		return $this->mData.ContainsKey($key);
	}

	public function ContainsValue($value)
	{
		while(list($key, $val)= each($this->mData))
		{
			if($val.Equals($value))
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