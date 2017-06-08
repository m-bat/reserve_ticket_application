<?php
session_start();
if (!isset($_SESSION["USERID"]) && !isset($_SESSION["ID"])) {
    header("Location: Logout.php");
    exit;
}
$errorMessage = "";
$updateMessage = "";

function check_f($password) {
 $check_str1 = '(^.*(([0-9]|[a-z]|[A-Z])){4,8}$)';
 $check_str2 = '(^.*([0-9]))';
 $check_str3 = '(^.*([a-z]))';
 $check_str4 = '(^.*([A-Z]))';
 if (preg_match($check_str1, $password) != 0 ) {
     if (preg_match($check_str2, $password) != 0 ) {
         if (preg_match($check_str3, $password) != 0 ) {
             if (preg_match($check_str4, $password) != 0 ) {
                 return true;
             }
         }
     }
 }
 return false;
}



if (isset($_POST["update"])) {
  if (empty($_POST["old_password"])) {
    $errorMessage = '現在のパスワードが入力されていません。';
  } else if (empty($_POST["new_password"])) {
    $errorMessage = '新しいパスワードが入力されていません。';
  }



  if (!empty($_POST['old_password']) && !empty($_POST['new_password'])) {

    if (check_f($_POST['new_password'])) {

    $old_password = $_POST['old_password'];
    $new_password = $_POST['new_password'];
    $login_name = $_SESSION['USERID'];
    $query1 = "SELECT * FROM member WHERE login_name = $1";
    $conn = pg_connect ("host=localhost dbname=j141011m user=j141011m");
    $result = pg_prepare ($conn, "change_password", $query1);
    $result = pg_execute ($conn, "change_password", array($login_name));

    if ($rows = pg_fetch_array($result, NULL, PGSQL_ASSOC)) {
    if (password_verify($old_password, $rows['password'])) {
      $new_password_hash = password_hash($new_password, PASSWORD_DEFAULT);
      $query2 = "UPDATE member SET password = $1 WHERE login_name=$2";
      $result1 = pg_prepare($conn, "update", $query2);
      $result1 = pg_execute($conn, "update", array($new_password_hash, $login_name));
      $errorMessage = "";
      $updateMessage = 'パスワードを変更しました。';
    }else {
      $errorMessage = '現在のパスワードが間違っています。';
    }
  }
}else {
  $errorMessage = '大文字小文字数字を含み、かつ4文字以上のパスワードを発行してください。';
}
}
}
?>

<!doctype html>
<html>
    <head>
            <meta charset="UTF-8">
	    <link rel="stylesheet" href="./common/main.css">
            <title>パスワード変更（情報工学実験）</title>
            <p id="site">東横駅沿いイベント情報サイト</p>
            <FONT size="4"><h1>東横イベント検索くん</h1></FONT>
            <h4 id = "log"><a href="Logout.php">ログアウト</a> &nbsp; &nbsp; &nbsp; <a href="password.php">パスワード変更</a></h4>
    </head>
    <body>
      <div class="main_log">
        <div align="center">
        <h1>パスワード変更（情報工学実験）</h1>
        <h3>ようこそ<u><?php echo htmlspecialchars($_SESSION["USERID"], ENT_QUOTES); ?></u>さん</h3>

        <form id="loginForm" name="loginForm" action="" method="POST">
            <fieldset class="login">
                <legend><h3>パスワード変更フォーム</h3></legend>
                <div><font color="#ff0000"><?php echo $errorMessage ?></font></div>
                <div><font color="#0000ff"><?php echo $updateMessage ?></font></div>
                <label for="login_name"><span>現在のパスワードを入力</span></label><input size="19" type="password" id="old_password" name="old_password" placeholder="現在のパスワードを入力" value="">
                <br>
<br>
                <label for="password"><span>新しいパスワード</span></label><input size="20" type="password" id="new_password" name="new_password" value="" placeholder="新しいパスワードを入力">
                <br>
<br>
                <input type="submit" id="update" name="update" value="変更">
                <BR>
                  <h4><li><a href="top.php">トップページに戻る</a></li></h4>
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
