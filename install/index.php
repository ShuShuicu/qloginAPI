<?php
//程序安装文件
error_reporting(0);
$databaseFile = '../includes/config.php';//数据库配置文件
$siteFile = '../includes/siteconfig.php';//网站配置文件
require_once '../includes/Tomori.php';
@header('Content-Type: text/html; charset=UTF-8');
$step=isset($_GET['step'])?$_GET['step']:1;
$action=isset($_POST['action'])?$_POST['action']:null;
if(file_exists('install.lock')){
    exit('你已经成功安装，如需重新安装，请手动删除install目录下install.lock文件！');
}


function random($length, $numeric = 0) {
	$seed = base_convert(md5(microtime().$_SERVER['DOCUMENT_ROOT']), 16, $numeric ? 10 : 35);
	$seed = $numeric ? (str_replace('0', '', $seed).'012340567890') : ($seed.'zZ'.strtoupper($seed));
	$hash = '';
	$max = strlen($seed) - 1;
	for($i = 0; $i < $length; $i++) {
		$hash .= $seed[mt_rand(0, $max)];
	}
	return $hash;
}

if($action=='install'){
    $db_host=isset($_POST['db_host'])?$_POST['db_host']:null;
    $db_port=isset($_POST['db_port'])?$_POST['db_port']:null;
    $db_user=isset($_POST['db_user'])?$_POST['db_user']:null;
    $db_pwd=isset($_POST['db_pwd'])?$_POST['db_pwd']:null;
    $db_name=isset($_POST['db_name'])?$_POST['db_name']:null;
    $admin_user=isset($_POST['admin_user'])?$_POST['admin_user']:null;
    $admin_pwd=isset($_POST['admin_pwd'])?$_POST['admin_pwd']:null;
    if(empty($db_host) || empty($db_port) || empty($db_user) || empty($db_pwd) || empty($db_name)){
        $errorMsg='请填完所有数据库信息';
    }elseif(empty($admin_user) || empty($admin_pwd)){
        $errorMsg='请填写管理员信息';
    }else{
        try{
            $db=new PDO("mysql:host=".$db_host.";dbname=".$db_name.";port=".$db_port,$db_user,$db_pwd);
        }catch(Exception $e){
            $errorMsg='链接数据库失败:'.$e->getMessage();
        }
        if(empty($errorMsg)){
            @file_put_contents($databaseFile,'<?php
return [
	"host" => "'.$db_host.'", //数据库服务器
	"port" => '.$db_port.', //数据库端口
	"user" => "'.$db_user.'", //数据库用户名
	"pwd" => "'.$db_pwd.'", //数据库密码
	"dbname" => "'.$db_name.'" //数据库名
];');
            @file_put_contents($siteFile,'<?php
return [
	"syskey" => "'.random(12).'",
    "admin_user" => "'.$admin_user.'",
    "admin_pwd" => "'.$admin_pwd.'",
];');
			date_default_timezone_set("PRC");
			$date = date("Y-m-d");
            $db->exec("set names utf8");
            $sqls=file_get_contents('install.sql');
            $sqls=explode(';', $sqls);
            $success=0;$error=0;$errorMsg=null;
            foreach ($sqls as $value) {
                $value=trim($value);
                if(!empty($value)){
                    if($db->exec($value)===false){
                        $error++;
                        $dberror=$db->errorInfo();
                        $errorMsg.=$dberror[2]."<br>";
                    }else{
                        $success++;
                    }
                }
            }
            $step=3;
			@file_put_contents("install.lock",'安装锁');
        }
    }
}
?>
<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" name="viewport">
    <meta content="yes" name="apple-mobile-web-app-capable">
    <meta content="black" name="apple-mobile-web-app-status-bar-style">
    <title>QQ登录中转API-安装程序</title>
    <link href="//ss.bscstorage.com/wpteam/assets/themes/Bocchi/style.css?ver=<?php Tomori::GetVer(); ?>" rel="stylesheet">
    <link href="//ss.bscstorage.com/wpteam/static/mdui@1/css/mdui.min.css?ver=<?php Tomori::GetVer(); ?>" rel="stylesheet">
    <style>
        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            z-index: -520;
            pointer-events: none;
        }

        body::before {
            background: linear-gradient(90deg, rgba(247, 149, 51, .1), rgba(243, 112, 85, .1) 15%, rgba(239, 78, 123, .1) 30%, rgba(161, 102, 171, .1) 44%, rgba(80, 115, 184, .1) 58%, rgba(16, 152, 173, .1) 72%, rgba(7, 179, 155, .1) 86%, rgba(109, 186, 130, .1));
        }

        .mdui-btn {
            border-radius: 8px;
        }

        .mdui-card {
            background-color: #ffffffb0;
        }
    </style>
</head>

<body class="mdui-theme-auto mdui-theme-primary-blue-grey mdui-theme-accent-red mdui-loaded mdui-theme-layout-light">
    <div id="app" class="mdui-appbar-with-toolbar mdui-container">
        <div class="mdui-card">
            <div class="mdui-card-primary">
                <div class="mdui-card-primary-title">{{ title }}</div>
                <div class="mdui-divider"></div>
                <div class="mdui-card-actions mdui-card-primary-subtitle">
                    {{ install.description }}
                </div>
                <div class="mdui-divider"></div>
                <div class="mdui-card-content">
                    <?php
                if(isset($errorMsg)){
                    echo '<div class="alert alert-danger text-center" role="alert">'.$errorMsg.'</div>';
                }
                if($step==2){
                ?>
                    <div class="mdui-card-content">
                        <form action="#" method="post">
                            <div class="mdui-textfield">
                                <i class="mdui-icon material-icons">account_circle</i>
                                <label class="mdui-textfield-label">管理员用户名</label>
                                <input type="text" name="admin_user" class="mdui-textfield-input" value="Tomori">
                            </div>
                            <div class="mdui-textfield">
                                <i class="mdui-icon material-icons">lock</i>
                                <label class="mdui-textfield-label">管理员密码</label>
                                <input type="text" name="admin_pwd" class="mdui-textfield-input" placeholder="114514">
                            </div>

                            <input type="hidden" name="action" value="install">
                            <div class="mdui-textfield mdui-col-xs-6">
                                <label class="mdui-textfield-label">数据库地址</label>
                                <input type="text" name="db_host" class="mdui-textfield-input" value="localhost">
                            </div>
                            <div class="mdui-textfield mdui-col-xs-6">
                                <label class="mdui-textfield-label">数据库端口</label>
                                <input type="text" name="db_port" class="mdui-textfield-input" value="3306">
                            </div>
                            <div class="mdui-textfield mdui-col-xs-4">
                                <label class="mdui-textfield-label">数据库名称</label>
                                <input type="text" name="db_name" class="mdui-textfield-input" placeholder="root">
                            </div>
                            <div class="mdui-textfield mdui-col-xs-4">
                                <label class="mdui-textfield-label">数据库用户名</label>
                                <input type="text" name="db_user" class="mdui-textfield-input" placeholder="root">
                            </div>
                            <div class="mdui-textfield mdui-col-xs-4">
                                <label class="mdui-textfield-label">数据库密码</label>
                                <input type="text" name="db_pwd" class="mdui-textfield-input" placeholder="password">
                            </div>
                            <div>
                                <div class="mdui-float-right mdui-m-b-2">
                                    <a :href="install.url + '?step=1'" class="mdui-btn mdui-ripple mdui-color-theme">上一步</a>
                                    <button type="submit" class="mdui-btn mdui-ripple mdui-color-theme-accent">下一步</button>
                                </div>
                            </div>

                        </form>
                    </div>

                    <?php }elseif($step==3){ ?>
                    <div>数据导入完毕</div>
                    <ul class="mdui-typo">
                        <li class="list-group-item">成功执行SQL语句<?php echo $success;?>条，失败<?php echo $error;?>条！</li>
                        <li class="list-group-item">系统已成功安装完毕！</li>
                        <li class="list-group-item">后台地址：<a href="../admin/" target="_blank">/admin/</a></li>
                        <li class="list-group-item">管理员账号：<?php echo $admin_user?> 密码：<?php echo $admin_pwd?></li>
                        <a href="../admin/" class="btn list-group-item">进入网站后台</a>
                    </ul>
                </div>
                <?php }else{ ?>
                <?php
                    $install=true;
                    if(!file_exists('./install.lock')){
                        $check[2]='未锁定';
                    }else{
                        $check[2]='已锁定';
                        $install=false;
                    }
                    if(class_exists("PDO")){
                        $check[0]='支持';
                    }else{
                        $check[0]='不支持';
                        $install=false;
                    }
                    if($fp = @fopen("../test.txt", 'w')) {
                        @fclose($fp);
                        @unlink("../test.txt");
                        $check[1]='支持';
                    }else{
                        $check[1]='不支持';
                        $install=false;
                    }
                    if(version_compare(PHP_VERSION,'5.4.0','<')){
                        $check[3]='不支持';
                    }else{
                        $check[3]='支持';
                    }

                    ?>
                <ul>
                    <li>检测安装是否锁定 <?php echo $check[2];?></li>
                    <li>PDO_MYSQL组件 <?php echo $check[0];?></li>
                    <li>主目录写入权限 <?php echo $check[1];?></li>
                    <li>PHP版本>=5.4 <?php echo $check[3];?></li>
                    <li>成功安装后安装文件就会锁定，如需重新安装，请手动删除install目录下install.lock配置文件！</li>
                </ul>
            </div>


            <div class="mdui-float-right mdui-m-b-2">
                <a href="https://github.com/ShuShuicu" class="mdui-btn mdui-ripple mdui-color-theme">GitHub</a>
                <?php
                if($install) 
                ?>
                <a :href="install.url + '?step=2'"><button class="mdui-btn mdui-ripple mdui-color-theme-accent">下一步</button></a>
            </div>


        </div>
        <?php } ?>
    </div>
    </div>
    <script src="//ss.bscstorage.com/wpteam/static/vue@2/vue.min.js?ver=<?php Tomori::GetVer(); ?>"></script>
    <script src="//ss.bscstorage.com/wpteam/static/jquery@3/jquery.min.js?ver=<?php Tomori::GetVer(); ?>"></script>
    <script src="//ss.bscstorage.com/wpteam/static/jquery-pjax@2/jquery.pjax.min.js?ver=<?php Tomori::GetVer(); ?>"></script>
    <script src="//ss.bscstorage.com/wpteam/static/mdui@1/js/mdui.min.js?ver=<?php Tomori::GetVer(); ?>"></script>
    <script>
        let vueInstance = null;

        function initVue() {
            if (vueInstance) {
                vueInstance.$destroy();
            }
            vueInstance = new Vue({
                el: '#app',
                data: {
                    title: 'QQ登录中转API',
                    install: {
                        title: '安装环境检测',
                        description: '本程序二开自彩虹聚合登录中转API，永久免费禁止倒卖！',
                        url: '<?php Tomori::GetInstallUrl(); ?>',
                    },
                }
            });
        }

        $(document).pjax('a[href^="' + window.location.origin + '"]:not(a[target="_blank"], a[no-pjax])', {
            container: '#app',
            fragment: '#app',
            timeout: 8000
        });

        $(document).on('pjax:end', function() {
            initVue();
            mdui.mutation();
        });

        // 初始化页面时也调用一次
        initVue();
        mdui.mutation();
    </script>
</body>

</html>