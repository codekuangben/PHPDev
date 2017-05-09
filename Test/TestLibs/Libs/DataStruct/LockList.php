using System;

namespace SDK.Lib
{
    /**
     * @brief 线程安全列表， T 是 Object ，便于使用 Equal 比较地址
     */
    public class LockList<T>
    {
        protected DynBuffer<T> mDynamicBuffer;
        protected MMutex mVisitMutex;
        protected T mRetItem;

        public LockList(string name, uint initCapacity = 32/*DataCV.INIT_ELEM_CAPACITY*/, uint maxCapacity = 8 * 1024 * 1024/*DataCV.MAX_CAPACITY*/)
        {
            this.mDynamicBuffer = new DynBuffer<T>(initCapacity, maxCapacity);
            this.mVisitMutex = new MMutex(false, name);
        }

        //public uint Count
        //{ 
        //    get
        //    {
        //        using (MLock mlock = new MLock(mVisitMutex))
        //        {
        //            return mDynamicBuffer.mSize;
        //        }
        //    }
        //}

        public uint Count()
        {
            using (MLock mlock = new MLock(mVisitMutex))
            {
                return this.mDynamicBuffer.mSize;
            }
        }

        public T this[int index] 
        { 
            get
            {
                using (MLock mlock = new MLock(mVisitMutex))
                {
                    if (index < mDynamicBuffer.mSize)
                    {
                        return this.mDynamicBuffer.mBuffer[index];
                    }
                    else
                    {
                        return default(T);
                    }
                }
            }

            set
            {
                using (MLock mlock = new MLock(mVisitMutex))
                {
                    this.mDynamicBuffer.mBuffer[index] = value;
                }
            }
        }

        public void Add(T item)
        {
            using (MLock mlock = new MLock(mVisitMutex))
            {
                if (this.mDynamicBuffer.mSize >= mDynamicBuffer.mCapacity)
                {
                    this.mDynamicBuffer.extendDeltaCapicity(1);
                }

                this.mDynamicBuffer.mBuffer[mDynamicBuffer.mSize] = item;
                ++this.mDynamicBuffer.mSize;
            }
        }

        public bool Remove(T item)
        {
            using (MLock mlock = new MLock(mVisitMutex))
            {
                int idx = 0;
                foreach (var elem in this.mDynamicBuffer.mBuffer)
                {
                    if(item.Equals(elem))       // 地址比较
                    {
                        this.RemoveAt(idx);
                        return true;
                    }

                    ++idx;
                }

                return false;
            }
        }

        public T RemoveAt(int index)
        {
            using (MLock mlock = new MLock(mVisitMutex))
            {
                if (index < this.mDynamicBuffer.mSize)
                {
                    this.mRetItem = this.mDynamicBuffer.mBuffer[index];

                    if (index < this.mDynamicBuffer.mSize)
                    {
                        if (index != this.mDynamicBuffer.mSize - 1 && 1 != this.mDynamicBuffer.mSize) // 如果删除不是最后一个元素或者总共就大于一个元素
                        {
                            Array.Copy(this.mDynamicBuffer.mBuffer, index + 1, this.mDynamicBuffer.mBuffer, index, this.mDynamicBuffer.mSize - 1 - index);
                        }

                        --this.mDynamicBuffer.mSize;
                    }
                }
                else
                {
                    this.mRetItem = default(T);
                }

                return this.mRetItem;
            }
        }

        public int IndexOf(T item)
        {
            using (MLock mlock = new MLock(mVisitMutex))
            {
                int idx = 0;

                foreach (var elem in this.mDynamicBuffer.mBuffer)
                {
                    if (item.Equals(elem))       // 地址比较
                    {
                        this.RemoveAt(idx);
                        return idx;
                    }

                    ++idx;
                }

                return -1;
            }
        }
    }
}