<?php
session_start();

if (isset($_SESSION["USERID"])) {
    $errorMessage = "ログアウトしました。";
} else {
    $errorMessage = "セッションがタイムアウトしました。";
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
        <title>ログアウト（情報工学実験）</title>
        <p id="site">東横駅沿いイベント情報サイト</p>
        <FONT size="4"><h1>東横イベント検索くん</h1></FONT>
    </head>
      <BODY bgcolor="#FFFAF0" text="#000000">
      <div class="main_log">
<div align="center">
        <h1>ログアウト画面</h1>
        <div><?php echo $errorMessage; ?></div>
        <br>
            <a href="Login.php">ログイン画面に戻る</a>

      </div>
    </div>
    <div align="center">
    <img src="./common/image/sample.jpeg"  width="800" height="80">
    </div>
    </body>
</html>
