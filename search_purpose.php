<?php

session_start();

// ログイン状態チェック
if (!isset($_SESSION["USERID"]) && !isset($_SESSION["ID"])) {
    header("Location: Logout.php");
    exit;
}

if (empty($_POST['purpose'])) {
  $check = false;
}
else {
  $check = true;
}

if ($check) {
if(is_array($_POST['purpose'])) {
$sql = "SELECT e.id, e.title, to_char(e.date, 'YYYY年MM月DD日'), m.login_name, g.group_name FROM event e, member m, event_group g WHERE e.host = m.id and (e.event_group = g.id and ";

$i=0;
foreach($_POST['purpose'] AS $purpose) {
if ($i) {
$sql .= " OR ";
}
$sql .= "e.event_group = $purpose and e.event_group = g.id ";
$i++;
}

}
$sql .= ")";


$conn = pg_connect ("host=localhost dbname=j141011m user=j141011m");
$result = pg_prepare ($conn, "my_query", $sql);
$result = pg_execute ($conn, "my_query", array());
}

?>

<!doctype html>
<html>
    <head>
            <meta charset="UTF-8">
	    <link rel="stylesheet" href="./common/main.css">

            <title>目的検索結果一覧（情報工学実験）</title>
            <p id="site">東横駅沿いイベント情報サイト</p>
            <FONT size="4"><h1>東横イベント検索くん</h1></FONT>
            <h4 id = "log"><a href="Logout.php">ログアウト</a> &nbsp; &nbsp; &nbsp; <a href="password.php">パスワード変更</a></h4>
    </head>
    <body>
      <div class="main_log">
        <div align="center">
        <h1>目的検索結果一覧</h1>

        <form id="loginForm" name="loginForm" action="confirm.php" method="POST">
            <!--<fieldset class="login">-->
              <fieldset class="login">
                <legend><h3>イベント一覧表示フォーム</h3></legend>

                <table class="table10">
                  <tgbody>
                  <tr>
                    <th>イベント名</th>
                    <th>種別</th>
                    <th>開催日程</th>
                    <th>主催者</th>
                  </tr>

                  <?php
                  if ($check) {
                  while ($row = pg_fetch_array($result, NULL, PGSQL_ASSOC)) {
                    echo '<tr>';
                    echo '<td><a href="./detail.php?event_id='.$row['id'].'">'.$row['title'].'</a></td>';
                    echo '<td>'.$row['group_name'].'</td>';
                    echo '<td>'.$row['to_char'].'</td>';
                    echo '<td>'.$row['login_name'].'</td>';
                    echo '</tr>';
                  }
                }else {
                  echo '<h4>一つ以上選択をしてください</h4>';
                }
                   ?>
                  <tgbody>
                </table>
                <BR>
                <input type="button" onclick="location.href='top.php' "value="トップページに戻る">
            </fieldset>
        </form>
        <br>

      </div>
  </div>
  <div align="center">
  <img src="./common/image/sample.jpeg"  width="800" height="80">
  </div>
    </body>
</html>
