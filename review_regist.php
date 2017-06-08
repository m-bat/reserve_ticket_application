<?php
session_start();
if (!isset($_SESSION["USERID"]) && !isset($_SESSION["ID"])) {
    header("Location: Logout.php");
    exit;
}
$errorMessage = "";
$registMessage = "";

if (!isset($_POST['event_id'])) {
  header("Location: fraud.php");
  exit;
}

else {

$event_id = $_POST['event_id'];

 if (!empty($_POST['tmp'])) {
   $first = false;
 }
 else {
   $first = true;
 }
if (isset($_POST["confirm"])) {
    if (empty($_POST["title"])) {
      $errorMessage = 'タイトルが入力されていません。';
    }
    else if (empty($_POST["evaluation"])) {
      $errorMessage = '評価が入力されていません';
  } else if (empty($_POST["detail"])) {
    $errorMessage = '感想が入力されていません。';
    }
    else if (empty($_POST["capacity"])) {
      $errorMessage = '店員が入力されていません';
    }
 }
 }
?>
<!doctype html>
<html>
    <head>
            <meta charset="UTF-8">
	    <link rel="stylesheet" href="./common/main.css">

            <title>レビュー投稿（情報工学実験）</title>
            <p id="site">東横駅沿いイベント情報サイト</p>
            <FONT size="4"><h1>東横イベント検索くん</h1></FONT>
            <h4 id = "log"><a href="Logout.php">ログアウト</a> &nbsp; &nbsp; &nbsp; <a href="password.php">パスワード変更</a></h4>
    </head>
    <body>
      <div class="main_log">
        <div align="center">
        <h1>レビュー投稿画面</h1>

        <form id="loginForm" name="confirm" action="confirm_review.php" method="POST">
            <!--<fieldset class="login">-->
              <fieldset class="login">
                <legend><h3>レビュー登録フォーム</h3></legend>


                <div><font color="#0000ff"><?php echo $errorMessage ?></font></div>

                <div><font color="#0000ff"><?php echo $registMessage ?></font></div>


                <table class="table11">
                  <col span="1" style="background-color:#ffffff">
                  <col width="200">
                <tr>

                  <th>レビューのタイトル：</th>
                  <td><input type="text" name="title" size="20" value="<?php if($first){?><?php echo $_POST["title"]; ?><?php } else {?><?php }?>"></td>
                </tr>
                <tr>
                  <th>評価（5段階）：</th>
                <td><select name="evaluation">
                   <option value= "1">☆</option>
                   <option value= "2">☆☆</option>
                   <option value= "3">☆☆☆</option>
                   <option value= "4">☆☆☆☆</option>
                   <option value= "5">☆☆☆☆☆</option>
                 </select>
               </td>
                </tr>

                <th>レビューの詳細　：</th>
                  <td>
                  <textarea name="detail" rows="8" cols="80"><?php if($first){?><?php echo $_POST["detail"]; ?><?php }else{?><?php }?></textarea></td>
                </tr>
                </table>
                <BR>
                <input type="hidden" name="event_id" value="<?php echo $event_id; ?>">
                <input type="submit" id="regist" name="confirm" value="確認ページへいく">
                <input type="button" onclick="location.href='mypage.php' "value="前のページに戻る">
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
