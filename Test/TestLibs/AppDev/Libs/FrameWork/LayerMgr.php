namespace SDK.Lib
{
    /**
     * @brief 框架层管理器
     */
    public class LayerMgr
    {
        public MDictionary<string, UnityEngine.GameObject> mPath2Go;

        public LayerMgr()
        {
            $this->mPath2Go = new MDictionary<string, UnityEngine.GameObject>();
        }

        public void init()
        {

        }

        public void dispose()
        {

        }
    }
}