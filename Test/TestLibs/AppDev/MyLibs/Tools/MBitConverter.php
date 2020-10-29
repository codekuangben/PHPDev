<?php

namespace MyLibs;

class MBitConverter
{
	public static function ToBoolean(
		$bytes, 
		$startIndex, 
		$endian = MEndian::eLITTLE_ENDIAN
		)
	{
		return $bytes[$startIndex] != 0;
	}

	public static function ToChar(
		$bytes, 
		$startIndex, 
		$endian = MEndian::eLITTLE_ENDIAN
		)
	{
		return $bytes[$startIndex];
	}

	public static function ToInt16(
		$bytes, 
		$startIndex, 
		$endian = MEndian::eLITTLE_ENDIAN
		)
	{
		$retValue = 0;
		if ($endian == MEndian::eLITTLE_ENDIAN)
		{
			$retValue = (short)(
				($bytes[$startIndex + 1] << 8) + 
				 $bytes[$startIndex]
				);
		}
		else
		{
			$retValue = (short)(
				($bytes[$startIndex] << 8) + 
				 $bytes[$startIndex + 1]
				);
		}
		return $retValue;
	}

	public static function ToUInt16(
		$bytes, 
		$startIndex, 
		$endian = MEndian::eLITTLE_ENDIAN
		)
	{
		$retValue = 0;
		if ($endian == MEndian::eLITTLE_ENDIAN)
		{
			$retValue = (ushort)(
				($bytes[$startIndex + 1] << 8) + 
				 $bytes[$startIndex]
				);
		}
		else
		{
			$retValue = (ushort)(
				($bytes[$startIndex] << 8) + 
				$bytes[$startIndex + 1]
				);
		}
		return $retValue;
	}

	public static function ToInt32(
		$bytes, 
		$startIndex, MEndian 
		$endian = MEndian::eLITTLE_ENDIAN
		)
	{
		$retValue = 0;
		if ($endian == MEndian::eLITTLE_ENDIAN)
		{
			$retValue = (int)(
				($bytes[$startIndex + 3] << 24) +
				($bytes[$startIndex + 2] << 16) +
				($bytes[$startIndex + 1] << 8) + 
				 $bytes[$startIndex]
				);
		}
		else
		{
			$retValue = (int)(
				($bytes[$startIndex] << 24) +
				($bytes[$startIndex + 1] << 16) +
				($bytes[$startIndex + 2] << 8) + 
				 $bytes[$startIndex + 3]
				);
		}
		return $retValue;
	}

	public static function ToUInt32(
		$bytes, 
		$startIndex, 
		$endian = MEndian::eLITTLE_ENDIAN
		)
	{
		$retValue = 0;
		if ($endian == MEndian::eLITTLE_ENDIAN)
		{
			$retValue = (uint)(
				($bytes[$startIndex + 3] << 24) +
				($bytes[$startIndex + 2] << 16) +
				($bytes[$startIndex + 1] << 8) + 
				 $bytes[$startIndex]
				);
		}
		else
		{
			$retValue = (uint)(
				($bytes[$startIndex] << 24) +
				($bytes[$startIndex + 1] << 16) +
				($bytes[$startIndex + 2] << 8) + 
				 $bytes[$startIndex + 3]
				);
		}
		return $retValue;
	}

	public static function ToInt64(
		$bytes, 
		$startIndex, 
		$endian = MEndian::eLITTLE_ENDIAN
		)
	{
		$retValue = 0;
		if ($endian == MEndian::eLITTLE_ENDIAN)
		{
			$retValue = (long)(
				($bytes[$startIndex + 7] << 56) + 
				($bytes[$startIndex + 6] << 48) +
				($bytes[$startIndex + 5] << 40) +
				($bytes[$startIndex + 4] << 32) +
				($bytes[$startIndex + 3] << 24) +
				($bytes[$startIndex + 2] << 16) +
				($bytes[$startIndex + 1] << 8) + 
				 $bytes[$startIndex]
				);
		}
		else
		{
			$retValue = (long)(
				($bytes[$startIndex] << 56) +
				($bytes[$startIndex + 1] << 48) +
				($bytes[$startIndex + 2] << 40) +
				($bytes[$startIndex + 3] << 32) +
				($bytes[$startIndex + 4] << 24) +
				($bytes[$startIndex + 5] << 16) +
				($bytes[$startIndex + 6] << 8) + 
				 $bytes[$startIndex + 7]
				);
		}
		return $retValue;
	}

	public static function ToUInt64(
		$bytes, 
		$startIndex, 
		$endian = MEndian::eLITTLE_ENDIAN
		)
	{
		$retValue = 0;
		if ($endian == MEndian::eLITTLE_ENDIAN)
		{
			$retValue = (ulong)(
				($bytes[$startIndex + 7] << 56) +
				($bytes[$startIndex + 6] << 48) +
				($bytes[$startIndex + 5] << 40) +
				($bytes[$startIndex + 4] << 32) +
				($bytes[$startIndex + 3] << 24) +
				($bytes[$startIndex + 2] << 16) +
				($bytes[$startIndex + 1] << 8) + 
				 $bytes[$startIndex]
				);
		}
		else
		{
			$retValue = (ulong)(
				($bytes[$startIndex] << 56) +
				($bytes[$startIndex + 1] << 48) +
				($bytes[$startIndex + 2] << 40) +
				($bytes[$startIndex + 3] << 32) +
				($bytes[$startIndex + 4] << 24) +
				($bytes[$startIndex + 5] << 16) +
				($bytes[$startIndex + 6] << 8) + 
				 $bytes[$startIndex + 7]
				);
		}
		return $retValue;
	}
	
	public static function GetBytes(
		$data, 
		$bytes, 
		$startIndex, 
		$endian = MEndian::eLITTLE_ENDIAN
		)
	{
		$bytes[$startIndex] = (byte)($data ? 1 : 0);
	}

	public static function GetBytesA(
		$data, 
		$bytes, 
		$startIndex, 
		$endian = MEndian::eLITTLE_ENDIAN
		)
	{
		$bytes[$startIndex] = /*(byte)*/$data;
	}

	public static function GetBytesB(
		$data, 
		$bytes, 
		$startIndex, 
		$endian = MEndian::eLITTLE_ENDIAN
		)
	{
		if ($endian == MEndian::eLITTLE_ENDIAN)
		{
			//bytes[index] = (byte)(data & 0x00FF);
			//bytes[index + 1] = (byte)((data & 0xFF00) >> 8);
			$bytes[$startIndex] = (byte)($data << 8 >> 8);
			$bytes[$startIndex + 1] = (byte)($data >> 8);
		}
		else
		{
			//bytes[index + 1] = (byte)((data & 0xFF00) >> 8);
			//bytes[index] = (byte)(data & 0x00FF);
			$bytes[$startIndex] = (byte)($data >> 8);
			$bytes[$startIndex + 1] = (byte)($data << 8 >> 8);
		}
	}

	public static function GetBytesC(
		$data, 
		$bytes, 
		$startIndex, 
		$endian = MEndian::eLITTLE_ENDIAN
		)
	{
		if ($endian == MEndian::eLITTLE_ENDIAN)
		{
			$bytes[$startIndex] = (byte)($data << 8 >> 8);
			$bytes[$startIndex + 1] = (byte)($data >> 8);
		}
		else
		{
			$bytes[$startIndex] = (byte)($data >> 8);
			$bytes[$startIndex + 1] = (byte)($data << 8 >> 8);
		}
	}

	public static function GetBytesD(
		$data, 
		$bytes, 
		$startIndex, 
		$endian = MEndian::eLITTLE_ENDIAN
		)
	{
		if ($endian == MEndian::eLITTLE_ENDIAN)
		{
			$bytes[$startIndex] = (byte)($data << 24 >> 24);
			$bytes[$startIndex + 1] = (byte)($data << 16 >> 24);
			$bytes[$startIndex + 2] = (byte)($data << 8 >> 24);
			$bytes[$startIndex + 3] = (byte)($data >> 24);
		}
		else
		{
			$bytes[$startIndex] = (byte)($data >> 24);
			$bytes[$startIndex + 1] = (byte)($data << 8 >> 24);
			$bytes[$startIndex + 2] = (byte)($data << 16 >> 24);
			$bytes[$startIndex + 3] = (byte)($data << 24 >> 24);
		}
	}

	public static function GetBytesE(
		$data, 
		$bytes, 
		$startIndex, 
		$endian = MEndian::eLITTLE_ENDIAN
		)
	{
		if ($endian == MEndian::eLITTLE_ENDIAN)
		{
			$bytes[$startIndex] = (byte)($data << 24 >> 24);
			$bytes[$startIndex + 1] = (byte)($data << 16 >> 24);
			$bytes[$startIndex + 2] = (byte)($data << 8 >> 24);
			$bytes[$startIndex + 3] = (byte)($data >> 24);
		}
		else
		{
			$bytes[$startIndex] = (byte)($data >> 24);
			$bytes[$startIndex + 1] = (byte)($data << 8 >> 24);
			$bytes[$startIndex + 2] = (byte)($data << 16 >> 24);
			$bytes[$startIndex + 3] = (byte)($data << 24 >> 24);
		}
	}

	public static function GetBytesF(
		$data, 
		$bytes, 
		$startIndex, 
		$endian = MEndian::eLITTLE_ENDIAN
		)
	{
		if ($endian == MEndian::eLITTLE_ENDIAN)
		{
			$bytes[$startIndex] = (byte)($data << 56 >> 56);
			$bytes[$startIndex + 1] = (byte)($data << 48 >> 56);
			$bytes[$startIndex + 2] = (byte)($data << 40 >> 56);
			$bytes[$startIndex + 3] = (byte)($data << 32 >> 56);

			$bytes[$startIndex + 4] = (byte)($data << 24 >> 56);
			$bytes[$startIndex + 5] = (byte)($data << 16 >> 56);
			$bytes[$startIndex + 6] = (byte)($data << 8 >> 56);
			$bytes[$startIndex + 7] = (byte)($data >> 56);
		}
		else
		{
			$bytes[$startIndex] = (byte)($data >> 56);
			$bytes[$startIndex + 1] = (byte)($data << 8 >> 56);
			$bytes[$startIndex + 2] = (byte)($data << 16 >> 56);
			$bytes[$startIndex + 3] = (byte)($data << 24 >> 56);

			$bytes[$startIndex + 4] = (byte)($data << 32 >> 56);
			$bytes[$startIndex + 5] = (byte)($data << 40 >> 56);
			$bytes[$startIndex + 6] = (byte)($data << 48 >> 56);
			$bytes[$startIndex + 7] = (byte)($data << 56 >> 56);
		}
	}

	public static function GetBytesG(
		$data, 
		$bytes, 
		$startIndex, 
		$endian = MEndian::eLITTLE_ENDIAN
		)
	{
		if ($endian == MEndian::eLITTLE_ENDIAN)
		{
			bytes[startIndex] = (byte)($data << 56 >> 56);
			bytes[startIndex + 1] = (byte)($data << 48 >> 56);
			bytes[startIndex + 2] = (byte)($data << 40 >> 56);
			bytes[startIndex + 3] = (byte)($data << 32 >> 56);

			bytes[startIndex + 4] = (byte)($data << 24 >> 56);
			bytes[startIndex + 5] = (byte)($data << 16 >> 56);
			bytes[startIndex + 6] = (byte)($data << 8 >> 56);
			bytes[startIndex + 7] = (byte)($data >> 56);
		}
		else
		{
			$bytes[$startIndex] = (byte)($data >> 56);
			$bytes[$startIndex + 1] = (byte)($data << 8 >> 56);
			$bytes[$startIndex + 2] = (byte)($data << 16 >> 56);
			$bytes[$startIndex + 3] = (byte)($data << 24 >> 56);

			$bytes[$startIndex + 4] = (byte)($data << 32 >> 56);
			$bytes[$startIndex + 5] = (byte)($data << 40 >> 56);
			$bytes[$startIndex + 6] = (byte)($data << 48 >> 56);
			$bytes[$startIndex + 7] = (byte)($data << 56 >> 56);
		}
	}

	public static function ToInt32A($value)
	{
		return (int)(value);
	}
	
	public static function TryParseUshort($strValue, $refValue)
	{
	    return 0;
	}
	
	public static function TryParseShort($strValue, $refValue)
	{
	    return 0;
	}
	
	public static function TryParseUint($strValue, $refValue)
	{
	    return 0;
	}
	
	public static function TryParseInt($strValue, $refValue)
	{
	    return 0;
	}
	
	public static function TryParseFloat($strValue, $refValue)
	{
	    return 0;
	}
}

?>