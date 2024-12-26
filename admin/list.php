<?php
include "../includes/common.php";
$title = "应用列表";
include "./head.php";
if ($islogin == 1) {
} else {
    exit(
        "<script language='javascript'>window.location.href='./login.php';</script>"
    );
}
?>
        <?php
$my = isset($_GET["my"]) ? $_GET["my"] : null;

if ($my == "search") {
    $sql = " `{$_GET["column"]}`='{$_GET["value"]}'";
    $numrows = $DB->getColumn("SELECT count(*) from ucenter_apps WHERE{$sql}");
    $con = "包含 " . $_GET["value"] . " 的共有 <b>" . $numrows . "</b> 个记录";
    $link = "&my=search&column=" . $_GET["column"] . "&value=" . $_GET["value"];
} else {
    $numrows = $DB->getColumn("SELECT count(*) from ucenter_apps WHERE 1");
    $sql = " 1";
    $con = "系统共有 <b>" . $numrows . "</b> 条记录";
}
?>
<form action="list.php" method="GET" class="form-inline"><input type="hidden" name="my" value="search">
<div class="mdui-card-primary-title">
<?php
echo $con;
?>
</div>
<div class="mdui-divider"></div>
    <div class="mdui-card-content">
    <select name="type" class="mdui-select" default="<?php echo $_GET['type'] ?>">
        <option value="0" selected>选择搜索方式</option>
        <option value="appid">APPID</option>
        <option value="name">应用名称</option>
        <option value="url">应用域名</option>
    </select>
    <div class="mdui-textfield">
    <i class="mdui-icon material-icons">account_circle</i>
      <input type="text" class="mdui-textfield-input" name="value"  placeholder="搜索内容">
  </div>
  <div class="mdui-float-right">
    <a :href="url.Admin + '/edit.php?my=add'" class="mdui-btn mdui-btn-raised mdui-ripple mdui-color-theme">添加</a>
    <button type="submit" class="mdui-btn mdui-btn-raised mdui-ripple mdui-color-theme-accent">搜索</button>
  </div>
</form>
		<div class="mdui-table-fluid">
            <table class="mdui-table mdui-table-hoverable">
                <thead>
                    <tr>
                        <th>APPID</th>
                        <th>APPKEY</th>
                        <th>应用名称</th>
                        <th>应用域名</th>
                        <th>添加时间</th>
                        <th>状态</th>
                        <th>操作</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
$pagesize = 30;
$pages = ceil($numrows / $pagesize);
$page = isset($_GET["page"]) ? intval($_GET["page"]) : 1;
$offset = $pagesize * ($page - 1);

$rs = $DB->query(
    "SELECT * FROM ucenter_apps WHERE{$sql} order by appid desc limit $offset,$pagesize"
);
while ($res = $rs->fetch()) {
    echo "<tr><td><b>" .
        $res["appid"] .
        "</b></td><td>" .
        $res["appkey"] .
        "</td><td>" .
        $res["name"] .
        "</td><td>" .
        $res["url"] .
        "</td><td>" .
        $res["addtime"] .
        "</td><td>" .
        ($res["status"] == 1
            ? '<font color="green">开启</font>'
            : '<font color="red">关闭</font>') .
        '</td><td><a href="./edit.php?my=reset&appid=' .
        $res["appid"] .
        '" class="btn btn-xs btn-default" onclick="return confirm(\'你确实要重置APPKEY吗？\');">重置KEY</a>&nbsp;<a href="./edit.php?my=edit&appid=' .
        $res["appid"] .
        '" class="btn btn-xs btn-info">编辑</a>&nbsp;<a href="./edit.php?my=del&appid=' .
        $res["appid"] .
        '" class="btn btn-xs btn-danger" onclick="return confirm(\'你确实要删除此记录吗？\');">删除</a>&nbsp;<a href="./log.php?my=search&appid=' .
        $res["appid"] .
        '" class="btn btn-xs btn-default">日志</a></td></tr>';
}
?>
                </tbody>
            </table>
        </div>
        <?php
echo '<ul class="pagination">';
$first = 1;
$prev = $page - 1;
$next = $page + 1;
$last = $pages;
if ($page > 1) {
    echo '<li><a href="list.php?page=' . $first . $link . '">首页</a></li>';
    echo '<li><a href="list.php?page=' . $prev . $link . '">&laquo;</a></li>';
} else {
    echo '<li class="disabled"><a>首页</a></li>';
    echo '<li class="disabled"><a>&laquo;</a></li>';
}
$start = $page - 10 > 1 ? $page - 10 : 1;
$end = $page + 10 < $pages ? $page + 10 : $pages;
for ($i = $start; $i < $page; $i++) {
    echo '<li><a href="list.php?page=' . $i . $link . '">' . $i . "</a></li>";
}
echo '<li class="disabled"><a>' . $page . "</a></li>";
for ($i = $page + 1; $i <= $end; $i++) {
    echo '<li><a href="list.php?page=' . $i . $link . '">' . $i . "</a></li>";
}
echo "";
if ($page < $pages) {
    echo '<li><a href="list.php?page=' . $next . $link . '">&raquo;</a></li>';
    echo '<li><a href="list.php?page=' . $last . $link . '">尾页</a></li>';
} else {
    echo '<li class="disabled"><a>&raquo;</a></li>';
    echo '<li class="disabled"><a>尾页</a></li>';
}
echo "</ul>";
?>
    </div>
</div>
<?php require_once "./foot.php"; ?>