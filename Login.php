<?php
session_start();
$errorMessage = "";

if (isset($_POST["login"])) {
  if (empty($_POST["login_name"])) {
    $errorMessage = 'ユーザーIDが入力されていません。';
  } else if (empty($_POST["password"])) {
    $errorMessage = 'パスワードが入力されていません。';
  }



  if (!empty($_POST["login_name"]) && !empty($_POST["password"])) {
    $login_name = $_POST["login_name"];
    $conn = pg_connect ("host=localhost dbname=j141011m user=j141011m");
    $query = "SELECT * FROM member WHERE login_name = $1";
    $result = pg_prepare ($conn, "my_query", $query);
    $result = pg_execute ($conn, "my_query", array($login_name));



    $password = $_POST["password"];

    if ($rows = pg_fetch_array($result, NULL, PGSQL_ASSOC)) {
      if (password_verify($password, $rows['password'])) {
        session_regenerate_id(true);

        $_SESSION["USERID"] = $rows['login_name'];
        $_SESSION["ID"] = $rows['id'];
        header("Location: top.php");
        exit();
      }else {
        $errorMessage = 'ログインネームもしくはパスワードに謝りがあります。';
      }
    } else {
      $errorMessage = 'ログインネームあるいはパスワードに誤りがあります。';
    }
  }
}
?>
<!doctype html>
<html>
    <head>
            <meta charset="UTF-8">
	    <link rel="stylesheet" href="./common/main.css">
            <title>ログイン（情報工学実験）</title>
            <p id="site">東横駅沿いイベント情報サイト</p>
            <FONT size="4"><h1>東横イベント検索くん</h1></FONT>

    </head>
    <BODY bgcolor="#FFFAF0" text="#000000">
      <div class="main_log">
        <div align="center">
        <h1>ログイン画面</h1>

        <form id="loginForm" name="loginForm" action="" method="POST">
            <fieldset class="login">
                <legend><h3>ログインフォーム</h3></legend>
                <div><font color="#ff0000"><?php echo $errorMessage ?></font></div>
                <label for="login_name"><span>ログイン名</span></label><input size="19" type="text" id="login_name" name="login_name" placeholder="ログイン名を入力" value="<?php if (!empty($_POST["login_name"])) {echo htmlspecialchars($_POST["login_name"], ENT_QUOTES);} ?>">
                <br>
<br>
                <label for="password"><span>パスワード</span></label><input size="20" type="password" id="password" name="password" value="" placeholder="パスワードを入力">
                <br>
<br>
                <input type="submit" id="login" name="login" value="ログイン">
            </fieldset>
        </form>
        <br>
        <form action="SignUp.php">
            <fieldset class="signup">
                <legend><h3>新規登録フォーム</h3></legend>
<p>まだ登録されていない方はこちらから登録を行ってください。</p>
                <input type="submit" value="新規登録">
            </fieldset>
        </form>

      </div>
  </div>
  <div align="center">
  <img src="./common/image/sample.jpeg"  width="800" height="80">
</div>
    </body>
</html>
