<?php
include("../includes/common.php");
$title='系统设置';
include './head.php';
if($islogin==1){}else exit("<script language='javascript'>window.location.href='./login.php';</script>");
$scriptpath=str_replace('\\','/',$_SERVER['SCRIPT_NAME']);
$sitepath = substr($scriptpath, 0, strrpos($scriptpath, '/'));
$admin_path = substr($sitepath, strrpos($sitepath, '/'));
$siteurl = (is_https() ? 'https://' : 'http://').$_SERVER['HTTP_HOST'].str_replace($admin_path,'',$sitepath).'/';

$mod=isset($_GET['mod'])?$_GET['mod']:'site';
if($mod=='site_n' && $_POST['do']){
	$admin_user=trim($_POST['admin_user']);
	$admin_pwd=trim($_POST['admin_pwd']);
	$qq_appid=trim($_POST['qq_appid']);
	$qq_appkey=trim($_POST['qq_appkey']);
	if(!$admin_user)showmsg('管理员用户名不能为空',3);
	if(!empty($admin_pwd))$conf['admin_pwd'] = $admin_pwd;
	$conf['admin_user'] = $admin_user;
	$conf['qq_appid'] = $qq_appid;
	$conf['qq_appkey'] = $qq_appkey;

	$data = '<?php'."\r\n".'return ['."\r\n";
	foreach($conf as $key=>$value){
		$data .= '"'.$key.'" => "'.$value.'",'."\r\n";
	}
	$data .= '];';
	if(file_put_contents(SYSTEM_ROOT.'siteconfig.php', $data)){
		showmsg('修改成功！',1);
	}else{
		showmsg('保存失败，请确保有本地写入权限',3);
	}
}elseif($mod=='site'){
?>

<form action="./set.php?mod=site_n" method="post" class="form-horizontal" role="form"><input type="hidden" name="do" value="1" />
    <div class="mdui-card-content">
        <div class="mdui-textfield">
            <i class="mdui-icon material-icons">verified_user</i>
            <label class="mdui-textfield-label">QQ快捷登录Appid</label>
            <input type="text" name="qq_appid" value="<?php echo $conf['qq_appid']; ?>" class="mdui-textfield-input" placeholder="114514" />
        </div>
        <div class="mdui-textfield">
            <i class="mdui-icon material-icons">security</i>
            <label class="mdui-textfield-label">QQ快捷登录AppKey</label>
            <input type="text" name="qq_appkey" value="<?php echo $conf['qq_appkey']; ?>" class="mdui-textfield-input" placeholder="1919810" />
        </div>
        <div class="mdui-textfield mdui-col-xs-6">
            <i class="mdui-icon material-icons">account_circle</i>
            <label class="mdui-textfield-label">用户名</label>
            <input type="text" name="admin_user" value="<?php echo $conf['admin_user']; ?>" class="mdui-textfield-input" required />
        </div>
        <div class="mdui-textfield mdui-col-xs-6">
            <i class="mdui-icon material-icons">lock</i>
            <label class="mdui-textfield-label">密码</label>
            <input type="text" name="admin_pwd" value="" class="mdui-textfield-input" placeholder="不重置管理员密码请留空" />
        </div>
    </div>
    <div class="mdui-float-right mdui-m-b-2">
        <button type="submit" name="submit" class="mdui-btn mdui-ripple mdui-color-red">保存</button>
    </div>
	<table class="mdui-table mdui-table-hoverable mdui-typo">
    <thead>
      <tr>
        <th>#</th>
        <th>Name</th>
        <th>回调地址</th>
        <th>申请地址</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td>1</td>
        <td>QQ互联</td>
        <td><?php echo $siteurl.'return.php';?></td>
        <td><a href="https://connect.qq.com/" target="_blank" rel="noreferrer">https://connect.qq.com/</a></td>
      </tr>
  </table>
</form>

<?php
}
?>
</div>
</div>
<?php require_once './foot.php'; ?>