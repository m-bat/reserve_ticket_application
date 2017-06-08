<?php
session_start();

// ログイン状態チェック
if (!isset($_SESSION["USERID"]) && !isset($_SESSION["ID"])) {
    header("Location: Logout.php");
    exit;
}


$conn = pg_connect ("host=localhost dbname=j141011m user=j141011m");
$query = "SELECT e.id, e.title, to_char(e.regist_date, 'YYYY年MM月DD日HH24時MI分'),  g.group_name FROM event e, event_group g WHERE e.event_group = g.id ORDER BY e.regist_date DESC LIMIT 10";
$query1 = "SELECT * FROM event_group";
$query2 = "SELECT * FROM station";
$result = pg_prepare ($conn, "my_query", $query);
$result1 = pg_prepare ($conn, "my_query1", $query1);
$result2 = pg_prepare ($conn, "my_query2", $query2);
$result = pg_execute ($conn, "my_query", array());
$result1 = pg_execute ($conn, "my_query1", array());
$result2 = pg_execute ($conn, "my_query2", array());


?>

<!doctype html>
<HTML>
<HEAD>
<TITLE>東横イベント検索くん（情報工学実験）</TITLE>

<meta charset="UTF-8">
 <link rel="stylesheet" href="./common/main.css">
</HEAD>
<BODY bgcolor="#FFFAF0" text="#000000">

<header id="pageHead">
<p id="site">東横駅沿いイベント情報サイト</p>
<FONT size="4"><h1>東横イベント検索くん</h1></FONT>
　　　　このサイトは慶應義塾大学理工学部の情報工学実験のために作成したものです。実際にイベントを管理することはできません。
<h4 id = "log"><a href="Logout.php">ログアウト</a> &nbsp; &nbsp; &nbsp; <a href="password.php">パスワード変更</a></h4>
</header>
<link rel="stylesheet" type="text/css" href="calendar/calendar.css">

<div class="main">
<div align="center">

</div>
<div class="blocka">

<h3>ようこそ<u><?php echo htmlspecialchars($_SESSION["USERID"], ENT_QUOTES); ?></u>さん</h3>
 <p>このサイトでは、自分で企画したイベントを投稿することができます。また他のユーザーが主催しているイベントに参加申し込みをすることができます。実際に参加したイベントに関しましては、レビューをしていただき、他のユーザーにフィードバックをしましょう！</p>
  <section class="information">
    <div style="padding: 5px; margin 10px; border: 5px double #333333; border-radius: 10px;">
     <h3>最近投稿されたイベント（新着イベントをチェック!）</h3>
     <p>最新の10件を表示しています</p>
     <div style="width: 260px; height: 300px; overflow: auto;">
     <ul>
       <?php
       while ($row = pg_fetch_array($result, NULL, PGSQL_ASSOC)) {
         echo '<li><time>'.$row['to_char'].'</time></li><li><a href="./detail.php?event_id='.$row['id'].'">'.$row['title'].'</a></li>';
     }
       ?>
     </ul>
   </div>
   </div>
 </section>

 <BR>

<fieldset class="login1">
<FORM name="form1" method="post" action="list.php">
  <b>イベント一覧を表示する：</b><BR>
  <p>投稿されているすべてのイベントを閲覧することができます。</p><p>終了したイベントに関してはレビューを見ることできますので、ぜひ参考にしてください</p>
  <INPUT type="submit" value="一覧表示" style="HEIGHT:28px">
  <BR>
</FORM>
</fieldset>
<BR>

  <fieldset class="login2">
  <FORM name="form1" method="post" action="search_purpose.php">
    <b>目的から探す：（複数選択可）</b>
    <p>目的に合わせてイベントを検索することができます。</p>

      <?php
      while ($row1 = pg_fetch_array($result1, NULL, PGSQL_ASSOC)) {
        echo '<input type="checkbox" name="purpose[]" value="'.$row1['id'].'"><b>'.$row1['group_name'].' </b>&nbsp;';
      }
        ?>

      <BR>

    <INPUT type="submit" value="検索" style="HEIGHT:28px">
    <BR>
  </FORM>
  </fieldset>

<BR>

  <fieldset class="login3">
  <FORM name="form2" method="post" action="search_station.php">
    <b> 駅沿いから探す：（複数選択可）</b>
    <p>開催場所からイベントを検索することができます。</p>
      <?php
      while ($row2 = pg_fetch_array($result2, NULL, PGSQL_ASSOC)) {
        echo '<input type="checkbox" name="station[]" value="'.$row2['id'].'"><b>'.$row2['name'].' </b>&nbsp;';
      }
        ?>

      <BR>

    <INPUT type="submit" value="検索" style="HEIGHT:28px">
    <BR>
  </FORM>
  </fieldset>

<BR>


<BR><BR>
</div>

<div class="blockb">
  <section class="regist">
    <div style="padding: 30px; margin 0px; border: 3px double #333333; border-radius: 10px;">
    <form id="keyword" name="keyword" action="search.php" method="GET">
      <h4>キーワードで検索</h4>
      <p>キーワードからイベントを検索することができます</p><br>
      <input type="text" id="keyword" name="keyword" value="" placeholder="キーワードを入力">
      <input type="submit" value="検索">
    </form>

    <FORM name="form3" method="get" action="mypage.php">
      <h4> 参加申し込みをしているイベント</h4>
      <p>現在参加申し込みをしているイベントを確認することができます。
      また終了したイベントについてはぜひ<font color="red">評価</font>と<font color="red">コメント</font>をお願いいたします。
    みなさまのご意見お待ちしております！　イベントを企画してくださった幹事さんも喜びますよ！</p><br>
      <INPUT type="submit" value="ページ移動" style="HEIGHT:28px">
      <BR>
    </FORM>

     <h4>イベントを投稿しよう！</h4>
     <div style="width: 260px; margin 0px; height: 300px; overflow: auto;">
       <fieldset class="regist_event">
       <FORM name="form1" method="get" action="create_regist.php">
         <p>自分の企画したイベントを投稿することができます。</p>
         <p>投稿して参加者を募りイベントを成功させましょう!</p>
         <INPUT type="submit" value="イベントを登録する" style="HEIGHT:28px">
       </FORM>
       </fieldset>


     </div>
</div>
 </section>

</div>
</div>
<div align="center">
<img src="./common/image/sample.jpeg"  width="800" height="80">
</div>

</BODY>
</HTML>
