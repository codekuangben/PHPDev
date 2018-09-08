<?php

namespace SDK\Lib;

class UtilXml
{
	public const XML_OK = 0;
	public const XML_FAIL = 1;

	public static function getXmlAttrBool($xmlElement, $attrName, $ret)
	{
		if (null != $xmlElement && null != $xmlElement.Attributes && $xmlElement.Attributes.ContainsKey($attrName))
		{
			if (UtilSysLibWrap.TRUE == $xmlElement.Attribute($attrName))
			{
			    $ret = true;
			}
			else if (UtilSysLibWrap.FALSE == $xmlElement.Attribute($attrName))
			{
			    $ret = false;
			}
			else
			{
			    $ret = false;
			}

			return XML_OK;
		}

		$ret = false;
		return XML_FAIL;
	}

	public static function getXmlAttrStr($xmlElement, $attrName, $ret)
	{
		if (null != $xmlElement && null != $xmlElement.Attributes && $xmlElement.Attributes.ContainsKey($attrName))
		{
		    $ret =  $xmlElement.Attribute($attrName);
			return XML_OK;
		}

		$ret = "";
		return XML_FAIL;
	}

	public static function getXmlAttrUShort($xmlElement, $attrName, $ret)
	{
		if (null != $xmlElement && null != $xmlElement.Attributes && $xmlElement.Attributes.ContainsKey($attrName))
		{
		    ushort.TryParse($xmlElement.Attribute($attrName), $ret);
			return XML_OK;
		}

		$ret = 0;
		return XML_FAIL;
	}

	public static function getXmlAttrInt($xmlElement, $attrName, $ret)
	{
		if (null != $xmlElement && null != $xmlElement.Attributes && $xmlElement.Attributes.ContainsKey($attrName))
		{
		    short.TryParse($xmlElement.Attribute($attrName), $ret);
			return XML_OK;
		}

		$ret = 0;
		return XML_FAIL;
	}

	public static function getXmlAttrUInt($xmlElement, $attrName, $ret)
	{
		if (null != $xmlElement && null != $xmlElement.Attributes && $xmlElement.Attributes.ContainsKey($attrName))
		{
		    uint.TryParse($xmlElement.Attribute($attrName), $ret);
			return XML_OK;
		}

		$ret = 0;
		return XML_FAIL;
	}

	public static function getXmlAttrInt($xmlElement, $attrName, $ret)
	{
		if (null != $xmlElement && null != $xmlElement.Attributes && $xmlElement.Attributes.ContainsKey($attrName))
		{
		    int.TryParse($xmlElement.Attribute($attrName), $ret);
			return XML_OK;
		}

		$ret = 0;
		return XML_FAIL;
	}

	public static function getXmlAttrFloat($xmlElement, $attrName, $ret)
	{
		if (null != $xmlElement && null != $xmlElement.Attributes && $xmlElement.Attributes.ContainsKey($attrName))
		{
		    float.TryParse($xmlElement.Attribute($attrName), $ret);
			return XML_OK;
		}

		$ret = 0;
		return XML_FAIL;
	}

	// 获取一个 Element 中对应名字是 $attrName 的孩子节点列表
	public static function getXmlChildList($xmlElement, $attrName, $list)
	{
		if (null != $xmlElement)
		{
			$idx = 0;
			$len = $xmlElement.Children.Count;
			$child = null;

			//foreach (SecurityElement child in $xmlElement.Children)
			while($idx < $len)
			{
				$child = $xmlElement.Children[idx];

				//比对下是否使自己所需要得节点
				if (child.Tag == $attrName)
				{
					$list.Add(child);
				}

				++$idx;
			}
		}

		if ($list.Count > 0)
		{
			return XML_OK;
		}
		else
		{
			$list.Clear();
			return XML_FAIL;
		}
	}

	// 获取一个孩子节点
	public static function getXmlChild($xmlElement, $attrName, $childNode)
	{
		if (null != $xmlElement)
		{
			$idx = 0;
			$len = $xmlElement.Children.Count;
			$child = null;

			//foreach (SecurityElement child in $xmlElement.Children)
			while($idx < $len)
			{
				$child = $xmlElement.Children[idx];

				//比对下是否使自己所需要得节点
				if ($child.Tag == $attrName)
				{
					$childNode = $child;
					return XML_OK;
				}

				++$idx;
			}
		}

		$childNode = null;
		return XML_FAIL;
	}

	// 获取某一个元素的所有 Child 列表
	public static function getXmlElementAllChildList($xmlElement, $itemNode, $list)
	{
		$objElem = null;

		if (string.IsNullOrEmpty($itemNode))
		{
			$objElem = $xmlElement;
		}
		else
		{
			UtilXml::getXmlChild($xmlElement, $itemNode, $objElem);
		}

		$list = $objElem.Children;

		return XML_OK;
	}

	// 获取一个 Element 中对应目录是 pathListStr 的列表，目录个是为 "aaa.bbb.ccc"
	public static function getXmlChildListByPath($xmlElement, $pathListStr, $list)
	{
		$pathList = UtilStr.split($pathListStr, '.');
		$curName = "";
		$curElement = $xmlElement;  // 当前元素

		$elemIdx = 0;
		$elemLen = 0;

		$childIdx = 0;
		$childLen = 0;

		$child = null;
		$isLastOne = false;

		while ($elemIdx < $elemLen)
		{
			// 如果是最后一级
			if($elemIdx == $elemLen - 1)
			{
				$isLastOne = true;
			}

			$curName = $pathList[elemIdx];

			if (null != $curElement)
			{
				$childIdx = 0;
				$childLen = $curElement.Children.Count;

				while ($childIdx < $childLen)
				{
					$child = $curElement.Children[childIdx];

					//比对下是否使自己所需要得节点
					if ($child.Tag == $curName)
					{
						if (!$isLastOne)
						{
							$curElement = $child;
							break;
						}
						else
						{
							$list.Add($child);
						}
					}

					++$childIdx;
				}
			}

			++$elemIdx;
		}

		if ($list.Count > 0)
		{
			return XML_OK;
		}
		else
		{
			$list.Clear();
			return XML_FAIL;
		}
	}
}

?>