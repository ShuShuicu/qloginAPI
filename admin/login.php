<?php
/**
 * 登录
**/
include("../includes/common.php");
if(isset($_POST['user']) && isset($_POST['pass'])){
	$user=daddslashes($_POST['user']);
	$pass=daddslashes($_POST['pass']);
	if($user==$conf['admin_user'] && $pass==$conf['admin_pwd']) {
		$session=md5($user.$pass.$password_hash);
		$token=authcode("{$user}\t{$session}", 'ENCODE', SYS_KEY);
		setcookie("admin_token", $token, time() + 604800, null, null, null, true);
		@header('Content-Type: text/html; charset=UTF-8');
		exit("<script language='javascript'>alert('登陆后台管理成功！');window.location.href='./';</script>");
	}elseif ($pass != $conf['admin_pwd']) {
		@header('Content-Type: text/html; charset=UTF-8');
		exit("<script language='javascript'>alert('用户名或密码不正确！');history.go(-1);</script>");
	}
}elseif(isset($_GET['logout'])){
	setcookie("admin_token", "", time() - 604800);
	@header('Content-Type: text/html; charset=UTF-8');
	exit("<script language='javascript'>alert('您已成功注销本次登陆！');window.location.href='./login.php';</script>");
}elseif($islogin==1){
	exit("<script language='javascript'>alert('您已登陆！');window.location.href='./';</script>");
}
$title='用户登录';
include './head.php';
?>
        <form action="./login.php" method="post" class="form-horizontal" role="form">
            <div class="mdui-card-content">
                <div class="mdui-textfield mdui-col-xs-6">
                    <i class="mdui-icon material-icons">account_circle</i>
                    <input type="text" name="user" value="" class="mdui-textfield-input" placeholder="用户名" required="required">
                </div>
                <div class="mdui-textfield mdui-col-xs-6">
                    <i class="mdui-icon material-icons">lock</i>
                    <input type="password" name="pass" value="" class="mdui-textfield-input" placeholder="密码" required="required">
                </div>
            </div>
            <div class="mdui-float-right mdui-m-b-2">
                <button type="submit" class="mdui-btn mdui-ripple mdui-color-theme-accent">登录</button>
            </div>
        </form>
<?php require_once './foot.php'; ?>