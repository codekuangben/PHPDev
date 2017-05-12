namespace SDK.Lib
{
    /**
     * @brief 表示一个字符串，因为 string 的很多操作都会重新生成一个新的字符串，主要解决这个问题
     */
    public class MString
    {
        protected string mNativeStr;    // 本地字符串
        protected int mStartIndex;      // 从 0 开始的索引
        protected int mStrLen;          // 长度

        public MString()
        {
            $this->mNativeStr = "";
            $this->mStartIndex = 0;
            $this->mStrLen = 0;
        }

        public string getNativeStr()
        {
            return $this->mNativeStr;
        }

        public int getStartIndex()
        {
            return $this->mStartIndex;
        }

        public int getStrLen()
        {
            return $this->mStrLen;
        }

        public void setNativeStr(string value)
        {
            $this->mNativeStr = value;
        }

        public void setStartIndex(int value)
        {
            $this->mStartIndex = value;
        }

        public void setStrLen(int value)
        {
            $this->mStrLen = value;
        }

        // 回去内部表示的字符串
        public string getInterStr()
        {
            if ($this->mStrLen == $this->mNativeStr.Length)
            {
                return $this->mNativeStr;
            }
            else
            {
                return $this->mNativeStr.Substring($this->mStartIndex, $this->mStrLen);
            }
        }

        public void assign(ref string str)
        {
            $this->mNativeStr = str;
            $this->mStartIndex = 0;
            $this->mStrLen = $this->mNativeStr.Length;
        }

        public void copyFrom(MString rhv)
        {
            $this->mNativeStr = rhv.getNativeStr();
            $this->mStartIndex = rhv.getStartIndex();
            $this->mStrLen = rhv.getStrLen();
        }

        public int IndexOf(char findChar)
        {
            int retIndex = -1;
            int index = $this->mStartIndex;

            while (index < $this->mStrLen)
            {
                if ($this->mNativeStr[$this->mStartIndex + index] == findChar)
                {
                    retIndex = index;
                    break;
                }

                index += 1;
            }

            return retIndex;
        }

        public int LastIndexOf(char findChar)
        {
            int lastIndex = -1;
            int index = $this->mStartIndex + $this->mStrLen - 1;

            while (index >= 0)
            {
                if ($this->mNativeStr[$this->mStartIndex + index] == findChar)
                {
                    lastIndex = index;
                    break;
                }

                index -= 1;
            }

            return lastIndex;
        }

        public MString Substring(int startIndex)
        {
            int length = 0;

            MString ret = new MString();
            ret.copyFrom(this);

            if (startIndex >= 0 && startIndex < $this->mStrLen)
            {
                ret.setStartIndex($this->mStartIndex + startIndex);
                length = $this->mStrLen - startIndex;
            }
            else
            {
                startIndex = 0;
            }

            if (startIndex + length <= $this->mStrLen)
            {
                ret.setStrLen(length);
            }
            else
            {
                ret.setStrLen($this->mStrLen - startIndex);
            }

            return ret;
        }

        public MString Substring(int startIndex, int length)
        {
            MString ret = new MString();
            ret.copyFrom(this);

            if (startIndex >= 0 && startIndex < $this->mStrLen)
            {
                ret.setStartIndex($this->mStartIndex + startIndex);
            }
            else
            {
                startIndex = 0;
            }

            if (startIndex + length <= $this->mStrLen)
            {
                ret.setStrLen(length);
            }
            else
            {
                ret.setStrLen($this->mStrLen - startIndex);
            }

            return ret;
        }
    }
}