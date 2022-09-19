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
        <title>会員登録</title>
    </head>
    <body>
        <form action="register2.php" method="post">
            ユーザー名<input type="text" name="name" size="20">（半角英数２０文字以内）<br>
            パスワード<input type="password" name="pass" size="12">（半角英数６～１２文字）<br>
            メールアドレス<input type="email" name="mail"><br>
            <input type="hidden" name="token" value="<?php echo $_SESSION['token'] ?>">
            <input type="submit" value="送信">
        </form>
    </body>
</html>