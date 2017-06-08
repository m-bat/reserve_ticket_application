<?php
session_start();
if (!isset($_SESSION["USERID"]) && !isset($_SESSION["ID"])) {
    header("Location: Logout.php");
    exit;
}

if (!isset($_POST['title']) || !isset($_POST['event_group']) || !isset($_POST['date']) || !isset($_POST['detail'])
 || !isset($_POST['station']) || !isset($_POST['capacity'])) {
   header("Location: fraud.php");
   exit;
 }else {
$errorMessage1 = "";
$errorMessage2 = "";
$errorMessage3 = "";
$errorMessage4 = "";
$errorMessage5 = "";

$registMessage = "";
$title = $_POST["title"];
$event_group = $_POST["event_group"];
$host = $_SESSION["ID"];
$date = $_POST["date"];
$detail = $_POST["detail"];
$station = $_POST["station"];
$capacity = $_POST["capacity"];

$conn = pg_connect ("host=localhost dbname=j141011m user=j141011m");
$query = "SELECT group_name FROM event_group WHERE id = $1";
$query1 = "SELECT name FROM station WHERE id = $1";
$result = pg_prepare ($conn, "my_query", $query);
$result1 = pg_prepare ($conn, "my_query1", $query1);
$result = pg_execute ($conn, "my_query", array($event_group));
$result1 = pg_execute ($conn, "my_query1", array($station));

$row = pg_fetch_array($result, NULL, PGSQL_ASSOC);
$row1 = pg_fetch_array($result1, NULL, PGSQL_ASSOC);
$con_event = $row['group_name'];
$con_station = $row1['name'];
$con_capacity = $capacity;

$date_format = 0;


  if (empty($title)) {
      $errorMessage1 = 'タイトルが入力されていません。';
    }

  if (empty($date)) {
    $errorMessage2 = '日時が入力されていません。';
  }else {
    if (strptime($date, '%Y/%m/%d %H:%M')) {
      $date_format = 0;
    }
    else {
      $errorMessage5 = '日付のフォーマットが正しくありません。フォーマットは年/月/日 時:分です。';
      $date_format = 1;
    }
  }
  if (empty($detail)) {
    $errorMessage3 = 'イベントの詳細が入力されていません。';
  }
  if (empty($capacity)) {
    $errorMessage4 = '定員が入力されていません。';
  }




  if (!empty($title) && !empty($event_group) && !empty($date) && !empty($detail) && !empty($capacity) && $date_format == 0) {
    $confirm = true;
  }
  else {
    $confirm = false;
  }

 if (!empty($title) && !empty($event_group) && !empty($date) && !empty($detail) && isset($_POST["regist"])) {
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
}
?>

<!doctype html>
<html>
    <head>
            <meta charset="UTF-8">
	    <link rel="stylesheet" href="./common/main.css">
            <title>確認画面</title>
            <p id="site">東横駅沿いイベント情報サイト</p>
            <FONT size="4"><h1>東横イベント検索くん</h1></FONT>
            <h4 id = "log"><a href="Logout.php">ログアウト</a> &nbsp; &nbsp; &nbsp; <a href="password.php">パスワード変更</a></h4>
    </head>
    <body>
      <div class="main_log">
        <div align="center">
        <h1>イベント登録確認画面</h1>

        <form id="loginForm" name="loginForm" action="regist_comp.php" method="POST">

            <fieldset class="login">
                <legend><h3>イベント登録確認ページ</h3></legend>


                <div><font color="#0000ff"><?php echo $errorMessage1 ?></font></div>
                <div><font color="#0000ff"><?php echo $errorMessage2 ?></font></div>
                <div><font color="#0000ff"><?php echo $errorMessage3 ?></font></div>
                <div><font color="#0000ff"><?php echo $errorMessage4 ?></font></div>
                <div><font color="#0000ff"><?php echo $errorMessage5 ?></font></div>


                <div><font color="#0000ff"><?php echo $registMessage ?></font></div>

                  <input type="hidden" name="mode" value="write">

                  <table class="table11">
                    <tbody>
                    <tr>
                      <th>イベントのタイトル：</th>
                      <td><?php echo $title; ?></td>
                    </tr>
                    <tr>
                  <th>イベントの種類 ：</th>
                  <td><?php echo $con_event;?></td>
                </tr>
                <tr>
                  <th>日時　：</th>
                  <td><?php  echo $date; ?></td>
                </tr>
                <tr>
                  <th>最寄駅　：</th>
                  <td><?php echo $con_station; ?></td>
                </tr>
                <tr>
                  <th>定員　：</th>
                  <td><?php echo $con_capacity; ?></td>
                </tr>
                <tr>
                  <th>イベントの詳細　：</th>
                  <td><?php echo $detail; ?></td>
                </tr>
              </tbody>
              </table>

                     <input type="hidden" name="title" value="<?php echo $title; ?>">
                     <input type="hidden" name="event_group" value="<?php echo $event_group; ?>">
                     <input type="hidden" name="date" value="<?php echo $date; ?>">
                     <input type="hidden" name="station" value="<?php echo $station; ?>">
                     <input type="hidden" name="host" value="<?php echo $host; ?>">
                     <input type="hidden" name="detail" value="<?php echo $detail; ?>">
                     <input type="hidden" name="capacity" value="<?php echo $capacity; ?>">

                <?php if($confirm){?>
                  <input type="submit" id="regist" name="regist" value="この内容で登録する">
                  <?php }?>
            </fieldset>
        </form>

        <form action="create_regist.php" method="post">
          <input type="hidden" name="title" value="<?php echo $title; ?>">
         <input type="hidden" name="event_group" value="<?php echo $event_group; ?>">
         <input type="hidden" name="date" value="<?php echo $date; ?>">
         <input type="hidden" name="station" value="<?php echo $station; ?>">
         <input type="hidden" name="detail" value="<?php echo $detail; ?>">
         <input type="hidden" name="capacity" value="<?php echo $con_capacity; ?>">
				<input type="submit" value="内容変更" />
			</form>
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
