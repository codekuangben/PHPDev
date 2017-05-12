namespace SDK.Lib
{
    /**
     * @brief 时间间隔
     */
    public class TimeInterval
    {
        protected float mInterval;
        protected float mTotalTime;
        protected float mCurTime;

        protected int mTotalExecNum;    // 总共执行次数
        protected int mCurExecNum;      // 执行的次数

        public TimeInterval()
        {
            $this->mInterval = 1 / 10;    // 每一秒更新 10 次
            $this->mTotalTime = 0;
            $this->mCurTime = 0;

            $this->mTotalExecNum = 0;     // 如果是 0 ，就说明没有限制
            $this->mCurExecNum = 0;
        }

        public void setInterval(float value)
        {
            $this->mInterval = value;
        }

        public void setTotalTime(float value)
        {
            $this->mTotalTime = value;
        }

        public void setCurTime(float value)
        {
            $this->mCurTime = value;
        }

        public void setTotalExecNum(int value)
        {
            $this->mTotalExecNum = value;
        }

        public void setCurExecNum(int value)
        {
            $this->mCurExecNum = value;
        }

        // 当前是否满足间隔条件
        public bool canExec(float delta)
        {
            bool ret = false;

            $this->mTotalTime += delta;
            $this->mCurTime += delta;

            if($this->mCurTime >= $this->mInterval)
            {
                if (0 == $this->mTotalExecNum ||
                    $this->mCurExecNum < $this->mTotalExecNum)
                {
                    $this->mCurTime -= $this->mInterval;
                    ret = true;
                }
                else
                {
                    ret = false;
                }

                $this->mCurExecNum += 1;
            }

            return ret;
        }

        // 是否执行结束
        public bool isExecEnd()
        {
            bool ret = false;

            if (0 != $this->mTotalExecNum &&
                $this->mCurExecNum >= $this->mTotalExecNum)
            {
                ret = true;
            }

            return ret;
        }
    }
}