<?php

namespace SDK\Lib;

/**
 * @brief 对系统 List 的封装
 */
class MList
{
	protected $mList;
	protected $mUniqueId;       // 唯一 Id ，调试使用
	protected $mEleTotal;       // 元素总数

	protected $mDic;    // 为了加快查找速度，当前 Element 到索引映射
	protected $mIsSpeedUpFind;  // 是否加快查询，这个只适用于元素在列表中是唯一的，例如引用之类的，如果有相同的，就会有问题，注意了
	protected $mIsOpKeepSort;           // 操作的时候是否保持排序

	public function __construct($capacity = 0)
	{
		$this->mList = array();
		$this->mEleTotal = $capacity;
		$this->mIsSpeedUpFind = false;
		$this->mIsOpKeepSort = false;
	}

	public function setIsSpeedUpFind($value)
	{
	    $this->mIsSpeedUpFind = $value;

		if($this->mIsSpeedUpFind)
		{
			$this->mDic = new MDictionary();
		}
	}

	public function setIsOpKeepSort($value)
	{
		$this->mIsOpKeepSort = $value;
	}

	public function list()
	{
		return $this->mList;
	}

	public function getUniqueId()
	{
		return $this->mUniqueId;
	}
		
	public function setUniqueId($value)
	{
		$this->mUniqueId = $value;
	}

	public function getSize()
	{
		// 频繁获取这个字段比较耗时
		//return count($this->mList);
		return $this->mEleTotal;
	}
	
	public function setCapacity($capacity)
	{
	    $index = 0;
	    $listLen = $capacity;
	    
	    while($index < $listLen)
	    {
	        $this->add(null);
	        $index += 1;
	    }
	}

	public function add($item)
	{
	    array_push($this->mList, $item);
		$this->mEleTotal += 1;

		if ($this->mIsSpeedUpFind)
		{
			$this->mDic[$item] = $this->count() - 1;
		}
	}

	public function push($item)
	{
		array_push($this->mList, $item);
		$this->mEleTotal += 1;

		if ($this->mIsSpeedUpFind)
		{
			$this->mDic[item] = $this->count() - 1;
		}
	}

	public function remove($item)
	{
		if ($this->mIsSpeedUpFind)
		{
			return $this->effectiveRemove($item);
		}
		else
		{
			$ret = false;
			
			$index = array_search($this->mList, $item);
			
			if($index)
			{
				$ret = true;
				array_splice($this->mList, $index, 1);
				$this->mEleTotal -= 1;
			}
			
			return $ret;
		}
	}

	public function get($index)
	{
		return $this->mList[$index];
	}

	public function set($index, $value)
	{
		$this->mList[$index] = $value;
	}

	public function clear()
	{
		// 真正的释放资源
		unset($this->mList);
		// 重新申请资源
		$this->mList = array();
		$this->mEleTotal = 0;

		if ($this->mIsSpeedUpFind)
		{
			$this->mDic->clear();
		}
	}

	public function count()
	{
		//return $this->mList.count;
		return $this->mEleTotal;
	}

	public function length()
	{
		//return $this->mList.count;
		return $this->mEleTotal;
	}

	public function setLength($value)
	{
		$this->mList->Capacity = $value;
	}

	public function removeAt($index)
	{
		if ($this->mIsSpeedUpFind)
		{
			$this->effectiveRemove($this->mList[$index]);
		}
		else
		{
			if ($index < $this->count())
			{
				// http://www.jb51.net/article/30689.htm
				// unset 这种方法的最大缺点是没有重建数组索引，就是说，数组的第三个元素没了。
				// array_splice 重建数组索引
				//unset($this->mList[$index]);
				array_splice($this->mList, $index, 1); 
				$this->mEleTotal -= 1;
			}
		}
	}

	public function indexOf($item)
	{
		if ($this->mIsSpeedUpFind)
		{
			if ($this->mDic->containsKey($item))
			{
				return $this->mDic[$item];
			}
			else
			{
				return -1;
			}
		}
		else
		{
			/**
			 * @ref PHP array_search() 函数 http://www.w3school.com.cn/php/func_array_search.asp 
			 */
			return array_search($this->mList, $item);
		}
	}

	public function insert($index, $item)
	{
		if (index <= $this->count())
		{
			$this->arrayPushBefore($this->mList, array($item), $index);
			$this->mEleTotal += 1;

			if ($this->mIsSpeedUpFind)
			{
				$this->mDic[$item] = $index;

				$this->updateIndex($index + 1);
			}
		}
		else
		{

		}
	}

	public function contains($item)
	{
		if ($this->mIsSpeedUpFind)
		{
			return $this->mDic->containsKey($item);
		}
		else
		{
			return in_array($this->mList, $item, true);
		}
	}

	public function Sort($comparer)
	{
		$this->mList->Sort($comparer);
	}

	public function merge($appendList)
	{
		if($appendList != null)
		{
		    $nativeList = $appendList->list();
		    
		    foreach ($nativeList as $item)
			{
				array_push($this->mList, $item);
				$this->mEleTotal += 1;

				if ($this->mIsSpeedUpFind)
				{
					$this->mDic[$item] = $this->count() - 1;
				}
			}
		}
	}

	// 快速移除元素
	protected function effectiveRemove($item)
	{
		$ret = false;

		if ($this->mDic->containsKey($item))
		{
			$ret = true;

			$idx = $this->mDic[$item];
			$this->mDic->remove($item);

			if (idx == $this->count() - 1)    // 如果是最后一个元素，直接移除
			{
				array_splice($this->mList, $idx, 1);
				$this->mEleTotal -= 1;
			}
			else
			{
				if (!$this->mIsOpKeepSort)
				{
					$this->mList[$idx] = $this->mList[$this->count() - 1];
					array_splice($this->mList, $this->count() - 1, 1);
					$this->mDic[$this->mList[$idx]] = $idx;
					$this->mEleTotal -= 1;
				}
				else
				{
					array_splice($this->mList, $idx, 1);
					$this->mEleTotal -= 1;
					$this->updateIndex($idx);
				}
			}
		}

		return ret;
	}

	protected function updateIndex($idx)
	{
		$len = $this->count();

		while($idx < $len)
		{
			$this->mDic[$this->mList[$idx]] = $idx;

			++$idx;
		}
	}
	
	/**
	 * @ref http://php.net/manual/en/function.array-push.php
	 * @return array
	 * @param array $src
	 * @param array $in
	 * @param int|string $pos
	 */
	public function arrayPushBefore($src, $in, $pos)
	{
		if(is_int($pos))
		{
			/**
			 * @ref 更新日志： 	自 PHP 5.0 起，该函数仅接受数组类型的参数。 http://www.w3school.com.cn/php/func_array_merge.asp 
			 */
			$R=array_merge(array_slice($src, 0 ,$pos), $in, array_slice($src, $pos));
		}
		else
		{
			foreach($src as $k=>$v)
			{
				if($k==$pos)
				{
					$R=array_merge($R,$in);
				}
				
				$R[$k]=$v;
			}
		}
		
		return $R;
	}
	
	/**
	 * @ref http://php.net/manual/en/function.array-push.php
	 * @return array
	 * @param array $src
	 * @param array $in
	 * @param int|string $pos
	 */
	public function arrayPushAfter($src, $in, $pos)
	{
		if(is_int($pos))
		{
			$R=array_merge(array_slice($src, 0, $pos + 1), $in, array_slice($src, $pos+1));
		}
		else
		{
			foreach($src as $k=>$v)
			{
				$R[$k]=$v;
				
				if($k==$pos)
				{
					$R=array_merge($R, $in);
				}
			}
		}
		
		return $R;
	}
	
	public function traverse()
	{
	    foreach($this->mList as $item)
	    {
	        echo $item."<br>";
	    }
	}
}

?>