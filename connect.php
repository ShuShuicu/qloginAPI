<?php
include './includes/common.php';
@header("Cache-Control: no-store, no-cache, must-revalidate");
@header("Pragma: no-cache");

require SYSTEM_ROOT.'QC.class.php';

$act=isset($_GET['act'])?$_GET['act']:exit('{"code":-1,"errcode":101,"msg":"no act"}');
$appid=isset($_GET['appid'])?$_GET['appid']:exit('{"code":-1,"errcode":101,"msg":"no appid"}');
$appkey = isset($_GET['appkey'])?$_GET['appkey']:exit('{"code":-1,"errcode":101,"msg":"no appkey"}');
$type=isset($_GET['type'])?$_GET['type']:'qq';

$approw = $DB->getRow("select * from ucenter_apps where appid='$appid' limit 1");
if(!$approw)exit('{"code":-1,"errcode":102,"msg":"应用appid不存在"}');
if($approw['status']==0)exit('{"code":-1,"errcode":102,"msg":"应用已关闭"}');
if($appkey!=$approw['appkey'])exit('{"code":-1,"errcode":103,"msg":"appkey不正确"}');

if($act=='login')
{
	$redirect_uri=isset($_GET['redirect_uri'])?$_GET['redirect_uri']:exit('{"code":-1,"errcode":101,"msg":"no redirect_uri"}');
	$referer = parse_url($redirect_uri);
	$rehost=$referer['host'];
	if($approw['limit']==1 && $rehost!=$approw['url'] && $rehost!=$approw['url2'])exit('{"code":-1,"errcode":103,"msg":"回调域名未授权"}');
	$code = strtoupper(md5(uniqid(rand(), TRUE)));
	$sds=$DB->exec("INSERT INTO `ucenter_logs` (`code`, `appid`, `uid`, `type`, `domain`, `redirect`, `state`, `addtime`, `status`) VALUES (:code, :appid, :uid, :type, :domain, :redirect, :state, NOW(), 0)", [':code'=>$code, ':appid'=>$appid, ':uid'=>$approw['uid'], ':type'=>$type, ':domain'=>$rehost, ':redirect'=>$redirect_uri, ':state'=>$_GET['state']]);
	if($sds){
		$logid = $DB->lastInsertId();
		$state = authcode($type.'||||'.$logid, 'ENCODE', SYS_KEY);
		if($type == 'qq'){
			$QC=new QC($conf['qq_appid'], $conf['qq_appkey']);
			$login_url = $QC->qq_login($state);
			$result = ['code'=>0, 'msg'=>'succ', 'type'=>$type, 'url'=>$login_url];
			exit(json_encode($result));
		}else{
			exit('{"code":-1,"errcode":104,"msg":"未知登录类型(type)"}');
		}
	}else{
		exit('{"code":-1,"errcode":201,"msg":"数据库错误'.$DB->error().'"}');
	}
}
elseif($act=='callback')
{
	$code = isset($_GET['code'])?trim($_GET['code']):exit('{"code":-1,"errcode":101,"msg":"no code"}');
	$row = $DB->getRow("SELECT * FROM ucenter_logs WHERE appid=:appid AND code=:code ORDER BY id DESC LIMIT 1", [":code"=>$code, ":appid"=>$appid]);
	if(!$row)exit('{"code":-1,"errcode":102,"msg":"记录不存在"}');
	if($row['status']==1){
		if(strtotime($row['endtime'])<time()-60)exit('{"code":-1,"errcode":102,"msg":"CODE已失效"}');
		$account = $DB->getRow("SELECT * FROM ucenter_accounts WHERE appid=:appid AND type=:type AND openid=:openid ORDER BY id DESC LIMIT 1", [":appid"=>$appid, ":type"=>$row['type'], ":openid"=>$row['openid']]);
		$result = ['code'=>0, 'msg'=>'succ', 'type'=>$row['type'], 'access_token'=>$account['token'], 'social_uid'=>$account['openid']];
		if($approw['type']==1){
			$result['faceimg'] = $account['faceimg'];
			$result['nickname'] = $account['nickname'];
			$result['location'] = $account['location'];
			$result['gender'] = $account['gender'];
		}
		exit(json_encode($result));
	}else{
		if($row['type']=='qq'){
			$QC=new QC($conf['qq_appid'], $conf['qq_appkey']);
			$access_token=$QC->qq_callback($row['ucode']);
			$openid=$QC->get_openid($access_token);
			$result = ['code'=>0, 'msg'=>'succ', 'type'=>'qq','access_token'=>$access_token,'social_uid'=>$openid];
			if($approw['type']==1){
				$userinfo = $QC->get_userinfo($openid, $access_token);
				$result['faceimg'] = $userinfo['figureurl_qq_2']?$userinfo['figureurl_qq_2']:$userinfo['figureurl_qq_1'];
				$result['faceimg'] = str_replace('http://','https://',$result['faceimg']);
				$result['nickname'] = $userinfo['nickname'];
				$result['gender'] = $userinfo['gender'];
			}
		}else{
			exit('{"code":-1,"errcode":104,"msg":"未知登录类型(type)"}');
		}

		$account = $DB->getRow("SELECT * FROM ucenter_accounts WHERE appid=:appid AND type=:type AND openid=:openid ORDER BY id DESC LIMIT 1", [":appid"=>$appid, ":type"=>$row['type'], ":openid"=>$result['social_uid']]);
		if($account){
			$DB->exec("UPDATE `ucenter_accounts` SET `token`=:token,`nickname`=:nickname,`faceimg`=:faceimg,`location`=:location,`gender`=:gender,`ip`=:ip,`lasttime`=NOW() WHERE id=:id", [':token'=>$result['access_token'], ':nickname'=>$result['nickname'], ':faceimg'=>$result['faceimg'], ':location'=>$result['location'], ':gender'=>$result['gender'], ':ip'=>$row['ip'], ':id'=>$account['id']]);
		}else{
			$DB->exec("INSERT INTO `ucenter_accounts` (`uid`, `appid`, `type`, `openid`, `token`, `nickname` ,`faceimg` ,`location` ,`gender` ,`ip`, `addtime`, `lasttime`, `status`) VALUES (:uid, :appid, :type, :openid, :token, :nickname, :faceimg, :location, :gender, :ip, NOW(), NOW(), 1)", [':uid'=>$approw['uid'], ':appid'=>$appid, ':type'=>$row['type'], ':openid'=>$result['social_uid'], ':token'=>$result['access_token'], ':nickname'=>$result['nickname'], ':faceimg'=>$result['faceimg'], ':location'=>$result['location'], ':gender'=>$result['gender'], ':ip'=>$row['ip']]);
		}

		$DB->exec("UPDATE `ucenter_logs` SET `openid`=:openid,`endtime`=NOW(),`status`=1 WHERE id=:id", [':openid'=>$result['social_uid'], ':id'=>$row['id']]);
		exit(json_encode($result));
	}
}
elseif($act=='query')
{
	$social_uid = isset($_GET['social_uid'])?trim($_GET['social_uid']):exit('{"code":-1,"errcode":101,"msg":"social_uid不能为空"}');
	$row = $DB->getRow("SELECT * FROM ucenter_accounts WHERE appid=:appid AND type=:type AND openid=:openid ORDER BY id DESC LIMIT 1", [":appid"=>$appid, ":type"=>$type, ":openid"=>$social_uid]);
	if($row){
		$result=array("code"=>0,"msg"=>"succ","type"=>$row['type'],"social_uid"=>$row['openid'],"access_token"=>$row['token'],"nickname"=>$row['nickname'],"faceimg"=>$row['faceimg'],"location"=>$row['location'],"gender"=>$row['gender'],"ip"=>$row['ip']);
	}else{
		$result=array("code"=>-1,"msg"=>"none");
	}
	exit(json_encode($result));
}