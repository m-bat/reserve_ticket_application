<?php
session_start();

if (isset($_SESSION["USERID"])) {
    $errorMessage = "不正なアクセスのためログアウトいたしました。";
} else {
    $errorMessage = "不正アクセスを検知しました。";
}

// セッションの変数のクリア
$_SESSION = array();

// セッションクリア
@session_destroy();
?>

<!doctype html>
<html>
    <head>
        <meta charset="UTF-8">
        <link rel="stylesheet" href="./common/main.css">
        <title>不正アクセス（情報工学実験）</title>
    </head>
      <BODY bgcolor="#FFFAF0" text="#000000">
      <div class="main_log">
<div align="center">
        <h1>不正アクセス検知画面</h1>
        <h3><?php echo $errorMessage; ?></h3>
        <br>
            <a href="Login.php">ログイン画面に戻る</a>

      </div>
    </div>
    <div align="center">
    <img src="./common/image/sample.jpeg"  width="800" height="80">
    </div>
    </body>
</html>
