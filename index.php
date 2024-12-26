<?php
/**
 * QQ登录中转API，二开自彩虹。
 * @package qloginAPI
 * @author: 鼠子Tomoriゞ
 * @version: 1.0
 * @link: https://github.com/ShuShuicu/qloginAPI
 */
include './includes/common.php';
header("Content-type:application/json");
$data = array(
    'code' => '403',
    'message' => '你长得太丑访问被拒'
);
echo json_encode($data, JSON_UNESCAPED_UNICODE);
