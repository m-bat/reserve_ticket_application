<?php
//session_start();
$errorMessage = "";
$SignUpMessage = "";

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



if (isset($_POST['signUp'])) {
  if (empty($_POST['login_name'])){
    $errorMessage = 'ログインネームが入力されていません';
  }else if (empty($_POST['real_name'])) {
    $errorMessage = '本名が入力されていません';
  }else if (empty($_POST['mail'])) {
    $errorMessage = 'メールアドレスが入力されていません';
  }else if (empty($_POST['password'])) {
    $errorMessage = 'パスワードが入力されていません';
  }else if (empty($_POST['password2'])) {
    $errorMessage = '確認パスワードが入力されていません';
  }



  if (!empty($_POST["login_name"]) && !empty($_POST["real_name"]) && !empty($_POST["mail"]) && !empty($_POST["password"]) && !empty($_POST["password2"]) && $_POST["password"] == $_POST["password2"]) {
       $login_name= $_POST["login_name"];
       $real_name = $_POST["real_name"];
       $mail = $_POST["mail"];

       $password = $_POST["password"];

       $hashpwd = password_hash($password, PASSWORD_DEFAULT);

       $conn = pg_connect ("host=localhost dbname=j141011m user=j141011m");
       $query1 = "SELECT * FROM member WHERE login_name = $1";
       $result1 = pg_prepare ($conn, "my_query1", $query1);
       $result1 = pg_execute ($conn, "my_query1", array($login_name));
       $num = pg_num_rows($result1);



       if (check_f($password)) {

         if ($num == 0) {

         $query = "INSERT INTO member (real_name, login_name, password, mail) VALUES ($1, $2, $3, $4)";
         $result = pg_prepare ($conn, "my_query", $query);
         $result = pg_execute ($conn, "my_query", array($real_name, $login_name, $hashpwd, $mail));
         $SignUpMessage = '登録が完了しました。あなたのログインネームは '.$login_name.' です。';
       }
       else {
         $errorMessage = 'ログインネーム　'.$login_name.'　はすでに使われています。';
       }
       }else {
         $errorMessage = '大文字小文字数字を含み、かつ4文字以上のパスワードを入力してください。';
       }


      //$sth = $dbh->prepare ("INSERT INTO member(real_name, login_name, password, mail) VALUES (?, ?, ?, ?)");
        // $sth->execute(array($real_name, $login_name, password_hash($password, PASSWORD_DEFAULT), $mail));
        // $sth->execute();
         //$SignUpMessage = '登録が完了しました。あなたの登録IDは '. $userid. 'です。';


  } else if($_POST["password"] != $_POST["password2"]) {
     $errorMessage = 'パスワードに謝りがあります。';
  }
}
?>

<!doctype html>
<html>
	<head>
	  <meta charset="UTF-8">
	    <link rel="stylesheet" href="./common/main.css">
            <title>新規登録（情報工学実験）</title>
      <p id="site">東横駅沿いイベント情報サイト</p>
      <FONT size="4"><h1>東横イベント検索くん</h1></FONT>

    </head>
    <div class="main_log">
        <div align="center">
    <BODY bgcolor="#FFFAF0" text="#000000">
        <h1>新規登録画面</h1>
<form id="loginForm" name="loginForm" action="<?php print($_SERVER['PHP_SELF']) ?>" method="POST">
            <fieldset class = "signup">
                <legend><h3>新規登録フォーム</h3></legend>
                <BR>
                <p>大文字小文字数字を含み４文字以上のパスワードでなければ登録することができません<p>
                  <BR>

                <div><font color="#ff0000"><?php echo $errorMessage ?></font></div>
                <div><font color="#0000ff"><?php echo $SignUpMessage ?></font></div>
                <label for="login_name"><span class = "span">ログインネーム</span></label><input type="login_name" id="login_name" name="login_name" value="" placeholder="ログインネームを入力">
                <br>
<br>
                <label for="real_name"><span class = "span">名前</span></label><input type="real_name" id="real_name" name="real_name" value="" placeholder="名前を入力">
<br>
<br>


                <label for="mail"><span class = "span">メール</span></label><input type="mail" id="mail" name="mail" value="" placeholder="メールを入力">
<br>
<br>
                <label for="password"><span class = "span">パスワード</span></label><input type="password" id="password" name="password" value="" placeholder="パスワードを入力">
                <br>
<br>
                <label for="password2"><span class = "span">パスワード(確認用)</span></label><input type="password" id="password2" name="password2" value="" placeholder="再度パスワードを入力">
                <br>
<br>
                <input type="submit" id="signUp" name="signUp" value="新規登録">
            </fieldset>
	    </form>
	    <br>
  <form action="Login.php">
            <input type="submit" value="戻る">
        </form>
      </div>
</div>
<div align="center">
<img src="./common/image/sample.jpeg"  width="800" height="80">
</div>
    </body>
</html>
