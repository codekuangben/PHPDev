<?php

namespace SDK\Lib;

/**
 * @brief Profile，要一段时间配置一次，每一帧配置是没有意义的
 */
public class MProfiler
{
	protected bool mEnabled;
	protected int mNameFieldWidth;
	protected int mDataWidth;
	protected int mIndentAmount;

	protected bool mReallyEnabled;
	protected bool mWantReport;
	protected bool mWantWipe;
	protected int mStackDepth;

	protected MProfileInfo mRootNode;
	protected MProfileInfo mCurrentNode;
	protected bool mIsStartProfile;
	protected string mRootNodeName;
	protected bool mPrintLog;

	public MProfiler()
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
	
	public void init()
	{
		if (MacroDef.ENABLE_LOG)
		{
			Ctx.mInstance.mLogSys.log("MProfiler::init", LogTypeId.eLogProfileDebug);
		}
	}

	public void dispose()
	{
		if (MacroDef.ENABLE_LOG)
		{
			Ctx.mInstance.mLogSys.log("MProfiler::dispose", LogTypeId.eLogProfileDebug);
		}
	}

	public bool isStartProfile()
	{
		return $this->mIsStartProfile;
	}

	public void setIsStartProfile(bool value)
	{
		$this->mIsStartProfile = value;

		if (MacroDef.ENABLE_LOG)
		{
			Ctx.mInstance.mLogSys.log(string.Format("MProfiler::setIsStartProfile, IsStartProfile = {0}", $this->mIsStartProfile), LogTypeId.eLogProfileDebug);
		}
	}

	public void toggleIsStartProfile()
	{
		$this->mIsStartProfile = !$this->mIsStartProfile;

		if (MacroDef.ENABLE_LOG)
		{
			Ctx.mInstance.mLogSys.log(string.Format("MProfiler::toggleIsStartProfile, IsStartProfile = {0}", $this->mIsStartProfile), LogTypeId.eLogProfileDebug);
		}
	}

	protected void checkInternalState()
	{
		// 如果我们在 root ，我们可以更新我们内部开启的状态
		if ($this->mStackDepth == 0)
		{
			// 是否控制
			if ($this->mIsStartProfile)
			{
				if (!$this->mPrintLog)
				{
					$this->mPrintLog = true;

					if (MacroDef.ENABLE_LOG)
					{
						Ctx.mInstance.mLogSys.log(string.Format("MProfiler::checkInternalState, StackDepth = {0}, IsStartProfile = {1}, Enabled = {2}", $this->mStackDepth, $this->mIsStartProfile, $this->mEnabled), LogTypeId.eLogProfileDebug);
					}
				}

				if (!$this->mEnabled)
				{
					if (MacroDef.ENABLE_LOG)
					{
						Ctx.mInstance.mLogSys.log("MProfiler::checkInternalState, StartWantWipe", LogTypeId.eLogProfileDebug);
					}

					$this->mWantWipe = true;
					$this->mEnabled = true;
				}
			}
			else
			{
				if ($this->mEnabled)
				{
					if (MacroDef.ENABLE_LOG)
					{
						Ctx.mInstance.mLogSys.log("MProfiler::checkInternalState, StartWantReport", LogTypeId.eLogProfileDebug);
					}

					$this->mWantReport = true;
					$this->mEnabled = false;
				}
			}

			$this->mReallyEnabled = $this->mEnabled;
			// 必然开启设置这里，测试 enter 和 exit 不匹配       
			// $this->mReallyEnabled = true;

			// 清理所有配置数据，开始重新收集数据
			if ($this->mWantWipe)
			{
				if (MacroDef.ENABLE_LOG)
				{
					Ctx.mInstance.mLogSys.log("MProfiler::checkInternalState, doWipe", LogTypeId.eLogProfileDebug);
				}

				$this->doWipe();
			}

			// 输出配置
			if ($this->mWantReport)
			{
				if (MacroDef.ENABLE_LOG)
				{
					Ctx.mInstance.mLogSys.log("MProfiler::checkInternalState, doReport", LogTypeId.eLogProfileDebug);
				}

				$this->doReport();
			}
		}
	}

	/**
	 * @brief 进入一个命名的函数块
	 */
	public void enter(string blockName)
	{
		if (MacroDef.ENABLE_LOG)
		{
			Ctx.mInstance.mLogSys.log(string.Format("MProfiler::enter, blockName = {0}, ReallyEnabled = {1}, StackDepth = {2}", blockName, $this->mReallyEnabled, $this->mStackDepth), LogTypeId.eLogProfileDebug);
		}

		// 第一次进入的时候判断是否有根节点
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

		// 查找 Child如果没有就创建
		MProfileInfo newNode = $this->mCurrentNode.mChildren[blockName];

		if (null == newNode)
		{
			newNode = new MProfileInfo(blockName, $this->mCurrentNode);
			$this->mCurrentNode.mChildren[blockName] = newNode;
		}

		// 压入堆栈
		$this->mCurrentNode = newNode;

		// 开始计时 Child Node
		$this->mCurrentNode.mStartTime = UtilApi.getFloatUTCMilliseconds();

		if (MacroDef.ENABLE_LOG)
		{
			Ctx.mInstance.mLogSys.log(string.Format("MProfiler::enter, blockName = {0}, StartTime = {1}", blockName, $this->mCurrentNode.mStartTime), LogTypeId.eLogProfileDebug);
		}
	}
	
	/**
	 * @brief 指明我们退出一个命名执行块
	 */
	public void exit(string blockName)
	{
		if (MacroDef.ENABLE_LOG)
		{
			Ctx.mInstance.mLogSys.log(string.Format("MProfiler::exit, blockName = {0}, ReallyEnabled = {1}, StackDepth = {2}", blockName, $this->mReallyEnabled, $this->mStackDepth), LogTypeId.eLogProfileDebug);
		}

		// 更新堆栈深度，及早退出
		$this->mStackDepth -= 1;

		if (!$this->mReallyEnabled)
		{
			return;
		}

		if (blockName != $this->mCurrentNode.mName)
		{
			throw new System.Exception("MProfiler::exit, Mismatched Profiler.enter/Profiler.exit calls, got '" + $this->mCurrentNode.mName + "' but was expecting '" + blockName + "'");
		}

		// 更新这个 node 的状态
		double currentTime = UtilApi.getFloatUTCMilliseconds();
		double elapsedTime = currentTime - $this->mCurrentNode.mStartTime;

		$this->mCurrentNode.mActivations += 1;
		$this->mCurrentNode.mTotalTime += elapsedTime;

		if (MacroDef.ENABLE_LOG)
		{
			Ctx.mInstance.mLogSys.log(string.Format("MProfiler::exit, blockName = {0}, TotalTime = {1}, currentTime = {2}", blockName, $this->mCurrentNode.mTotalTime, currentTime), LogTypeId.eLogProfileDebug);
		}

		if (elapsedTime > $this->mCurrentNode.mMaxTime)
		{
			$this->mCurrentNode.mMaxTime = elapsedTime;
		}

		if (elapsedTime < $this->mCurrentNode.mMinTime)
		{
			$this->mCurrentNode.mMinTime = elapsedTime;
		}

		// 弹出堆栈
		$this->mCurrentNode = $this->mCurrentNode.mParent;
	}
	
	/**
	 * @brief Dump 统计信息到日志，下一次我们到达堆栈底部
	 */
	public void report()
	{
		if ($this->mStackDepth > 0)
		{
			$this->mWantReport = true;
			return;
		}

		$this->doReport();
	}
	
	/**
	 * 重置所有的统计信息到零
	 */
	public void wipe()
	{
		if ($this->mStackDepth > 0)
		{
			$this->mWantReport = true;
			return;
		}

		$this->doWipe();
	}
	
	/**
	 * @brief 确保配置状态没有不匹配
	 */
	public void ensureAtRoot()
	{
		if ($this->mStackDepth > 0)
		{
			throw new System.Exception("MProfiler::ensureAtRoot, Not at root!");
		}
	}
	
	private void doReport()
	{
		$this->mWantReport = false;

		string header = string.Format("[{0}{1}][{2}{3}][{4}{5}][{6}{7}][{8}{9}][{10}{11}][{12}{13}][{14}{15}][{16}{17}]",
									  UtilStr.toStringByCount(mNameFieldWidth - "name".Length, " "), "name",
									  UtilStr.toStringByCount($this->mDataWidth - "Total%".Length, " "), "Total%",
									  UtilStr.toStringByCount($this->mDataWidth - "Self%".Length, " "), "Self%",
									  UtilStr.toStringByCount($this->mDataWidth - "Calls".Length, " "), "Calls",
									  UtilStr.toStringByCount($this->mDataWidth - "Total ms".Length, " "), "Total ms",
									  UtilStr.toStringByCount($this->mDataWidth - "self ms".Length, " "), "self ms",
									  UtilStr.toStringByCount($this->mDataWidth - "AvgMs".Length, " "), "AvgMs",
									  UtilStr.toStringByCount($this->mDataWidth - "MinMs".Length, " "), "MinMs",
									  UtilStr.toStringByCount($this->mDataWidth - "MaxMs".Length, " "), "MaxMs");

		if (MacroDef.ENABLE_LOG)
		{
			Ctx.mInstance.mLogSys.log(header, LogTypeId.eLogProfile);
		}

		$this->reportNode($this->mRootNode, 0);
	}
	
	private void reportNode(MProfileInfo profileInfo, int indent)
	{
		bool hasChild = false;   // 是否有 Child
		double totalTime = 0;

		foreach (System.Collections.Generic.KeyValuePair<string, MProfileInfo> kv in profileInfo.mChildren)
		{
			MProfileInfo childProfileInfo = kv.Value;

			hasChild = true;
			profileInfo.mSelfTime = profileInfo.mTotalTime - childProfileInfo.mTotalTime;
			totalTime += childProfileInfo.mTotalTime;
		}

		if (profileInfo.mName == $this->mRootNodeName)
		{
			profileInfo.mTotalTime = totalTime;
			profileInfo.mSelfTime = 0;
		}
		
		string entry = "";

		if (indent == 0)
		{
			entry = "+Root";
		}
		else
		{
			entry = $this->formatProfile(indent, hasChild, profileInfo);
		}

		if (MacroDef.ENABLE_LOG)
		{
			Ctx.mInstance.mLogSys.log(entry, LogTypeId.eLogProfile);
		}

		MList<MProfileInfo> tmpArray = new MList<MProfileInfo>();

		foreach (System.Collections.Generic.KeyValuePair<string, MProfileInfo> kv in profileInfo.mChildren)
		{
			MProfileInfo childProfileInfo = kv.Value;
			tmpArray.push(childProfileInfo);
		}

		tmpArray.Sort($this->sortByTotalTime);

		foreach (MProfileInfo childPi in tmpArray.list())
		{
			$this->reportNode(childPi, indent + 1);
		}
	}
	
	private void doWipe(MProfileInfo profileInfo = null)
	{
		$this->mWantWipe = false;
		
		if (null == profileInfo)
		{
			$this->doWipe($this->mRootNode);
			return;
		}

		profileInfo.wipe();

		foreach (System.Collections.Generic.KeyValuePair<string, MProfileInfo> kv in profileInfo.mChildren)
		{
			MProfileInfo childProfileInfo = kv.Value;
			$this->doWipe(childProfileInfo);
		}
	}

	protected int sortByTotalTime(MProfileInfo a, MProfileInfo b)
	{
		int ret = 0;

		if(a.mTotalTime < b.mTotalTime)
		{
			ret = 1;
		}
		else if (a.mTotalTime > b.mTotalTime)
		{
			ret = -1;
		}

		return ret;
	}

	protected string formatProfile(int indent, bool hasChild, MProfileInfo profileInfo)
	{
		double totalTimePercent = -1;
		if (null != profileInfo.mParent)
		{
			totalTimePercent = profileInfo.mTotalTime / $this->mRootNode.mTotalTime * 100;
		}

		double selfTimePercent = -1;
		if (null != profileInfo.mParent)
		{
			selfTimePercent = profileInfo.mSelfTime / ($this->mRootNode.mTotalTime) * 100;
		}

		// 这个开始字符主要看层次关系
		string startStr = indent.ToString();
		string startPrefix = UtilStr.toStringByCount(indent * $this->mIndentAmount - startStr.Length, " ");

		string nameStr = (hasChild ? "+" : "-") + profileInfo.mName;
		string namePrefix = UtilStr.toStringByCount($this->mNameFieldWidth - indent * $this->mIndentAmount - nameStr.Length, " ");

		string totalTimePercentStr = totalTimePercent.ToString("F2");
		string totalTimePercentPrefix = UtilStr.toStringByCount($this->mDataWidth - totalTimePercentStr.Length, " ");

		string selfTimePercentStr = selfTimePercent.ToString("F2");
		string selfTimePercentPrefix = UtilStr.toStringByCount($this->mDataWidth - selfTimePercentStr.Length, " ");

		string activationsStr = profileInfo.mActivations.ToString();
		string activationsPrefix = UtilStr.toStringByCount($this->mDataWidth - activationsStr.Length, " ");

		string totalTimeStr = profileInfo.mTotalTime.ToString("F2");
		string totalTimePrefix = UtilStr.toStringByCount($this->mDataWidth - totalTimeStr.Length, " ");

		string selfTimeStr = profileInfo.mSelfTime.ToString("F2");
		string selfTimePrefix = UtilStr.toStringByCount($this->mDataWidth - selfTimeStr.Length, " ");

		string averageTimeStr = (profileInfo.mTotalTime / profileInfo.mActivations).ToString("F2");
		string averageTimePrefix = UtilStr.toStringByCount(8 - averageTimeStr.Length, " ");

		string minTimeStr = profileInfo.mMinTime.ToString("F2");
		string minTimePrefix = UtilStr.toStringByCount(8 - minTimeStr.Length, " ");

		string maxTimeStr = profileInfo.mMaxTime.ToString("F2");
		string maxTimePrefix = UtilStr.toStringByCount(8 - maxTimeStr.Length, " ");

		string retStr = string.Format("[{0}{1}][{2}{3}][{4}{5}][{6}{7}][{8}{9}][{10}{11}][{12}{13}][{14}{15}][{16}{17}][{18}{19}]",
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