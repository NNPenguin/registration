<?php
    session_start();
    header('X-FRAME-OPTIONS: SAMEORIGIN');
    if($_SERVER['REQUEST_METHOD']!=='POST'):
		exit();
	endif;
        session_regenerate_id(true);

    if(!isset($_POST['token'])):
		exit();
	endif;

    if($_SERVER["REQUEST_METHOD"]!=="POST"):
        exit("直接アクセス禁止");
    endif;
?>

<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <title>登録情報の変更</title>
    </head>
    <body>
        <p>変更前<br>
        ユーザー名：<?php echo $_POST["name"]; ?><br>
        パスワード：<?php echo $_POST["pass"]; ?><br>
        メールアドレス：<?php ?>
        </p>

        <p>変更後
        <form action="change2.php" method="post">
            <input type="hidden" name="name" value="<?php echo $_POST["name"]; ?>">
            <input type="hidden" name="pass" value="<?php echo $_POST["pass"]; ?>">
            <input type="hidden" name="token" value="<?php echo $_POST["token"]; ?>">
            ユーザー名：<input type="text" name="newname" size="20"><br>
            パスワード：<input type="password" name="newpass" size="12"><br>
            メールアドレス：<input type="email" name="newmail"><br>
            <input type="submit" value="決定">
        </form>
        </p>
    </body>
</html>