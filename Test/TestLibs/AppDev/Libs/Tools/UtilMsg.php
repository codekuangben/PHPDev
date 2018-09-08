<?php

namespace SDK\Lib;

/**
 * @brief 处理消息工具
 */
class UtilMsg
{
	// 发送消息， bnet 如果 true 就直接发送到 socket ，否则直接进入输出消息队列
	public static function sendMsg($msg, $isSendToNet = true)
	{
	    Ctx::$mInstance->mShareData->mTmpBA = Ctx::$mInstance->mNetMgr->getSendBA();
		
		if (Ctx.mInstance.mShareData.mTmpBA != null)
		{
			msg.serialize(Ctx.mInstance.mShareData.mTmpBA);
		}
		else
		{
		}
		if (isSendToNet)
		{
			// 打印日志
		    Ctx::$mInstance->mShareData->mTmpStr = string.Format("Send msg: byCmd = {0}, byParam = {1}", msg.byCmd, msg.byParam);
		}
		Ctx.mInstance.mNetMgr.send(isSendToNet);
	}

	public static function sendMsg($byteArr, $startIndex, $length, $isSendToNet = true)
	{
	    Ctx::$mInstance->mShareData->mTmpBA = Ctx::$mInstance->mNetMgr->getSendBA();
		
		if (Ctx.mInstance.mShareData.mTmpBA != null)
		{
		    Ctx.mInstance.mShareData.mTmpBA.writeBytes($byteArr, $startIndex, $length);
		}
		else
		{
		}
		if ($isSendToNet)
		{
			// 打印日志
		    Ctx::$mInstance->mShareData->mTmpStr = string.Format("Send msg");
		}
		Ctx.mInstance.mNetMgr.send_KBE(isSendToNet);
	}

	public static function checkStr($str)
	{
		if (string.IsNullOrEmpty(str))
		{
		}
	}

	// 格式化消息数据到数组形式
	public static function formatBytes2Array($bytes, $len)
	{
		$str = "{ ";
		$isFirst = true;
		
		for ($idx = 0; $idx < $len; ++$idx)
		{
			if ($isFirst)
			{
				$isFirst = false;
			}
			else
			{
				$str += ", ";
			}
			
			$str += bytes[idx];
		}

		$str += " }";            
	}
}

?>