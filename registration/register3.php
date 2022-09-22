<?php
	session_start();
	session_regenerate_id(true);
	header('X-FRAME-OPTIONS: SAMEORIGIN');
	$flag=0;

	if($_SERVER['REQUEST_METHOD']!=='POST'):
		$flag++;
	endif;

	if(isset($_POST['name']) && isset($_POST['pass']) && isset($_POST['mail']) && isset($_POST['token'])):
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

	$link=mysqli_connect("localhost","root","root");
	if(!$link):
		exit("データベースに接続できません".mysqli_connect_error());
	endif;

	mysqli_select_db($link,"login");
	mysqli_set_charset($link,"utf8");

	$errors=array();

	if(preg_match("/^[a-zA-Z0-9]{2,20}$/",$_POST['name'])):
		$sql=mysqli_prepare($link,"SELECT `name`,`pass` FROM `member` WHERE `name`=?");
		mysqli_stmt_bind_param($sql,'s',$_POST['name']);
		$result=mysqli_stmt_execute($sql);
		if(!$result):
			mysqli_stmt_close($sql);
			mysqli_close($link);
			exit("サーバーエラー");
		endif;
		mysqli_stmt_store_result($sql);
		if(mysqli_stmt_num_rows($sql)!=0):
			$errors["name"]="同じ名前が既に存在します";
		endif;
		mysqli_stmt_close($sql);
		$name=$_POST["name"];
	else:
		$errors[]="ユーザーネームを正しく入力して下さい";
	endif;

	if(preg_match("/^[a-zA-Z0-9]{6,12}$/",$_POST['pass'])):
		$pass=$_POST['pass'];
	else:
		$errors[]="パスワードを正しく入力して下さい";
	endif;

	if(preg_match("/^[a-zA-Z0-9]+[a-zA-Z0-9\._-]*@[a-zA-Z0-9_-]+\.[a-zA-Z0-9\._-]+$/",$_POST["mail"])):
		$mail=$_POST["mail"];
	else:
		$errors["mail"]="メールアドレスを正しく入力してください";
	endif;

	if(count($errors)===0):
		$sql = mysqli_prepare($link,'INSERT INTO `member` (`name`,`pass`,`mail`) VALUES (?,?,?)');
		mysqli_stmt_bind_param($sql,'sss',$name,$pass,$mail);
		mysqli_stmt_execute($sql);
		mysqli_stmt_close($sql);
	endif;
	mysqli_close($link);
?>
<!DOCTYPE html>
<html lang="ja">
<head>
	<meta charset="UTF-8">
	<title>登録</title>
</head>
<body>
<?php if (count($errors)): ?>
		<ul class="error_list">
<?php foreach($errors as $error): ?>
			<li>
<?php echo htmlspecialchars($error,ENT_QUOTES,"UTF-8") ?>
			</li>
<?php endforeach; ?>
			<li><a href="register1.php">登録画面に戻る</a></li>
		</ul>
<?php else: ?>
		<p>登録完了しました</p>
		<a href="login1.php">ログイン画面に戻る</a>
<?php endif; ?>
</body>
</html>
