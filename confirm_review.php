<?php
session_start();
if (!isset($_SESSION["USERID"]) && !isset($_SESSION["ID"])) {
    header("Location: Logout.php");
    exit;
}

$errorMessage1 = "";
$errorMessage2 = "";
$errorMessage3 = "";

if (!isset($_POST['title']) || !isset($_POST['evaluation']) || !isset($_POST['detail']) || !isset($_POST['event_id'])) {
  header("Location: fraud.php");
  exit;
} else {
$registMessage = "";
$title = $_POST["title"];
$evaluation = $_POST["evaluation"];
$detail = $_POST["detail"];
$event_id = $_POST["event_id"];
$member = $_SESSION["ID"];


  if (empty($title)) {
      $errorMessage1 = 'タイトルが入力されていません。';
    }

  if (empty($evaluation)) {
    $errorMessage2 = '評価が入力されていません。';
  }
  if (empty($detail)) {
    $errorMessage3 = 'レビューの詳細が入力されていません。';
  }


  if (!empty($title) && !empty($evaluation) && !empty($detail)) {
    $confirm = true;
  }
  else {
    $confirm = false;
  }

 if (!empty($title) && !empty($evaluation) && !empty($detail) && isset($_POST["regist"])) {
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
}
?>

<!doctype html>
<html>
    <head>
            <meta charset="UTF-8">
	    <link rel="stylesheet" href="./common/main.css">
            <title>レビュー投稿確認画面（情報工学実験）</title>
            <p id="site">東横駅沿いイベント情報サイト</p>
            <FONT size="4"><h1>東横イベント検索くん</h1></FONT>
            <h4 id = "log"><a href="Logout.php">ログアウト</a> &nbsp; &nbsp; &nbsp; <a href="password.php">パスワード変更</a></h4>
    </head>
    <body>
      <div class="main_log">
        <div align="center">
        <h1>レビュー投稿確認画面</h1>

        <form id="loginForm" name="loginForm" action="regist_comp_review.php" method="POST">

            <fieldset class="login">
                <legend><h3>レビュー投稿確認ページ</h3></legend>
              

                <div><font color="#0000ff"><?php echo $errorMessage1 ?></font></div>
                <div><font color="#0000ff"><?php echo $errorMessage2 ?></font></div>
                <div><font color="#0000ff"><?php echo $errorMessage3 ?></font></div>

                <div><font color="#0000ff"><?php echo $registMessage ?></font></div>

                  <input type="hidden" name="mode" value="write">

                  <table class="table11">
                    <tr>
                      <th>レビューのタイトル：</th>
                      <td><?php echo $title; ?></td>
                    </tr>
                    <tr>
                  <th>評価 ：</th>
                  <td><?php
                  if ($evaluation == 1) {
                    echo '☆';
                  }
                  else if ($evaluation == 2) {
                    echo '☆☆';
                  }
                  else if ($evaluation == 3) {
                    echo '☆☆☆';
                  }
                  else if ($evaluation == 4) {
                    echo '☆☆☆☆';
                  }
                  else if ($evaluation == 5) {
                    echo '☆☆☆☆☆';
                  }
                   ?></td>
                </tr>
                <tr>
                  <th>レビューの詳細　：</th>
                  <td><?php echo $detail; ?></td>
                </tr>
              </table>

<BR>
                     <input type="hidden" name="title" value="<?php echo $title; ?>">
                     <input type="hidden" name="evaluation" value="<?php echo $evaluation; ?>">
                     <input type="hidden" name="detail" value="<?php echo $detail; ?>">
                      <input type="hidden" name="event_id" value="<?php echo $event_id; ?>">

                <?php if($confirm){?>
                  <input type="submit" id="regist" name="regist" value="この内容で登録する">
                  <?php }?>
            </fieldset>
        </form>

        <form action="review_regist.php" method="post">
          <input type="hidden" name="title" value="<?php echo $title; ?>">
         <input type="hidden" name="evaluation" value="<?php echo $evaluation; ?>">
         <input type="hidden" name="detail" value="<?php echo $detail; ?>">
         <input type="hidden" name="event_id" value="<?php echo $event_id; ?>">
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
