<?php
session_start();
if (!isset($_SESSION["USERID"]) && !isset($_SESSION["ID"])) {
    header("Location: Logout.php");
    exit;
}
$errorMessage = "";
$registMessage = "";
$conn = pg_connect ("host=localhost dbname=j141011m user=j141011m");
$query = "SELECT * FROM station";
$query1 = "SELECT * FROM event_group";
$result = pg_prepare ($conn, "my_query", $query);
$result1 = pg_prepare ($conn, "my_query1", $query1);
$result = pg_execute ($conn, "my_query", array());
$result1 = pg_execute ($conn, "my_query1", array());


 if (!($_SERVER["REQUEST_METHOD"] == "POST")) {
   $first = false;
 }
 else {
   $first = true;
 }
if (isset($_POST["confirm"])) {
    if (empty($_POST["title"])) {
      $errorMessage = 'タイトルが入力されていません。';

  } else if (empty($_POST["date"])) {
    $errorMessage = '日時が入力されていません。';
  } else if (empty($_POST["detail"])) {
    $errorMessage = 'イベントの詳細が入力されていません。';
    }
    else if (empty($_POST["capacity"])) {
      $errorMessage = '店員が入力されていません';
      }
   if (!empty($_POST["title"]) && !empty($_POST["event_group"]) && !empty($_POST["date"]) && !empty($_POST["detail"])) {


   }
 }
?>
<!doctype html>
<html>
    <head>
            <meta charset="UTF-8">
	    <link rel="stylesheet" href="./common/main.css">
      <link rel="stylesheet" type="text/css" href="./jquery.datetimepicker.css"/ >


            <title>イベント登録画面（情報工学実験）</title>
            <p id="site">東横駅沿いイベント情報サイト</p>
            <FONT size="4"><h1>東横イベント検索くん</h1></FONT>
            <h4 id = "log"><a href="Logout.php">ログアウト</a> &nbsp; &nbsp; &nbsp; <a href="password.php">パスワード変更</a></h4>

    </head>
    <body>
      <div class="main">
        <div align="center">
        <h1>イベント登録画面</h1>

        <form id="loginForm" name="loginForm" action="confirm.php" method="POST">
            <!--<fieldset class="login">-->
              <fieldset class="login">
                <legend><h3>イベント登録フォーム</h3></legend>

                <div><font color="#0000ff"><?php echo $errorMessage ?></font></div>

                <div><font color="#0000ff"><?php echo $registMessage ?></font></div>


                <table class="table11">
                  <tbody>
                  <col span="1" style="background-color:#ffffff">
                  <col width="200">
                <tr>

                  <th>イベントのタイトル：</th>
                  <td><input type="text" name="title" size="20" value="<?php if($first){?><?php echo $_POST["title"]; ?><?php } else {?><?php }?>"></td>
                </tr>
                <tr>
                  <th>イベントの種類 ：</th>
                <td><?php echo '<select name="event_group">';

                while ($row1 = pg_fetch_array($result1, NULL, PGSQL_ASSOC)) {
                  $menu = $row1['group_name'];
                  echo '<option value='.$row1['id'];
                  if ($first) {
                    if ($row1['id'] == $_POST['event_group']){
                    echo ' selected >';
                  }else {
                    echo '>';
                  }
                  }
                  else{
                    echo '>';
                  }
                  echo $menu.'</option>';
                }
                echo '</select>';
                   ?>
                 </td>
                </tr>
                <tr>
                  <th> 日時　：</th>
                  <td><input id="datetimepicker" name="date" <?php if($first){echo ' value='.$_POST['date']; } ?>></td>
                </tr>
                <tr>
                  <th>最寄駅　：</th>
                  <td>
                  <?php
                  echo '<select name="station">';
                  while ($row2 = pg_fetch_array($result, NULL, PGSQL_ASSOC)) {
                    $menu = $row2['name'];
                    echo '<option value='.$row2['id'];
                    if ($first) {
                      if ($row2['id'] == $_POST['station']){
                      echo ' selected >';
                    }else {
                      echo '>';
                    }
                    }
                    else{
                      echo '>';
                    }
                    echo $menu.'</option>';
                  }
                  echo '</select>';
                  ?>
                </td>
              </tr>　
              <tr>
                <th>定員：</th>
                <td>

              <?php
              echo '<select name="capacity">';

              for ($i = 5; $i <= 100; $i+=5) {
                echo '<option value='.$i;
                if ($first) {
                  if ($i == $_POST['capacity']){
                  echo ' selected >';
                }else {
                  echo '>';
                }
                }
                else{
                  echo '>';
                }
                echo $i.'人</option>';
              }
              echo '</select>';
              ?>
            </td>
          </tr>



              <tr>
                <th>イベントの詳細　：</th>
                  <td>
                  <textarea name="detail" rows="8" cols="80"><?php if($first){?><?php echo $_POST["detail"]; ?><?php }else{?><?php }?></textarea></td>
                </tr>
              </tbody>
                </table>
                <BR>
                <input type="submit" id="regist" name="confirm" value="確認ページへいく">
                <input type="button" onclick="location.href='top.php' "value="トップページに戻る">
            </fieldset>
        </form>
        <br>

      </div>
  </div>

  <script src="./jquery-3.1.1.min.js"></script>
  <script src="./jquery.datetimepicker.full.min.js"></script>
  <script>
$(function(){
$('#datetimepicker').datetimepicker();
  format: 'Y-m-d H:i:s'
});
</script>
<div align="center">
<img src="./common/image/sample.jpeg"  width="800" height="80">
</div>
    </body>
</html>
