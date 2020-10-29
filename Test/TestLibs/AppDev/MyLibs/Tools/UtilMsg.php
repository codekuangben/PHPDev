<?php

namespace MyLibs;

/**
 * @brief 处理消息工具
 */
class UtilMsg
{
	// 发送消息， bnet 如果 true 就直接发送到 socket ，否则直接进入输出消息队列
	public static function sendMsg($msg, $isSendToNet = true)
	{
	    Ctx::$msIns->mShareData->mTmpBA = Ctx::$msIns->mNetMgr->getSendBA();
		
		if (Ctx::$msIns->mShareData->mTmpBA != null)
		{
			$msg->serialize(Ctx::$msIns->mShareData->mTmpBA);
		}
		else
		{
		}
		if (isSendToNet)
		{
			// 打印日志
		    Ctx::$msIns->mShareData->mTmpStr = UtilStr::Format("Send msg: CmdId = {0}, ParamId = {1}", $msg->CmdId, $msg->ParamId);
		}
		Ctx::$msIns->mNetMgr->send(isSendToNet);
	}

	public static function sendMsgA($byteArr, $startIndex, $length, $isSendToNet = true)
	{
	    Ctx::$msIns->mShareData->mTmpBA = Ctx::$msIns->mNetMgr->getSendBA();
		
		if (Ctx::$msIns->mShareData->mTmpBA != null)
		{
		    Ctx::$msIns->mShareData->mTmpBA->writeBytes($byteArr, $startIndex, $length);
		}
		else
		{
		}
		if ($isSendToNet)
		{
			// 打印日志
		    Ctx::$msIns->mShareData->mTmpStr = UtilStr::Format("Send msg");
		}
		Ctx::$msIns->mNetMgr->send_KBE(isSendToNet);
	}

	public static function checkStr($str)
	{
		if (string::IsNullOrEmpty(str))
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