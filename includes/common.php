<?php
error_reporting(E_ERROR | E_WARNING | E_PARSE);
define('IN_CRONLITE', true);
define('VERSION', '1001');
define('SYSTEM_ROOT', dirname(__FILE__).'/');
define('ROOT', dirname(SYSTEM_ROOT).'/');

date_default_timezone_set("PRC");
$date = date("Y-m-d H:i:s");
//session_start();

$dbconfig = require SYSTEM_ROOT.'config.php';
$conf = require SYSTEM_ROOT.'siteconfig.php';
define('SYS_KEY', $conf['syskey']);
require SYSTEM_ROOT.'PdoHelper.php';

if(!$dbconfig['user']||!$dbconfig['pwd']||!$dbconfig['dbname'])//检测安装1
{
header('Content-type:text/html;charset=utf-8');
header('Location: ./install/');
exit();
}

//连接数据库
$DB = new PdoHelper($dbconfig);

if($DB->query("select * from ucenter_apps where 1")==FALSE)//检测安装2
{
header('Content-type:text/html;charset=utf-8');
header('Location: ./install/');
exit();
}

require SYSTEM_ROOT.'function.php';

$scriptpath=str_replace('\\','/',$_SERVER['SCRIPT_NAME']);
$sitepath = substr($scriptpath, 0, strrpos($scriptpath, '/'));
$siteurl = (is_https() ? 'https://' : 'http://').$_SERVER['HTTP_HOST'].$sitepath.'/';

$password_hash='!@#%!s!0';
if(isset($_COOKIE["admin_token"]))
{
	$token=authcode(daddslashes($_COOKIE['admin_token']), 'DECODE', SYS_KEY);
	list($user, $sid) = explode("\t", $token);
	$session=md5($conf['admin_user'].$conf['admin_pwd'].$password_hash);
	if($session==$sid) {
		$islogin=1;
	}
}

if (!file_exists(ROOT.'install/install.lock') && file_exists(ROOT.'install/index.php')) {
	sysmsg('<h2>检测到无 install.lock 文件</h2><ul><li><font size="4">如果您尚未安装本程序，请<a href="./install/">前往安装</a></font></li><li><font size="4">如果您已经安装本程序，请手动放置一个空的 install.lock 文件到 /install 文件夹下，<b>为了您站点安全，在您完成它之前我们不会工作。</b></font></li></ul><br/><h4>为什么必须建立 install.lock 文件？</h4>它是安装保护文件，如果检测不到它，就会认为站点还没安装，此时任何人都可以安装/重装你的网站。<br/><br/>');exit;
}

require_once 'Tomori.php';