<?php
@header('Content-Type: text/html; charset=UTF-8');
?>
<!DOCTYPE html>
<html lang="zh-cn">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title><?php echo $title ?></title>
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

<body class="<?php if($islogin==1){ ?>mdui-drawer-body-left<?php } ?> mdui-theme-auto mdui-theme-primary-blue-grey mdui-theme-accent-red mdui-loaded mdui-theme-layout-light">
    <div id="app">
        <?php if($islogin==1){?>
        <header>
            <div class="mdui-appbar mdui-appbar-scroll-hide mdui-headroom">
                <div class="mdui-toolbar mdui-card mdui-shadow-0" style="border-radius: 0;">
                    <a href="javascript:;" class="mdui-btn mdui-btn-icon" mdui-drawer="{target: '#drawer'}">
                        <i class="mdui-icon material-icons">menu</i>
                    </a>
                    <a href="javascript:;" class="mdui-typo-headline mdui-hidden-xs">{{ title }}</a>
                    <a href="javascript:;" class="mdui-typo-title mdui-col-xs-6"><?php echo $title; ?></a>
                    <div class="mdui-toolbar-spacer"></div>
                    <a :href="url.Github" target="_blank" class="mdui-btn mdui-btn-icon">
                        <i class="mdui-icon material-icons">code</i>
                    </a>
                </div>
            </div>
        </header>
        <div id="Sidebar">
            <div id="drawer" class="mdui-drawer mdui-card" style="border-radius: 0px;">
                <img src="//bang-dream.bushimo.jp/mygo/assets/images/common/logo_mygo.png" class="mdui-img-fluid">
                <div class="mdui-divider"></div>
                <ul class="mdui-list">
                    <a :href="url.Admin">
                        <li class="mdui-list-item mdui-ripple">
                            <i class="mdui-list-item-icon mdui-icon material-icons">home</i>
                            <div class="mdui-list-item-content">首页</div>
                        </li>
                    </a>
                    <a :href="url.Admin + '/account.php'">
                        <li class="mdui-list-item mdui-ripple">
                            <i class="mdui-list-item-icon mdui-icon material-icons">account_circle</i>
                            <div class="mdui-list-item-content">账号列表</div>
                        </li>
                    </a>
                    <a :href="url.Admin + '/list.php'">
                        <li class="mdui-list-item mdui-ripple">
                            <i class="mdui-list-item-icon mdui-icon material-icons">label</i>
                            <div class="mdui-list-item-content">应用列表</div>
                        </li>
                    </a>
                    <a :href="url.Admin + '/log.php'">
                        <li class="mdui-list-item mdui-ripple">
                            <i class="mdui-list-item-icon mdui-icon material-icons">beenhere</i>
                            <div class="mdui-list-item-content">登录记录</div>
                        </li>
                    </a>
                    <a :href="url.Admin + '/set.php'">
                        <li class="mdui-list-item mdui-ripple">
                            <i class="mdui-list-item-icon mdui-icon material-icons">settings</i>
                            <div class="mdui-list-item-content">系统设置</div>
                        </li>
                    </a>
                    <li class="mdui-subheader">@Author</li>
                    <a :href="url.Gitee" target="_blank">
                        <li class="mdui-list-item mdui-ripple"><i class="mdui-list-item-icon mdui-icon material-icons">code</i>
                            <div class="mdui-list-item-content">Gitee</div>
                        </li>
                    </a>
                    <a :href="url.Github" target="_blank">
                        <li class="mdui-list-item mdui-ripple"><i class="mdui-list-item-icon mdui-icon material-icons">code</i>
                            <div class="mdui-list-item-content">GitHub</div>
                        </li>
                    </a>
                    <a :href="url.Bilibili" target="_blank">
                        <li class="mdui-list-item mdui-ripple"><i class="mdui-list-item-icon mdui-icon material-icons">videocam</i>
                            <div class="mdui-list-item-content">哔哩哔哩</div>
                        </li>
                    </a>
                    <li class="mdui-subheader">特别感谢彩虹!!!</li>
                    <a :href="url.Admin + '/login.php?logout'">
                        <li class="mdui-list-item mdui-ripple">
                            <i class="mdui-list-item-icon mdui-icon material-icons">person_outline</i>
                            <div class="mdui-list-item-content">退出登录</div>
                        </li>
                    </a>
                </ul>
            </div>
        </div>
        <?php }?>
        <div class="mdui-appbar-with-toolbar mdui-container">
            <div class="mdui-card mdui-hoverable">
                <div class="mdui-card-primary">