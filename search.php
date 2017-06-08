<?php
session_start();
if (!isset($_SESSION["USERID"]) && !isset($_SESSION["ID"])) {
    header("Location: Logout.php");
    exit;
}

if (!isset($_GET['keyword'])) {
  header("Location: fraud.php");
  exit;
}
else {
if (empty($_GET['keyword'])) {
  $key = true;
}
else {
  $key = false;
}

$keyword = "%".$_GET['keyword']."%";

$errorMessage = "";
$registMessage = "";
$conn = pg_connect ("host=localhost dbname=j141011m user=j141011m");
$query = "SELECT e.id, e.title, to_char(e.date, 'YYYY年MM月DD日'), m.login_name, g.group_name, e.detail FROM event e, member m, event_group g WHERE e.host = m.id and e.event_group = g.id and e.detail like $1";
$result = pg_prepare ($conn, "my_query", $query);
$result = pg_execute ($conn, "my_query", array($keyword));
$num = pg_num_rows($result);
}

?>
<!doctype html>
<html>
    <head>
            <meta charset="UTF-8">
	    <link rel="stylesheet" href="./common/main.css">

            <title>キーワード検索結果（情報工学実験）</title>
            <p id="site">東横駅沿いイベント情報サイト</p>
            <FONT size="4"><h1>東横イベント検索くん</h1></FONT>
            <h4 id = "log"><a href="Logout.php">ログアウト</a> &nbsp; &nbsp; &nbsp; <a href="password.php">パスワード変更</a></h4>
    </head>
    <body>
      <div class="main_log">
        <div align="center">
        <h1>キーワード検索結果一覧</h1>

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
                    <th>詳細</th>
                  </tr>

                  <?php
                  if ($key || $num == 0) {
                    echo '<h4>検索結果はありません</h4>';
                  }else {
                  while ($row = pg_fetch_array($result, NULL, PGSQL_ASSOC)) {
                    echo '<tr>';
                    echo '<td><a href="./detail.php?event_id='.$row['id'].'">'.$row['title'].'</a></td>';
                    echo '<td>'.$row['group_name'].'</td>';
                    echo '<td>'.$row['to_char'].'</td>';
                    echo '<td>'.$row['login_name'].'</td>';
                    echo '<td>'.$row['detail'].'</td>';
                    echo '</tr>';
                  }
                  }
                   ?>
                 </tgbody>
                </table>
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
