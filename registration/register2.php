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
        $_SESSION=array();
        if(isset($_COOKIE[session_name()])):
            setcookie(session_name(),'',time()-1000);
		endif;
        session_destroy();
        exit("正しくアクセスしてください。");
    endif;
?>

<!DOCTYPE html>
<html lang="ja">
<head>
	<meta charset="UTF-8">
	<title>会員登録 確認</title>
</head>
<body>
<?php
    if($_SERVER["REQUEST_METHOD"]!=="POST"):
        exit("直接アクセス禁止");
    endif;
    $errors=array();

    if(preg_match("/^[a-zA-Z0-9]{2,20}$/",$_POST['name'])):
		$name=$_POST['name'];
	else:
		$errors[]="ユーザー名を正しく入力して下さい";
	endif;

	if(preg_match("/^[a-zA-Z0-9]{6,12}$/",$_POST['pass'])):
		$pass=$_POST['pass'];
	else:
		$errors[]="パスワードを正しく入力して下さい";
	endif;

	if(preg_match("/^[a-zA-Z0-9]+[a-zA-Z0-9\._-]*@[a-zA-Z0-9_-]+\.[a-zA-Z0-9\._-]+$/",$_POST['mail'])):
		$mail=$_POST["mail"];
	else:
		$errors[]="メールアドレスを正しく入力してください";
	endif;

    if(count($errors)):
?>
		<ul>
<?php foreach($errors as $error): ?>
			<li>
<?php echo $error ?>
			</li>
<?php endforeach; ?>
			<li><a href="register1.php">登録画面に戻る</a></li>
		</ul>
<?php else: ?>
	<table>
		<tr>
			<td>ユーザー名</td>
			<td><?php echo $name; ?></td>
		</tr>
		<tr>
			<td>パスワード</td>
			<td><?php echo $pass; ?></td>
		</tr>
		<tr>
			<td>メールアドレス</td>
			<td><?php echo $mail; ?></td>
		</tr>
	</table>
	<p>この内容で登録してよろしいですか？</p>
	<form action="register3.php" method="post">
		<input type="hidden" name="name" value="<?php echo $name; ?>">
		<input type="hidden" name="pass" value="<?php echo $pass; ?>">
		<input type="hidden" name="mail" value="<?php echo $mail; ?>">
		<input type="hidden" name="token" value=<?php echo $_POST['token']; ?>>
		<input type="submit" value="登録">
		<a href="register1.php">戻る</a>
	</form>
<?php endif; ?>
</body>
</html>