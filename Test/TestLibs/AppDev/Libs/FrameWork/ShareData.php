namespace SDK.Lib
{
/**
 * @brief 共享内容，主要是数据
 */
public class ShareData
{
	public string mTmpStr = "";
	//public string m_retLangStr = "";     // 返回的语言描述，多线程访问会有问题，因此不用了
	public ByteBuffer mTmpBA;
	//public string m_resInPakPath = null;            // 返回的资源所在的包的目录
	public int noticeTimes = 0;
	public int noticeId = 0;
	public string noticeMsg = "";

	public uint top1_id = 0;//当前第一名
	public bool isTop1ShowArrow = false;
	public UnityEngine.Vector2 top1_arrow_pos;//指示箭头pos
	public UnityEngine.Quaternion top1_arrow_rotation;//指示箭头rotation

	public uint top2_id = 0;//当前第二名
	public bool isTop2ShowArrow = false;
	public UnityEngine.Vector2 top2_arrow_pos;//指示箭头pos
	public UnityEngine.Quaternion top2_arrow_rotation;//指示箭头rotation

	public uint top3_id = 0;//当前第三名
	public bool isTop3ShowArrow = false;
	public UnityEngine.Vector2 top3_arrow_pos;//指示箭头pos
	public UnityEngine.Quaternion top3_arrow_rotation;//指示箭头rotation
}
}