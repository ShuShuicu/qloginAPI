<?php
include("../includes/common.php");
$title='MyGO!!!!!';
include './head.php';
if($islogin==1){}else exit("<script language='javascript'>window.location.href='./login.php';</script>");

$count1 = $DB->getColumn("SELECT count(*) from ucenter_apps");
$count2 = $DB->getColumn("SELECT count(*) from ucenter_logs where status=1");
$mysqlversion=$DB->getColumn("select VERSION()");

$scriptpath=str_replace('\\','/',$_SERVER['SCRIPT_NAME']);
$sitepath = substr($scriptpath, 0, strrpos($scriptpath, '/'));
$admin_path = substr($sitepath, strrpos($sitepath, '/'));
$siteurl = (is_https() ? 'https://' : 'http://').$_SERVER['HTTP_HOST'].str_replace($admin_path,'',$sitepath).'/';

?>
<div class="mdui-tab mdui-tab-full-width" mdui-tab>
    <a href="#tab1" class="mdui-ripple">Kon!</a>
    <a href="#tab2" class="mdui-ripple">服务器信息</a>
</div>
<div class="mdui-divider"></div>
<div class="mdui-card-content">
    <div id="tab1">
        <div class="mdui-valign">
            <div class="mdui-center">
                <div class="mdui-chip">
                    <span class="mdui-chip-icon mdui-color-blue">
                        <i class="mdui-icon material-icons">apps</i>
                    </span>
                    <span class="mdui-chip-title">应用数量 <?php echo $count1 ?></span>
                </div>
                <div class="mdui-chip">
                    <span class="mdui-chip-icon mdui-color-indigo">
                        <i class="mdui-icon material-icons">loop</i>
                    </span>
                    <span class="mdui-chip-title">登录次数<?php echo $count2 ?></span>
                </div>
            </div>
        </div>
        <div class="mdui-textfield">
            <i class="mdui-icon material-icons">insert_link</i>
            <label class="mdui-textfield-label">接口地址</label>
            <input class="mdui-textfield-input" type="text" value="<?php echo $siteurl ?>" readonly>
        </div>
    </div>
    <div id="tab2" class="mdui-typo">
        <ul>
            <li>
                程序版本：<?php Tomori::GetVer(); ?>
            </li>
            <li>
                <b>IP：</b><?php echo $_SERVER['SERVER_ADDR'] ?>丨<b>域名：</b><?php echo $_SERVER['SERVER_NAME'] ?>
            </li>
            <hr>
            <li>
                <b>PHP 版本：</b><?php echo phpversion() ?>
                <?php if(ini_get('safe_mode')) { echo '线程安全'; } else { echo '非线程安全'; } ?>
            </li>
            <li>
                <b>MySQL 版本：</b><?php echo $mysqlversion ?>
            </li>
            <li>
            https://qizs.cn/<b>服务器系统：</b><?php echo PHP_OS ?>
            </li>
            <li>
                <b>服务器软件：</b><?php echo $_SERVER['SERVER_SOFTWARE'] ?>
            </li>

            <li>
                <b>程序最大运行时间：</b><?php echo ini_get('max_execution_time') ?>s
            </li>
            <li>
                <b>POST许可：</b><?php echo ini_get('post_max_size'); ?>
            </li>
            <li>
                <b>文件上传许可：</b><?php echo ini_get('upload_max_filesize'); ?>
            </li>
        </ul>
    </div>
</div>
<?php require_once './foot.php'; ?>