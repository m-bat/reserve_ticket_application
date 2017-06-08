<?php
session_start();
if (!isset($_SESSION["USERID"]) && !isset($_SESSION["ID"])) {
    header("Location: Logout.php");
    exit;
}
$errorMessage = "";
$registMessage = "";
$dateMessage = "このイベントはすでに終了しています。";

if (!isset($_GET['event_id'])) {
  header("Location: fraud.php");
  exit;
}
else {

$member = $_SESSION["ID"];
$event_id = $_GET['event_id'];
$conn = pg_connect ("host=localhost dbname=j141011m user=j141011m");
$query = "SELECT m.id, e.title, g.group_name, s.name, to_char(e.date, 'YYYY年MM月DD日HH24時MI分') AS date, e.date AS date1, m.login_name, e.detail, to_char(e.regist_date, 'YYYY年MM月DD日HH24時MI分SS秒'), e.capacity FROM event e, member m, event_group g, station s WHERE e.id = $1 and e.host = m.id and e.event_group = g.id and e.station = s.id";
$query1 = "SELECT * FROM attend WHERE member = $1 AND id = $2";
$query2 = "SELECT m.login_name FROM member m, attend a WHERE a.member = m.id AND a.id = $1";
$query3 = "SELECT * FROM attend WHERE id = $1";
$query4 = "SELECT r.title, r.regist_date, m.login_name, r.evaluation, r.detail FROM review r, member m WHERE r.event = $1 and r.member = m.id ORDER BY r.regist_date DESC";

$result = pg_prepare ($conn, "my_query", $query);
$result1 = pg_prepare($conn, "attend_check", $query1);
$result2 = pg_prepare($conn, "attends", $query2);
$result3 = pg_prepare($conn, "capacity", $query3);
$result4 = pg_prepare($conn, "review", $query4);
$result = pg_execute ($conn, "my_query", array($event_id));
$result1 = pg_execute($conn, "attend_check", array($_SESSION['ID'], $event_id));
$result2 = pg_execute($conn, "attends", array($event_id));
$result3 = pg_execute($conn, "capacity", array($event_id));
$result4 = pg_execute($conn, "review", array($event_id));
$row = pg_fetch_array($result, NULL, PGSQL_ASSOC);
$attend_num = pg_num_rows($result2);

$num4 = pg_num_rows($result4);

$n_array = array();

$date = date('Y-m-d H:i:s');
if ($row['date1'] < $date) {
  $finish = true;
}
else {
  $finish = false;
}

for ($i=0; $i<$attend_num; $i++) {
  $row3=pg_fetch_assoc($result2, $i);
  $n_array[]=$row3['login_name'];
}
$names = implode(", ", $n_array);
}
?>
<!doctype html>
<html>
    <head>
            <meta charset="UTF-8">
	    <link rel="stylesheet" href="./common/main.css">

            <title>イベント詳細（情報工学実験）</title>
            <p id="site">東横駅沿いイベント情報サイト</p>
            <FONT size="4"><h1>東横イベント検索くん</h1></FONT>
            <h4 id = "log"><a href="Logout.php">ログアウト</a> &nbsp; &nbsp; &nbsp; <a href="password.php">パスワード変更</a></h4>
    </head>
    <body>
      <div class="main">
      <div align="center">
      <div class="blocka">
        <div align="center">

        <h1>イベント詳細ページ</h1>

      <!--  <form id="loginForm" name="loginForm" action="confirm.php" method="POST"> -->
            <!--<fieldset class="login">-->
              <fieldset class="login">
                <legend><h3>イベント詳細表示フォーム</h3></legend>

                <?php
                if ($finish) {

                echo '<h4><font color="red">'.$dateMessage.'</font></h4>';
                }
                 ?>

                <table class="table11">
                  <tbody>
                  <tr>
                    <td align="center" bgcolor="white" colspan="2">イベント詳細</td>
                  </tr>
                  <tr><th>イベント名</th><td><?php echo $row['title']?></td></tr>
                  <tr><th>イベントの種類</th><td><?php echo $row['group_name'] ?></td></tr>
                  <tr><th>主催者</th><td><?php echo $row['login_name']?></td></tr>
                  <tr><th>最寄駅</th><td><?php echo $row['name']?></td></tr>
                  <tr><th>定員</th><td><?php echo $row['capacity']?></td></tr>
                  <tr><th>日程</th><td><?php echo $row['date']?></td></tr>
                  <tr><th>詳細</th><td><?php echo $row['detail']?></td></tr>
                  <tr><th>投稿日時</th><td><?php echo $row['to_char']?></td></tr>
                  <tr><th>参加者</th><td><?php if($attend_num == 0){echo '現在参加者はいません';} else{echo $names;}?></td></tr>

                </tbody>
                </table>

                <?php
                if (pg_num_rows($result1) == 1) {

                  if ($row['id'] == $member) {
                    echo '<BR><p><font color="red">あなたはこのイベントの幹事のため参加は必須です。参加取消をすることはできません。</font></p>';
                  }else {
                    if (!$finish) {
                  echo '<tr><td colspan="2"><form method="POST" action="cancel.php">';
                  echo '<input type="hidden" name="event_id" value="'.$event_id.'">';
                  echo '<input type="submit" value="参加取消">';
                  echo '</form></td></tr>';
                }
                }
                }else {
                  if (pg_num_rows($result3) >= $row['capacity']) {
                    if ($row['id'] == $member) {
                      echo '<BR><p><font color="red">あなたはこのイベントの幹事のため参加は必須です。参加ボタンをは表示されません。</font></p>';
                    }else {
                    echo '<p><font color="red">定員超過のため参加登録することができません。キャンセルをお待ちください。</font></p>';
                  }


                  }else {
                    if ($row['id'] == $member) {
                      echo '<BR><p><font color="red">あなたはこのイベントの幹事のため参加は必須です。参加ボタンは表示されません。</font></p>';
                    }
                    else {
                        if (!$finish) {
                    echo '<tr><td colspan="2"><form method="POST" action="attend.php">';
                    echo '<input type="hidden" name="event_id" value="'.$event_id.'">';
                    echo '<input type="submit" value="参加登録">';
                    echo '</form></td></tr>';
                  }
                  }
                  }
                  }


                ?>
                <BR>
                  <input type="button" onclick="location.href='list.php' "value="イベント一覧に戻る">
                <input type="button" onclick="location.href='top.php' "value="トップページに戻る">
            </fieldset>
        <!--</form>-->
        <br>

      </div>
  </div>

  <div class="blockb">
    <div align="left">
        <section class="regist">
      <h3>このイベントのレビュー</h3>
      <?php
      if ($finish) {
        if ($num4 == 0) {
          echo '<h5>まだレビューが投稿されていません</h4>';
        }
        else {
          echo '<h5>レビュー一覧</h4>';
          while ($row4 = pg_fetch_array($result4, NULL, PGSQL_ASSOC)) {
            echo 'タイトル: '.$row4['title'].'<BR>';
            echo '投稿日時: '.$row4['regist_date'].'<BR>';
            echo '投稿者　: '.$row4['login_name'].'<BR>';
            if ($row4['evaluation'] ==  1) {
            echo '評価　　: ☆<BR>';
            }
            else if ($row4['evaluation'] ==  2) {
            echo '評価　　: ☆☆<BR>';
            }
            else if ($row4['evaluation'] ==  3) {
            echo '評価　　: ☆☆☆<BR>';
            }
            else if ($row4['evaluation'] ==  4) {
            echo '評価　　: ☆☆☆☆<BR>';
            }
            else if ($row4['evaluation'] ==  5) {
            echo '評価　　: ☆☆☆☆☆<BR>';
            }
            echo '感想　　: '.$row4['detail'].'<BR>';
            echo '---------------------------------------<BR><BR>';
          }
        }
    }else {
      echo '<h5>このイベントはまだ終了していないのでレビューが投稿されておりません。';
    }
    ?>
</section>
    </div>
  </div>
</div>
</div>
  <div align="center">
  <img src="./common/image/sample.jpeg"  width="800" height="80">
  </div>
    </body>
</html>
