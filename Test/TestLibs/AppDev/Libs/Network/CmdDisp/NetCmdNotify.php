using System.Collections.Generic;

namespace SDK.Lib
{
    public class NetCmdNotify
    {
        protected int mRevMsgCnt;      // 接收到消息的数量
        protected int mHandleMsgCnt;   // 处理的消息的数量

        protected List<NetModuleDispHandle> mNetModuleDispList;
        protected bool mIsStopNetHandle;       // 是否停止网络消息处理
        protected CmdDispInfo mCmdDispInfo;

        public NetCmdNotify()
        {
            $this->mRevMsgCnt = 0;
            $this->mHandleMsgCnt = 0;
            $this->mNetModuleDispList = new List<NetModuleDispHandle>();
            $this->mIsStopNetHandle = false;
            $this->mCmdDispInfo = new CmdDispInfo();
        }

        public bool isStopNetHandle
        {
            get
            {
                return $this->mIsStopNetHandle;
            }
            set
            {
                $this->mIsStopNetHandle = value;
            }
        }

        public void addOneNofity(NetModuleDispHandle disp)
        {
            if ($this->mNetModuleDispList.IndexOf(disp) == -1)
            {
                $this->mNetModuleDispList.Add(disp);
            }
        }

        public void removeOneNotify(NetModuleDispHandle disp)
        {
            if ($this->mNetModuleDispList.IndexOf(disp) != -1)
            {
                $this->mNetModuleDispList.Remove(disp);
            }
        }

        public void handleMsg(ByteBuffer msg)
        {
            //if (false == mIsStopNetHandle)  // 如果没有停止网络处理
            //{
            byte byCmd = 0;
            msg.readUnsignedInt8(ref byCmd);
            byte byParam = 0;
            msg.readUnsignedInt8(ref byParam);
            msg.setPos(0);

            mCmdDispInfo.bu = msg;
            mCmdDispInfo.byCmd = byCmd;
            mCmdDispInfo.byParam = byParam;

            foreach (var item in mNetModuleDispList)
            {
                item.handleMsg(mCmdDispInfo);
            }
            //}
        }

        public void addOneRevMsg()
        {
            ++$this->mRevMsgCnt;            
        }

        public void addOneHandleMsg()
        {
            ++$this->mHandleMsgCnt;
        }

        public void clearOneRevMsg()
        {
            $this->mRevMsgCnt = 0;
        }

        public void clearOneHandleMsg()
        {
            $this->mHandleMsgCnt = 0;
        }
    }
}