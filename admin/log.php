<?php
include("../includes/common.php");
$title='登录记录';
include './head.php';
if($islogin==1){}else exit("<script language='javascript'>window.location.href='./login.php';</script>");
?>
        <?php
$my=isset($_GET['my'])?$_GET['my']:null;

if($my=='search' && !empty($_GET['appid'])) {
	$appid = intval($_GET['appid']);
	$sql=" status=1 AND `appid`='$appid'";
	if(!empty($_GET['type']) && $_GET['type']!='0'){
		$type = $_GET['type'];
		$sql.=" AND `type`='$type'";
		if(!empty($_GET['openid'])){
			$openid = trim($_GET['openid']);
			$sql.=" AND `openid`='$openid'";
		}
	}
	$numrows=$DB->getColumn("SELECT count(*) from ucenter_logs WHERE{$sql}");
	$con='APPID:'.$appid.' 共有 <b>'.$numrows.'</b> 条记录';
	$link="&appid=".$appid;
	if(!empty($_GET['type']) && $_GET['type']!='0'){
		$link.="&type=".$type;
		if(!empty($_GET['openid'])){
			$con='APPID:'.$appid.' openid:'.$openid.' 共有 <b>'.$numrows.'</b> 条记录';
			$link.="&openid=".$openid;
		}
	}
}else{
	$numrows=$DB->getColumn("SELECT count(*) from ucenter_logs WHERE status=1");
	$sql=" status=1";
	$con='系统共有 <b>'.$numrows.'</b> 条记录';
}
?>
<form action="log.php" method="GET" class="form-inline"><input type="hidden" name="my" value="search">
<div class="mdui-card-primary-title">
<?php
echo $con;
?>
</div>
<div class="mdui-divider"></div>
<div class="mdui-card-content">
	<div class="mdui-textfield">
		<i class="mdui-icon material-icons">apps</i>
		<input type="text" class="mdui-textfield-input" name="appid" placeholder="APPID" value="<?php echo $_GET['appid'] ?>">
	</div>
	<div class="mdui-textfield">
    <i class="mdui-icon material-icons">account_circle</i>
      <input type="text" class="mdui-textfield-input" name="openid" placeholder="第三方账号UID" value="<?php echo $_GET['openid'] ?>">
  </div>
  <select name="type" class="mdui-select" default="<?php echo $_GET['type'] ?>">
    <option value="0" selected>所有登录方式</option>
    <option value="qq">QQ</option>
  </select>
  <div class="mdui-float-right mdui-m-b-2">
    <a :href="url.Admin + '/log.php'" class="mdui-btn mdui-btn-raised mdui-ripple mdui-color-theme">重置</a>
    <button type="submit" class="mdui-btn mdui-btn-raised mdui-ripple mdui-color-theme-accent">搜索</button>
  </div>
</form>
		<div class="mdui-table-fluid">
			<table class="mdui-table mdui-table-hoverable">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>APPID</th>
                        <th>方式</th>
                        <th>第三方账号UID</th>
                        <th>登录IP</th>
                        <th>登录时间</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
$pagesize=30;
$pages=ceil($numrows/$pagesize);
$page=isset($_GET['page'])?intval($_GET['page']):1;
$offset=$pagesize*($page - 1);

$rs=$DB->query("SELECT * FROM ucenter_logs WHERE{$sql} order by id desc limit $offset,$pagesize");
while($res = $rs->fetch())
{
echo '<tr><td><b>'.$res['id'].'</b></td><td><a href="./list.php?my=search&column=appid&value='.$res['appid'].'" target="_blank">'.$res['appid'].'</a></td><td>'.$res['type'].'</td><td>'.$res['openid'].'</td><td><a href="https://m.ip138.com/iplookup.asp?ip='.$res['ip'].'" target="_blank" rel="noreferrer">'.$res['ip'].'</a></td><td>'.$res['endtime'].'</td></tr>';
}
?>
                </tbody>
            </table>
        </div>
        <?php
echo'<ul class="pagination">';
$first=1;
$prev=$page-1;
$next=$page+1;
$last=$pages;
if ($page>1)
{
echo '<li><a href="log.php?page='.$first.$link.'">首页</a></li>';
echo '<li><a href="log.php?page='.$prev.$link.'">&laquo;</a></li>';
} else {
echo '<li class="disabled"><a>首页</a></li>';
echo '<li class="disabled"><a>&laquo;</a></li>';
}
$start=$page-10>1?$page-10:1;
$end=$page+10<$pages?$page+10:$pages;
for ($i=$start;$i<$page;$i++)
echo '<li><a href="log.php?page='.$i.$link.'">'.$i .'</a></li>';
echo '<li class="disabled"><a>'.$page.'</a></li>';
for ($i=$page+1;$i<=$end;$i++)
echo '<li><a href="log.php?page='.$i.$link.'">'.$i .'</a></li>';
echo '';
if ($page<$pages)
{
echo '<li><a href="log.php?page='.$next.$link.'">&raquo;</a></li>';
echo '<li><a href="log.php?page='.$last.$link.'">尾页</a></li>';
} else {
echo '<li class="disabled"><a>&raquo;</a></li>';
echo '<li class="disabled"><a>尾页</a></li>';
}
echo'</ul>';
#分页
?>
    </div>
</div>
<script>
    var items = $("select[default]");
    for (i = 0; i < items.length; i++) {
        $(items[i]).val($(items[i]).attr("default") || 0);
    }
</script>
<?php require_once './foot.php'; ?>