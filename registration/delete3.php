<!DOCTYPE html>
<html lang="ja">
<head>
	<meta charset="UTF-8">
	<title>退会完了</title>
</head>
<body>
<?php
//SESSIONのチェックがある場合はREQUEST＿METHODは記載不要
	if($_SERVER["REQUEST_METHOD"]!=="POST"):
		exit("直接アクセス禁止");
	endif;

	$link=mysqli_connect("localhost","root","root");
	if(!$link){
		exit("データベースに接続できません".mysqli_connect_error());
	}
//入力ミスチェック
//issetしてからpregmatchしたほうが安定する
	if(!(isset($_POST['pass']) && preg_match("/^[a-zA-Z0-9]{6,12}$/",$_POST['pass']))):
?>
	<p>正しく入力してください</p>  
<?php
    else:
    mysqli_select_db($link,"login");
    mysqli_set_charset($link,"utf8");

    $sql=mysqli_prepare($link,"SELECT `pass` FROM `member` WHERE `name`=?");
    mysqli_stmt_bind_param($sql,'s',$_POST['name']);
    mysqli_stmt_execute($sql);
    mysqli_stmt_store_result($sql);
    if(mysqli_stmt_num_rows($sql)!=0):
        mysqli_stmt_bind_result($sql,$pass);
        mysqli_stmt_fetch($sql);
        if($_POST["pass"]===$pass):
            mysqli_stmt_close($sql);//新しいsql文の前に一度stmtをクローズする。しないと新しいsql文に入れない。
            $sql = mysqli_prepare($link,'DELETE FROM `member` WHERE `name`=?');
            mysqli_stmt_bind_param($sql,'s',$_POST['name']);
            mysqli_stmt_execute($sql);
			session_destroy();
?>
<p>退会が完了しました</p>
<?php	else: ?>
	<p>パスワードが違います</p>
<?php
			endif;
//44行目のif文と54～57行目のelseブロックとendifはsessionで行ってる場合不要
//今回は入力してるので入力ミス確認のため
		else:
?>
	<p>ユーザーが存在しません</p>
<?php
		endif;
		mysqli_stmt_close($sql);
	endif;	
	mysqli_close($link);
?>	
	<p><a href='login1.php'>ログイン画面に戻る</a></p>
</body>
</html>