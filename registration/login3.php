<?php
	session_start();
	header('X-FRAME-OPTIONS: SAMEORIGIN');
	$flag=0;

	if($_SERVER['REQUEST_METHOD']!=='POST'):
		$flag++;
	endif;session_regenerate_id(true);

	if(isset($_POST['name']) && isset($_POST['pass']) && isset($_POST['token'])):
		if(!(hash_equals($_POST['token'],$_SESSION['token']))):
			$flag++;
		endif;
	else:
		$flag++;
	endif;

	if($flag!==0):
	$_SESSION = array();
		if (isset($_COOKIE[session_name()])):
			setcookie(session_name(), '', time()-1000);
		endif;
		session_destroy();
		exit("正しくアクセスしてください");
	endif;
?>

<!DOCTYPE html>
<html lang="ja">
<head>
	<meta charset="UTF-8">
	<title>登録</title>
</head>
<body>
<?php
	if(!(preg_match("/^[a-zA-Z0-9]{2,20}$/",$_POST['name']) && preg_match("/^[a-zA-Z0-9]{6,12}$/",$_POST['pass']))):
?>
	<p>正しい入力がされていません</p>
	<p><a href='login2.php'>ログイン画面に戻る</a></p>
<?php
		exit();
	endif;
	
	$link=mysqli_connect("localhost","root","root");
	if(!$link):
		exit("データベースに接続できません".mysqli_connect_error());
	endif;

	mysqli_select_db($link,"login");
	mysqli_set_charset($link,"utf8");

	$sql=mysqli_prepare($link,"SELECT `name`,`pass` FROM `member` WHERE `name`=?");
	mysqli_stmt_bind_param($sql,'s',$_POST['name']);
	mysqli_stmt_execute($sql);
	mysqli_stmt_store_result($sql);
	if(mysqli_stmt_num_rows($sql)!=0):
		mysqli_stmt_bind_result($sql,$name,$pass);
		mysqli_stmt_fetch($sql);
		if($_POST["pass"]===$pass):
?>
	<p>ログインに成功しました</p><br>
    <form action="change1.php" method="post">
		<input type="hidden" name="name" value="<?php echo $_POST["name"]; ?>">
		<input type="hidden" name="pass" value="<?php echo $_POST["pass"]; ?>">
		<input type="hidden" name="token" value="<?php echo $_POST["token"]; ?>">
		<input type="submit" value="登録情報を変更する">
	</form>
<?php
		else:
?>
	<p>パスワードが違います</p><br>
	<p><a href='login2.php'>ログイン画面に戻る</a></p>
<?php
		endif;
	else:
?>
	<p>ユーザーが存在しません</p><br>
	<p><a href='login2.php'>ログイン画面に戻る</a></p>
<?php
	endif;
	mysqli_stmt_close($sql);
	mysqli_close($link);
?>
</body>
</html>
