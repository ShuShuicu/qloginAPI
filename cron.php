<?php
//日常维护文件

include './includes/common.php';

$thtime=date("Y-m-d H:i:s",time()-3600);
$thtime2=date("Y-m-d H:i:s",time()-3600*24*7);
$DB->exec("delete from ucenter_logs where status=0 and addtime<'{$thtime}'");
$DB->exec("delete from ucenter_logs where status=1 and addtime<'{$thtime2}'");
$DB->exec("OPTIMIZE TABLE `ucenter_logs`");

echo 'ok';