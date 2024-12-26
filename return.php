<?php
//登录回调文件

include './includes/common.php';

if($_GET['code']){
	$code = $_GET['code'];
}elseif($_GET['auth_code']){
	$code = $_GET['auth_code'];
}else{
	exit;
}
$array = explode('||||',authcode($_GET['state'], 'DECODE', SYS_KEY));
$type = $array[0];
$logid = $array[1];
if(!$type || !$logid)exit('Error');
$row = $DB->getRow("SELECT * FROM ucenter_logs WHERE id=:id LIMIT 1", [":id"=>$logid]);
if(!$row)exit('No Logs');
if(strtotime($row['addtime'])<time()-60*30)exit('Expired');

$DB->exec("UPDATE `ucenter_logs` SET `ucode`=:ucode,`ip`=:ip WHERE id=:id", [':ucode'=>$code, ':ip'=>real_ip(), ':id'=>$logid]);

$redirect_uri = $row['redirect'].'?type='.$type.'&code='.urlencode($row['code']).'&state='.urlencode($row['state']);
header('Location: '.$redirect_uri);
exit;
