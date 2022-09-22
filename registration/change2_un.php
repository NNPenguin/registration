<?php
    $flag=0;
    $errors=array();

    if($_SERVER["REQUEST_METHOD"]!=="POST"):
        exit("直接アクセス禁止");
    endif;

    $link=mysqli_connect("localhost","root","root");
	if(!$link):
		exit("データベースに接続できません".mysqli_connect_error());
	endif;

	mysqli_select_db($link,"login");
	mysqli_set_charset($link,"utf8");
    
    var_dump (is_string($_POST["newname"]));
    if(preg_match("/^[a-zA-Z0-9]{2,20}$/",$_POST['newname'])):
        $sql=mysqli_prepare($link,"SELECT `name` FROM `member` WHERE `name`=?");
        mysqli_stmt_bind_param($sql,'s',$_POST['newname']);
        $result=mysqli_stmt_execute($sql);
        if(!$result):
            mysqli_stmt_close($sql);
            mysqli_close($link);
            exit("サーバーエラー");
        endif;
        mysqli_stmt_store_result($sql);
        if(mysqli_stmt_num_rows($sql)!=0):
            $errors["newname"]="同じ名前が既に存在します";
        endif;
        mysqli_stmt_close($sql);
        $name=$_POST["newname"];
    else:
        $name=$_POST["name"];
    endif;

    if(isset($_POST['newpass'])):
        if(preg_match("/^[a-zA-Z0-9]{6,12}$/",$_POST['newpass'])):
            $pass=$_POST['newpass'];
        else:
            $errors[]="変更後のパスワードを正しく入力して下さい";
        endif;
    endif;

    if(isset($_POST['newmail'])):
        if(preg_match("/^[a-zA-Z0-9]+[a-zA-Z0-9\._-]*@[a-zA-Z0-9_-]+\.[a-zA-Z0-9\._-]+$/",$_POST['newmail'])):
            $mail=$_POST["newmail"];
        else:
            $errors[]="変更後のメールアドレスを正しく入力してください";
        endif;
    endif;
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>登録情報 変更完了</title>
    </head>
    <body> 

<?php
    if(count($errors)===0):
        $sql = mysqli_prepare($link,'UPDATE `member` set `name`=?,`pass`=?,`mail`=? WHERE `name`=?');
		mysqli_stmt_bind_param($sql,'ssss',$name,$pass,$mail,$_POST["name"]);
		mysqli_stmt_execute($sql);
		mysqli_stmt_close($sql);
?>
        <p>登録変更完了<br>
        ユーザー名：<?php echo $name; ?><br>
        パスワード：<?php echo $pass; ?><br>
        メールアドレス：<?php echo $mail; ?><br>
        </p>
        <a href="login2.php">ログイン画面に戻る</a>
<?php 
    else:
?>
		<ul class="error_list">
<?php foreach($errors as $error): ?>
			<li>
<?php echo htmlspecialchars($error,ENT_QUOTES,"UTF-8") ?>
			</li>
<?php endforeach; ?>
            <li><a href="login2.php">ログイン画面に戻る</a></li>
		</ul>
<?php
    endif;
?>
    </body>
</html>
