<?php
	session_start();
	session_regenerate_id(true);
	header('X-FRAME-OPTIONS: SAMEORIGIN');
	$_SESSION['token']=uniqid('',true);
?>

<!DOCTYPE html>
<html lang="ja">
<head>
	<meta charset="UTF-8">
	<title>ログイン</title>
</head>
<body>
	<form action="delete2.php" method="post">
		ユーザー名<input type="text" name="name" size="20"><br>
		パスワード<input type="password" name="pass" size="12"><br>
		<input type="hidden" name="token" value="<?php echo $_SESSION['token']; ?>">
		<input type="submit" value="送信">
	</form>
</body>
</html>