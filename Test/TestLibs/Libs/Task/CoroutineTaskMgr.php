﻿using System.Collections;

namespace SDK.Lib
{
    public class CoroutineTaskMgr
    {
        protected MList<CoroutineTaskBase> mCoroutineTaskList;
        protected eCoroutineTaskState mState;
        protected CoroutineTaskBase mTmp;

        public CoroutineTaskMgr()
        {
            mCoroutineTaskList = new MList<CoroutineTaskBase>();
            mState = eCoroutineTaskState.eStopped;
        }

        public bool isRuning()
        {
            return mState == eCoroutineTaskState.eRunning;
        }

        public bool isPause()
        {
            return mState == eCoroutineTaskState.ePaused;
        }

        public bool isStop()
        {
            return mState == eCoroutineTaskState.eStopped;
        }

        public bool isEmpty()
        {
            return mCoroutineTaskList.length() == 0;
        }

        public void init()
        {
            if (!isRuning())
            {
                mState = eCoroutineTaskState.eRunning;
                Ctx.mInstance.mCoroutineMgr.StartCoroutine(run());
            }
        }

        public void dispose()
        {

        }

        public void pause()
        {
            if(!isPause())
            {
                mState = eCoroutineTaskState.ePaused;
            }
        }

        public void stop()
        {
            if (!isStop())
            {
                mState = eCoroutineTaskState.eStopped;
                mCoroutineTaskList.Clear();
            }
        }

        public void addTask(CoroutineTaskBase task)
        {
            if (mCoroutineTaskList.IndexOf(task) == -1)
            {
                mCoroutineTaskList.Add(task);
            }
        }

        protected IEnumerator run()
        {
            while (isRuning())
            {
                //yield return null;
                yield return 1;

                if (!isEmpty())
                {
                    if(mCoroutineTaskList[0].isRuning())
                    {
                        mCoroutineTaskList[0].run();
                    }
                    if (mCoroutineTaskList[0].isNeedRemove())
                    {
                        mCoroutineTaskList.RemoveAt(0);
                    }
                    else
                    {
                        if(mCoroutineTaskList.length() > 1)
                        {
                            // 放到最后，否则下一次还会先执行
                            mTmp = mCoroutineTaskList[0];
                            mCoroutineTaskList.RemoveAt(0);
                            mCoroutineTaskList.Add(mTmp);
                        }
                    }
                }

                //yield return null;
                yield return 1;
            }

            yield break;
        }
    }
}