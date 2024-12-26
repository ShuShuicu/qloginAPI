DROP TABLE IF EXISTS `ucenter_apps`;
CREATE TABLE `ucenter_apps` (
`appid` int(11) NOT NULL AUTO_INCREMENT,
`uid` int(11) NOT NULL DEFAULT '0',
`appkey` varchar(32) NOT NULL,
`name` varchar(64) DEFAULT NULL,
`url` varchar(64) DEFAULT NULL,
`url2` varchar(64) DEFAULT NULL,
`addtime` datetime DEFAULT NULL,
`type` int(1) NOT NULL DEFAULT 0,
`limit` int(1) NOT NULL DEFAULT 0,
`status` int(1) NOT NULL DEFAULT 0,
`note` text DEFAULT NULL,
 PRIMARY KEY (`appid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=1000;

DROP TABLE IF EXISTS `ucenter_accounts`;
CREATE TABLE `ucenter_accounts` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`uid` int(11) NOT NULL DEFAULT '0',
`appid` int(11) NOT NULL,
`type` varchar(10) NOT NULL,
`openid` varchar(100) DEFAULT NULL,
`token` varchar(100) DEFAULT NULL,
`nickname` varchar(150) DEFAULT NULL,
`faceimg` varchar(150) DEFAULT NULL,
`location` varchar(150) DEFAULT NULL,
`gender` varchar(10) DEFAULT NULL,
`ip` varchar(20) DEFAULT NULL,
`addtime` datetime DEFAULT NULL,
`lasttime` datetime DEFAULT NULL,
`status` int(1) NOT NULL DEFAULT 0,
 PRIMARY KEY (`id`),
 KEY appid (`appid`),
 KEY account (`appid`,`type`,`openid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=1000;

DROP TABLE IF EXISTS `ucenter_logs`;
CREATE TABLE `ucenter_logs` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`code` char(32) NOT NULL,
`appid` int(11) NOT NULL,
`uid` int(11) NOT NULL DEFAULT '0',
`type` varchar(10) NOT NULL,
`openid` varchar(100) DEFAULT NULL,
`domain` varchar(100) DEFAULT NULL,
`redirect` text DEFAULT NULL,
`state` varchar(100) DEFAULT NULL,
`ucode` varchar(100) DEFAULT NULL,
`ip` varchar(20) DEFAULT NULL,
`addtime` datetime DEFAULT NULL,
`endtime` datetime DEFAULT NULL,
`status` int(1) NOT NULL DEFAULT 0,
 PRIMARY KEY (`id`),
 KEY appid (`appid`),
 KEY account (`appid`,`type`,`openid`),
 KEY code (`appid`,`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
