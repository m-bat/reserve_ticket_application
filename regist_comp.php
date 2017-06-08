<?php
session_start();
if (!isset($_SESSION["USERID"]) && !isset($_SESSION["ID"])) {
    header("Location: Logout.php");
    exit;
}

if (!isset($_POST['title']) || !isset($_POST['event_group']) || !isset($_POST['date']) || !isset($_POST['detail'])
|| !isset($_POST['station']) || !isset($_POST['capacity'])) {
  header("Location: fraud.php");

}else {

$title = $_POST["title"];
$event_group = $_POST["event_group"];
$host = $_SESSION["ID"];
$date = $_POST["date"];
$detail = $_POST["detail"];
$station = $_POST["station"];
$capacity = $_POST["capacity"];

$regist_date = date('Y-m-d H:i:s');
$conn = pg_connect ("host=localhost dbname=j141011m user=j141011m");

try {
$query = "INSERT INTO event (host, title, detail, date, event_group, regist_date, station, capacity) VALUES ($1, $2, $3, $4, $5, $6, $7, $8)";
$result = pg_prepare ($conn, "my_query2", $query);
$result = pg_execute ($conn, "my_query2", array($host, $title, $detail, $date, $event_group, $regist_date, $station, $capacity));


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

            <title>イベント登録完了</title>
            <p id="site">東横駅沿いイベント情報サイト</p>
            <FONT size="4"><h1>東横イベント検索くん</h1></FONT>
            <h4 id = "log"><a href="Logout.php">ログアウト</a> &nbsp; &nbsp; &nbsp; <a href="password.php">パスワード変更</a></h4>
    </head>
    <body>
      <div class="main_log">
        <div align="center">
        <h1>登録完了</h1>
        <BR>
        <h4>イベント登録完了</h4>
        <BR>
        <input type="button" onclick="location.href='top.php' "value="トップページに戻る">

        <br>

      </div>
  </div>
  <div align="center">
  <img src="./common/image/sample.jpeg"  width="800" height="80">
  </div>
    </body>
</html>
