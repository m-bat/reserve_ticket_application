<?php
session_start();
if (!isset($_SESSION["USERID"]) && !isset($_SESSION["ID"])) {
    header("Location: Logout.php");
    exit;
}

if (!isset($_POST['event_id'])) {
  header("Location: fraud.php");
  exit;
}
else {

$member = $_SESSION['ID'];
$event = $_POST['event_id'];

$conn = pg_connect ("host=localhost dbname=j141011m user=j141011m");
$query = "DELETE FROM attend WHERE id = $1 AND member = $2";
$result = pg_prepare($conn, "cancel", $query);
$result = pg_execute($conn, "cancel", array($event, $member));
}

?>
<!doctype html>
<html>
    <head>
            <meta charset="UTF-8">
	    <link rel="stylesheet" href="./common/main.css">

            <title>参加取消完了（情報工学実験）</title>
            <p id="site">東横駅沿いイベント情報サイト</p>
            <FONT size="4"><h1>東横イベント検索くん</h1></FONT>
            <h4 id = "log"><a href="Logout.php">ログアウト</a> &nbsp; &nbsp; &nbsp; <a href="password.php">パスワード変更</a></h4>
    </head>
    <body>
      <div class="main_log">
        <div align="center">
        <h1>イベント参加取消完了</h1>
        <p>イベント参加取消完了しました</p>
        <p><a href="./detail.php?event_id=<?php echo $event?>">前のページに戻る</a>;

        <br>

      </div>
  </div>
  <div align="center">
  <img src="./common/image/sample.jpeg"  width="800" height="80">
  </div>
    </body>
</html>
