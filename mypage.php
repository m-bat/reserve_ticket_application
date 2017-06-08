<?php
session_start();
if (!isset($_SESSION["USERID"]) && !isset($_SESSION["ID"])) {
    header("Location: Logout.php");
    exit;
}
$errorMessage = "";
$registMessage = "";
$member = $_SESSION["ID"];

$conn = pg_connect ("host=localhost dbname=j141011m user=j141011m");
$query = "SELECT e.date, e.id, e.title, to_char(e.date, 'YYYY年MM月DD日'), m.login_name, g.group_name FROM event e, member m, event_group g, attend a WHERE a.member = $1 and a.id = e.id and e.host = m.id and e.event_group = g.id";
$result = pg_prepare ($conn, "my_query", $query);
$result = pg_execute ($conn, "my_query", array($member));
?>
<!doctype html>
<html>
    <head>
            <meta charset="UTF-8">
	    <link rel="stylesheet" href="./common/main.css">

            <title>参加申し込み一覧（情報工学実験）</title>
            <p id="site">東横駅沿いイベント情報サイト</p>
            <FONT size="4"><h1>東横イベント検索くん</h1></FONT>
            <h4 id = "log"><a href="Logout.php">ログアウト</a> &nbsp; &nbsp; &nbsp; <a href="password.php">パスワード変更</a></h4>
    </head>
    <body>
      <div class="main">
        <div align="center">
        <h1>現在参加申し込みをしているイベント一覧</h1>
        <BR>

            <!--<fieldset class="login">-->
              <fieldset class="login">
                <legend><h3>参加イベント一覧表示フォーム</h3></legend>

<h4><font color= "blue">終了しているイベントに関しては、レビュー投稿することができます！　ぜひ皆様のご意見をお聞かせください！</font></h4>
                <table class="table10">
                  <tgbody>
                  <tr>
                    <th>イベント名</th>
                    <th>種別</th>
                    <th>開催日程</th>
                    <th>主催者</th>
                    <th>レビュー</th>
                  </tr>

                  <?php
                  $query1 = "SELECT * FROM review WHERE event = $1 and member = $2";
                  $result1 = pg_prepare ($conn, "my_query1", $query1);
                  while ($row = pg_fetch_array($result, NULL, PGSQL_ASSOC)) {
                    $result1 = pg_execute ($conn, "my_query1", array($row['id'], $member));
                    $num = pg_num_rows($result1);
                    echo '<tr>';
                    echo '<td><a href="./detail.php?event_id='.$row['id'].'">'.$row['title'].'</a></td>';
                    echo '<td>'.$row['group_name'].'</td>';
                    echo '<td>'.$row['to_char'].'</td>';
                    echo '<td>'.$row['login_name'].'</td>';
                    if ($row['date'] < date('Y-m-d H:i:s')) {
                      if ($num == 0) {
                        echo '<td>';
                        echo '<FORM action="review_regist.php" method="post">';
                        echo '<input type="hidden" name="event_id" value="'.$row['id'].'">';
                        echo '<input type="hidden" name="tmp" value="1">';
                        echo '<INPUT type="submit" value="レビュー投稿" style="HEIGHT:28px">';
                        echo '</FORM>';
                        echo '</td>';
                      }else {
                        echo '<td>';
                        echo 'レビュー投稿済み';
                        echo '</td>';
                      }
                    }
                    else {
                      echo '<td>';
                      echo 'このイベントはまだ終了していません。';
                      echo '</td>';
                    }
                    echo '</tr>';
                  }
                   ?>
                 </tgbody>
                </table>
                <BR>
                <input type="button" onclick="location.href='top.php' "value="トップページに戻る">
            </fieldset>
        <br>

      </div>
  </div>
  <div align="center">
  <img src="./common/image/sample.jpeg"  width="800" height="80">
  </div>
    </body>
</html>
