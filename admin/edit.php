<?php
include("../includes/common.php");
$title='编辑应用';
include './head.php';
if($islogin==1){}else exit("<script language='javascript'>window.location.href='./login.php';</script>");
$my=isset($_GET['my'])?$_GET['my']:null;

if($my=='add'){
?>
<form action="./edit.php?my=add_submit" method="POST">
<div class="mdui-card-primary-title">
	{{ subTitle }}
</div>
<div class="mdui-divider"></div>
<div class="mdui-card-content">
	<div class="mdui-textfield">
		<i class="mdui-icon material-icons">apps</i>
		<input type="text" class="mdui-textfield-input" name="name" placeholder="应用名称">
	</div>
	<div class="mdui-textfield">
		<i class="mdui-icon material-icons">fiber_manual_record</i>
		<input type="text" class="mdui-textfield-input" name="url" placeholder="应用域名">
	</div>
	<div class="mdui-textfield">
		<i class="mdui-icon material-icons">fiber_smart_record</i>
		<input type="text" class="mdui-textfield-input" name="url2" placeholder="备用域名/可留空">
	</div>
	<select name="type" class="mdui-select">
        <option value="1" selected>获取头像</option>
        <option value="0">不获取</option>
    </select>
	<select name="limit" class="mdui-select">
		<option value="0" selected>不限制域名</option>
        <option value="1">限制域名</option>
    </select>
	<select name="status" class="mdui-select">
		<option value="1" selected>启用登录</option>
        <option value="2">关闭登录</option>
    </select>
</div>
<div class="mdui-float-right mdui-m-b-2">
    <a :href="url.Admin + '/list.php'" class="mdui-btn mdui-btn-raised mdui-ripple mdui-color-theme">返回列表</a>
    <button type="submit" class="mdui-btn mdui-btn-raised mdui-ripple mdui-color-theme-accent">确定添加</button>
  </div>
</form>
<?php
}
elseif($my=='add_submit')
{
$name=trim($_POST['name']);
$url=trim($_POST['url']);
$url2=trim($_POST['url2']);
$type=intval($_POST['type']);
$limit=intval($_POST['limit']);
$status=intval($_POST['status']);
if($name==NULL or $url==NULL){
showmsg('必填项不能为空！',3);
} else {
$rows=$DB->getRow("select * from ucenter_apps where name='$name' limit 1");
if($rows)
	showmsg('应用名称已存在！',3);
$appkey = md5(mt_rand(0,999).time());
$sql="insert into `ucenter_apps` (`appkey`,`name`,`url`,`url2`,`addtime`,`type`,`limit`,`status`) values (:appkey, :name, :url, :url2, NOW(), :type, :limit, :status)";
if($DB->exec($sql, ['appkey'=>$appkey, 'name'=>$name, 'url'=>$url, 'url2'=>$url2, 'type'=>$type, 'limit'=>$limit, 'status'=>$status])!==false){
	$appid=$DB->lastInsertId();
	showmsg('添加应用成功！<br/>APPID：'.$appid.'<br/>APPKEY：'.$appkey.'<br/><br/><a href="./list.php">>>返回应用列表</a>',1);
}else
	showmsg('添加应用失败！'.$DB->error(),4);
}
}
elseif($my=='edit'){
$appid=intval($_GET['appid']);
$row=$DB->getRow("select * from ucenter_apps where appid='$appid' limit 1");
if(!$row)showmsg('记录不存在',3);
echo '<div class="panel panel-primary">
<div class="panel-heading"><h3 class="panel-title">修改记录</h3></div>';
echo '<div class="panel-body">';
echo '<form action="./edit.php?my=edit_submit&appid='.$appid.'" method="POST">
<div class="form-group">
<label>应用名称:</label><br>
<input type="text" class="form-control" name="name" value="'.$row['name'].'" required>
</div>
<div class="form-group">
<label>应用域名:</label><br>
<input type="text" class="form-control" name="url" value="'.$row['url'].'" required>
</div>
<div class="form-group">
<label>备用域名:</label><br>
<input type="text" class="form-control" name="url2" value="'.$row['url2'].'">
</div>
<div class="form-group">
<label>是否获取昵称头像:</label><br>
<select class="form-control" name="type" default="'.$row['type'].'"><option value="1">是</option><option value="0">否</option></select>
</div>
<div class="form-group">
<label>是否限制域名:</label><br>
<select class="form-control" name="limit" default="'.$row['limit'].'"><option value="0">否</option><option value="1">是</option></select>
</div>
<div class="form-group">
<label>是否启用:</label><br>
<select class="form-control" name="status" default="'.$row['status'].'"><option value="1">开启</option><option value="0">关闭</option></select>
</div>
<input type="submit" class="btn btn-primary btn-block" value="确定添加"></form>';
echo '<br/><a href="./list.php">>>返回应用列表</a>';
echo '</div></div>';
}
elseif($my=='edit_submit')
{
$appid=intval($_GET['appid']);
$row=$DB->getRow("select * from ucenter_apps where appid='$appid' limit 1");
if(!$row)showmsg('记录不存在',3);
$name=trim($_POST['name']);
$url=trim($_POST['url']);
$url2=trim($_POST['url2']);
$type=intval($_POST['type']);
$limit=intval($_POST['limit']);
$status=intval($_POST['status']);
if($name==NULL or $url==NULL){
showmsg('必填项不能为空！',3);
} else {
$sql="UPDATE `ucenter_apps` SET `name`=:name,`url`=:url,`url2`=:url2,`type`=:type,`limit`=:limit,`status`=:status WHERE appid=:appid";
if($DB->exec($sql, ['appid'=>$appid, 'name'=>$name, 'url'=>$url, 'url2'=>$url2, 'type'=>$type, 'limit'=>$limit, 'status'=>$status])!==false){
	showmsg('修改应用成功！<br/><br/><a href="./list.php">>>返回应用列表</a>',1);
}else
	showmsg('修改应用失败！'.$DB->error(),4);
}
}
elseif($my=='reset'){
$appid=intval($_GET['appid']);
$appkey = md5(mt_rand(0,999).time());
$sql=$DB->exec("UPDATE ucenter_apps SET appkey='$appkey' WHERE appid='$appid'");
exit("<script language='javascript'>alert('APPKEY重置成功');history.go(-1);</script>");
}
elseif($my=='del'){
$appid=intval($_GET['appid']);
$sql=$DB->exec("DELETE FROM ucenter_apps WHERE appid='$appid'");
if($sql){$res='删除成功！';}
else{$res='删除失败！';}
exit("<script language='javascript'>alert('{$res}');history.go(-1);</script>");
}
elseif($my=='del_account'){
$id=intval($_GET['id']);
$sql=$DB->exec("DELETE FROM ucenter_accounts WHERE id='$id'");
if($sql){$res='删除成功！';}
else{$res='删除失败！';}
exit("<script language='javascript'>alert('{$res}');history.go(-1);</script>");
}
?>
    </div>
  </div>
<script>
$(document).ready(function(){
	var items = $("select[default]");
	for (i = 0; i < items.length; i++) {
		$(items[i]).val($(items[i]).attr("default")||0);
	}
})
</script>
<?php require_once './foot.php'; ?>