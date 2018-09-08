<?php

namespace SDK\Lib;

class MsgRouteId
{
	const eMRIDSocketOpened = 0;      // socket Opened
	const eMRIDSocketClosed = 1;// socket Closed
	const eMRIDLoadedWebRes = 2;// web 资源加载完成
	const eMRIDThreadLog = 3;// 线程打日志

	const eMRID_NewItem = 4;
	const eMRID_JoinTeam = 5;
	const eMRID_MoveTeam = 6;
	const eMRID_NewTrangle = 7;

	const eMRID_RemoveTrangle = 8;
	const eMRID_MoveTeamBullet = 9;
	const eMRID_TeamShooteMRID_NewItem = 10;
	const eMRID_TeamShoot = 11;
	const eMRID_BulletTimeOut = 12;
	const eMRID_HitTrangleCommand = 13;
	const eMRID_HitItemCommand = 14;
	const eMRID_TrangleInDangerZone = 15;
	const eMRID_TrangleOutDangerZone = 16;
	const eMRID_TrangleTeamLeave = 17;
	const eMRID_BatchAddEnergyPlane = 18;
	const eMRID_BatchRemovePlane = 19;
	const eMRID_NewTeamShoot = 20;
}

?>