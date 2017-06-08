<?php
session_start();
if (!isset($_SESSION["USERID"]) && !isset($_SESSION["ID"])) {
    header("Location: Logout.php");
    exit;
}

if (!isset($_POST['title']) || !isset($_POST['detail']) || !isset($_POST['event_id']) || !isset($_POST['evaluation'])){
  header("Location: fraud.php");
  exit;
}
else {

$title = $_POST["title"];
$evaluation = $_POST["evaluation"];
$detail = $_POST["detail"];
$event_id = $_POST["event_id"];
$member = $_SESSION["ID"];

$regist_date = date('Y-m-d H:i:s');
$conn = pg_connect ("host=localhost dbname=j141011m user=j141011m");

try {
$query = "INSERT INTO review VALUES ($1, $2, $3, $4, $5, $6)";
$result = pg_prepare ($conn, "my_query2", $query);
$result = pg_execute ($conn, "my_query2", array($event_id, $member, $detail, $regist_date, $title, $evaluation));


$registMessage = '登録しました';
} catch (PDOException $Exception) {
print "エラー：" . $Exception->getMessage();
}
}
?>
<!doctype html>
<html>
    <head>
            <meta charset="UTF-8">
	    <link rel="stylesheet" href="./common/main.css">

            <title>レビュー登録完了（情報工学実験）</title>
            <p id="site">東横駅沿いイベント情報サイト</p>
            <FONT size="4"><h1>東横イベント検索くん</h1></FONT>
            <h4 id = "log"><a href="Logout.php">ログアウト</a> &nbsp; &nbsp; &nbsp; <a href="password.php">パスワード変更</a></h4>
    </head>
    <body>
      <div class="main_log">
        <div align="center">
        <h1>登録完了</h1>
        <p>レビュー登録完了</p>
        <p><a href="./top.php">トップページに戻る</a>;

        <br>

      </div>
  </div>
  <div align="center">
  <img src="./common/image/sample.jpeg"  width="800" height="80">
  </div>
    </body>
</html>
