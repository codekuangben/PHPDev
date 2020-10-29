<?php

namespace MyLibs;

/**
 * @brief Profile
 */
class MProfiler
{
	protected $mEnabled;
	protected $mNameFieldWidth;
	protected $mDataWidth;
	protected $mIndentAmount;

	protected $mReallyEnabled;
	protected $mWantReport;
	protected $mWantWipe;
	protected $mStackDepth;

	protected $mRootNode;
	protected $mCurrentNode;
	protected $mIsStartProfile;
	protected $mRootNodeName;
	protected $mPrintLog;

	public function __construct()
	{
		$this->mEnabled = false;
		$this->mNameFieldWidth = 50;
		$this->mDataWidth = 8;
		$this->mIndentAmount = 4;
		$this->mReallyEnabled = true;

		$this->mWantReport = false;
		$this->mWantWipe = false;
		$this->mStackDepth = 0;
		$this->mRootNode = null;
		$this->mCurrentNode = null;
		$this->mRootNode = null;
		$this->mIsStartProfile = false;

		$this->mRootNodeName = "Root";
		$this->mPrintLog = false;
	}
	
	public function init()
	{
		if (MacroDef::ENABLE_LOG)
		{
			Ctx::$msInstance->mLogSys->log("MProfiler::init", LogTypeId::eLogProfileDebug);
		}
	}

	public function dispose()
	{
		if (MacroDef::ENABLE_LOG)
		{
			Ctx::$msInstance->mLogSys->log("MProfiler::dispose", LogTypeId::eLogProfileDebug);
		}
	}

	public function isStartProfile()
	{
		return $this->mIsStartProfile;
	}

	public function setIsStartProfile($value)
	{
		$this->mIsStartProfile = value;

		if (MacroDef::ENABLE_LOG)
		{
			Ctx::$msInstance->mLogSys->log(string.Format("MProfiler::setIsStartProfile, IsStartProfile = {0}", $this->mIsStartProfile), LogTypeId::eLogProfileDebug);
		}
	}

	public function toggleIsStartProfile()
	{
		$this->mIsStartProfile = !$this->mIsStartProfile;

		if (MacroDef::ENABLE_LOG)
		{
			Ctx::$msInstance->mLogSys->log(string.Format("MProfiler::toggleIsStartProfile, IsStartProfile = {0}", $this->mIsStartProfile), LogTypeId::eLogProfileDebug);
		}
	}

	protected function checkInternalState()
	{
		// 濡傛灉鎴戜滑鍦� root 锛屾垜浠彲浠ユ洿鏂版垜浠唴閮ㄥ紑鍚殑鐘舵��
		if ($this->mStackDepth == 0)
		{
			// 鏄惁鎺у埗
			if ($this->mIsStartProfile)
			{
				if (!$this->mPrintLog)
				{
					$this->mPrintLog = true;

					if (MacroDef::ENABLE_LOG)
					{
						Ctx::$msInstance->mLogSys->log(string.Format("MProfiler::checkInternalState, StackDepth = {0}, IsStartProfile = {1}, Enabled = {2}", $this->mStackDepth, $this->mIsStartProfile, $this->mEnabled), LogTypeId::eLogProfileDebug);
					}
				}

				if (!$this->mEnabled)
				{
					if (MacroDef::ENABLE_LOG)
					{
						Ctx::$msInstance->mLogSys->log("MProfiler::checkInternalState, StartWantWipe", LogTypeId::eLogProfileDebug);
					}

					$this->mWantWipe = true;
					$this->mEnabled = true;
				}
			}
			else
			{
				if ($this->mEnabled)
				{
					if (MacroDef::ENABLE_LOG)
					{
						Ctx::$msInstance->mLogSys->log("MProfiler::checkInternalState, StartWantReport", LogTypeId::eLogProfileDebug);
					}

					$this->mWantReport = true;
					$this->mEnabled = false;
				}
			}

			$this->mReallyEnabled = $this->mEnabled;
			// 蹇呯劧寮�鍚缃繖閲岋紝娴嬭瘯 enter 鍜� exit 涓嶅尮閰�       
			// $this->mReallyEnabled = true;

			// 娓呯悊鎵�鏈夐厤缃暟鎹紝寮�濮嬮噸鏂版敹闆嗘暟鎹�
			if ($this->mWantWipe)
			{
				if (MacroDef::ENABLE_LOG)
				{
					Ctx::$msInstance->mLogSys->log("MProfiler::checkInternalState, doWipe", LogTypeId::eLogProfileDebug);
				}

				$this->doWipe();
			}

			// 杈撳嚭閰嶇疆
			if ($this->mWantReport)
			{
				if (MacroDef::ENABLE_LOG)
				{
					Ctx::$msInstance->mLogSys->log("MProfiler::checkInternalState, doReport", LogTypeId::eLogProfileDebug);
				}

				$this->doReport();
			}
		}
	}

	/**
	 * @brief 杩涘叆涓�涓懡鍚嶇殑鍑芥暟鍧�
	 */
	public function enter($blockName)
	{
		if (MacroDef::ENABLE_LOG)
		{
			Ctx::$msInstance->mLogSys->log(string.Format("MProfiler::enter, blockName = {0}, ReallyEnabled = {1}, StackDepth = {2}", blockName, $this->mReallyEnabled, $this->mStackDepth), LogTypeId::eLogProfileDebug);
		}

		// 绗竴娆¤繘鍏ョ殑鏃跺�欏垽鏂槸鍚︽湁鏍硅妭鐐�
		if (null == $this->mCurrentNode)
		{
			$this->mRootNode = new MProfileInfo($this->mRootNodeName);
			$this->mCurrentNode = $this->mRootNode;
		}

		$this->checkInternalState();

		$this->mStackDepth += 1;

		if (!$this->mReallyEnabled)
		{
			return;
		}

		// 鏌ユ壘 Child濡傛灉娌℃湁灏卞垱寤�
		$newNode = $this->mCurrentNode.mChildren[blockName];

		if (null == newNode)
		{
			$newNode = new MProfileInfo(blockName, $this->mCurrentNode);
			$this->mCurrentNode->mChildren[blockName] = newNode;
		}

		// 鍘嬪叆鍫嗘爤
		$this->mCurrentNode = newNode;

		// 寮�濮嬭鏃� Child Node
		$this->mCurrentNode->mStartTime = UtilSysLibWrap.getFloatUTCMilliseconds();

		if (MacroDef::ENABLE_LOG)
		{
			Ctx::$msInstance->mLogSys->log(string.Format("MProfiler::enter, blockName = {0}, StartTime = {1}", blockName, $this->mCurrentNode.mStartTime), LogTypeId::eLogProfileDebug);
		}
	}
	
	/**
	 * @brief 鎸囨槑鎴戜滑閫�鍑轰竴涓懡鍚嶆墽琛屽潡
	 */
	public function exit($blockName)
	{
		if (MacroDef::ENABLE_LOG)
		{
			Ctx::$msInstance->mLogSys->log(string.Format("MProfiler::exit, blockName = {0}, ReallyEnabled = {1}, StackDepth = {2}", blockName, $this->mReallyEnabled, $this->mStackDepth), LogTypeId::eLogProfileDebug);
		}

		// 鏇存柊鍫嗘爤娣卞害锛屽強鏃╅��鍑�
		$this->mStackDepth -= 1;

		if (!$this->mReallyEnabled)
		{
			return;
		}

		if (blockName != $this->mCurrentNode.mName)
		{
		    throw new \Exception("MProfiler::exit, Mismatched Profiler.enter/Profiler.exit calls, got '" + $this->mCurrentNode.mName + "' but was expecting '" + blockName + "'");
		}

		// 鏇存柊杩欎釜 node 鐨勭姸鎬�
		$currentTime = UtilSysLibWrap.getFloatUTCMilliseconds();
		$elapsedTime = currentTime - $this->mCurrentNode.mStartTime;

		$this->mCurrentNode->mActivations += 1;
		$this->mCurrentNode->mTotalTime += elapsedTime;

		if (MacroDef::ENABLE_LOG)
		{
			Ctx::$msInstance->mLogSys->log(string.Format("MProfiler::exit, blockName = {0}, TotalTime = {1}, currentTime = {2}", blockName, $this->mCurrentNode.mTotalTime, currentTime), LogTypeId::eLogProfileDebug);
		}

		if (elapsedTime > $this->mCurrentNode.mMaxTime)
		{
			$this->mCurrentNode->mMaxTime = elapsedTime;
		}

		if (elapsedTime < $this->mCurrentNode.mMinTime)
		{
			$this->mCurrentNode->mMinTime = elapsedTime;
		}

		// 寮瑰嚭鍫嗘爤
		$this->mCurrentNode = $this->mCurrentNode.mParent;
	}
	
	/**
	 * @brief Dump 缁熻淇℃伅鍒版棩蹇楋紝涓嬩竴娆℃垜浠埌杈惧爢鏍堝簳閮�
	 */
	public function report()
	{
		if ($this->mStackDepth > 0)
		{
			$this->mWantReport = true;
			return;
		}

		$this->doReport();
	}
	
	/**
	 * 閲嶇疆鎵�鏈夌殑缁熻淇℃伅鍒伴浂
	 */
	public function wipe()
	{
		if ($this->mStackDepth > 0)
		{
			$this->mWantReport = true;
			return;
		}

		$this->doWipe();
	}
	
	/**
	 * @brief 纭繚閰嶇疆鐘舵�佹病鏈変笉鍖归厤
	 */
	public function ensureAtRoot()
	{
		if ($this->mStackDepth > 0)
		{
		    throw new \Exception("MProfiler::ensureAtRoot, Not at root!");
		}
	}
	
	private function doReport()
	{
		$this->mWantReport = false;

		$header = string.Format("[{0}{1}][{2}{3}][{4}{5}][{6}{7}][{8}{9}][{10}{11}][{12}{13}][{14}{15}][{16}{17}]",
									  UtilStr.toStringByCount(mNameFieldWidth - "name".Length, " "), "name",
									  UtilStr.toStringByCount($this->mDataWidth - "Total%".Length, " "), "Total%",
									  UtilStr.toStringByCount($this->mDataWidth - "Self%".Length, " "), "Self%",
									  UtilStr.toStringByCount($this->mDataWidth - "Calls".Length, " "), "Calls",
									  UtilStr.toStringByCount($this->mDataWidth - "Total ms".Length, " "), "Total ms",
									  UtilStr.toStringByCount($this->mDataWidth - "self ms".Length, " "), "self ms",
									  UtilStr.toStringByCount($this->mDataWidth - "AvgMs".Length, " "), "AvgMs",
									  UtilStr.toStringByCount($this->mDataWidth - "MinMs".Length, " "), "MinMs",
									  UtilStr.toStringByCount($this->mDataWidth - "MaxMs".Length, " "), "MaxMs");

		if (MacroDef::ENABLE_LOG)
		{
			Ctx::$msInstance->mLogSys->log(header, LogTypeId::eLogProfile);
		}

		$this->reportNode($this->mRootNode, 0);
	}
	
	private function reportNode($profileInfo, $indent)
	{
		$hasChild = false;   // 鏄惁鏈� Child
		$totalTime = 0;

		while(list($key, $val) = each($profileInfo->mChildren))
		{
			$childProfileInfo = $val;

			$hasChild = true;
			$profileInfo->mSelfTime = profileInfo.mTotalTime - childProfileInfo.mTotalTime;
			$totalTime += childProfileInfo.mTotalTime;
		}

		if (profileInfo.mName == $this->mRootNodeName)
		{
			$profileInfo->mTotalTime = totalTime;
			$profileInfo->mSelfTime = 0;
		}
		
		$entry = "";

		if ($indent == 0)
		{
			$entry = "+Root";
		}
		else
		{
			$entry = $this->formatProfile($indent, $hasChild, $profileInfo);
		}

		if (MacroDef::ENABLE_LOG)
		{
			Ctx::$msInstance->mLogSys->log(entry, LogTypeId::eLogProfile);
		}

		$tmpArray = new MList();

		while(list($key, $val) = each($profileInfo->mChildren))
		{
			$childProfileInfo = $val;
			tmpArray.push(childProfileInfo);
		}

		tmpArray.Sort($this->sortByTotalTime);

		foreach ($tmpArray->list() as $childPi)
		{
			$this->reportNode($childPi, $indent + 1);
		}
	}
	
	private function doWipe($profileInfo = null)
	{
		$this->mWantWipe = false;
		
		if (null == profileInfo)
		{
			$this->doWipe($this->mRootNode);
			return;
		}

		profileInfo.wipe();

		while(list($key, $val) = each($profileInfo->mChildren))
		{
			$childProfileInfo = $val;
			$this->doWipe(childProfileInfo);
		}
	}

	protected function sortByTotalTime($a, $b)
	{
		$ret = 0;

		if(a.mTotalTime < b.mTotalTime)
		{
			$ret = 1;
		}
		else if (a.mTotalTime > b.mTotalTime)
		{
			$ret = -1;
		}

		return ret;
	}

	protected function formatProfile($indent, $hasChild, $profileInfo)
	{
		$totalTimePercent = -1;
		if (null != profileInfo.mParent)
		{
			$totalTimePercent = profileInfo.mTotalTime / $this->mRootNode.mTotalTime * 100;
		}

		$selfTimePercent = -1;
		if (null != profileInfo.mParent)
		{
			$selfTimePercent = profileInfo.mSelfTime / ($this->mRootNode.mTotalTime) * 100;
		}

		// 杩欎釜寮�濮嬪瓧绗︿富瑕佺湅灞傛鍏崇郴
		$startStr = indent.ToString();
		$startPrefix = UtilStr.toStringByCount(indent * $this->mIndentAmount - startStr.Length, " ");

		$nameStr = (hasChild ? "+" : "-") + profileInfo.mName;
		$namePrefix = UtilStr.toStringByCount($this->mNameFieldWidth - indent * $this->mIndentAmount - nameStr.Length, " ");

		$totalTimePercentStr = totalTimePercent.ToString("F2");
		$totalTimePercentPrefix = UtilStr.toStringByCount($this->mDataWidth - totalTimePercentStr.Length, " ");

		$selfTimePercentStr = selfTimePercent.ToString("F2");
		$selfTimePercentPrefix = UtilStr.toStringByCount($this->mDataWidth - selfTimePercentStr.Length, " ");

		$activationsStr = profileInfo.mActivations.ToString();
		$activationsPrefix = UtilStr.toStringByCount($this->mDataWidth - activationsStr.Length, " ");

		$totalTimeStr = profileInfo.mTotalTime.ToString("F2");
		$totalTimePrefix = UtilStr.toStringByCount($this->mDataWidth - totalTimeStr.Length, " ");

		$selfTimeStr = profileInfo.mSelfTime.ToString("F2");
		$selfTimePrefix = UtilStr.toStringByCount($this->mDataWidth - selfTimeStr.Length, " ");

		$averageTimeStr = (profileInfo.mTotalTime / profileInfo.mActivations).ToString("F2");
		$averageTimePrefix = UtilStr.toStringByCount(8 - averageTimeStr.Length, " ");

		$minTimeStr = profileInfo.mMinTime.ToString("F2");
		$minTimePrefix = UtilStr.toStringByCount(8 - minTimeStr.Length, " ");

		$maxTimeStr = profileInfo.mMaxTime.ToString("F2");
		$maxTimePrefix = UtilStr.toStringByCount(8 - maxTimeStr.Length, " ");

		$retStr = string.Format("[{0}{1}][{2}{3}][{4}{5}][{6}{7}][{8}{9}][{10}{11}][{12}{13}][{14}{15}][{16}{17}][{18}{19}]",
									startStr, startPrefix,
									namePrefix, nameStr,
									totalTimePercentPrefix, totalTimePercentStr,
									selfTimePercentPrefix, selfTimePercentStr,
									activationsPrefix, activationsStr,
									totalTimePrefix, totalTimeStr,
									selfTimePrefix, selfTimeStr,
									averageTimePrefix, averageTimeStr,
									minTimePrefix, minTimeStr,
									maxTimePrefix, maxTimeStr);

		return retStr;
	}
}

?>