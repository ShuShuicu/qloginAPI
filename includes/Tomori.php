<?php 
class Tomori {
    public static function GetSiteUrl($echo = true) {
        $url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        if ($echo) {
            echo $url;
        } else {
            return $url;
        }
    }
    public static function GetSiteHomeUrl($echo = true) {
        $url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://" . $_SERVER['HTTP_HOST'];
        if ($echo) {
            echo $url;
        } else {
            return $url;
        }
    }

    public static function GetInstallUrl($echo = true) {
        $url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://" . $_SERVER['HTTP_HOST'] . '/install';
        if ($echo) {
            echo $url;
        } else {
            return $url;
        }
    }
    public static function GetSiteAdminUrl($echo = true) {
        $url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://" . $_SERVER['HTTP_HOST'] . '/admin';
        if ($echo) {
            echo $url;
        } else {
            return $url;
        }
    }
    public static function GetVer($echo = true) {
        $ver = '1.0';
        if ($echo) {
            echo $ver;
        } else {
            return $ver;
        }
    }
}