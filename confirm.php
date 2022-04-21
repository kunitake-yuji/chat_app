<?php
    session_start();
    if(isset($_SESSION["user_id"])){
        header("Location:login.php");
        exit();
    }

    if(isset($_POST["user_id"]) and $_POST["user_pass1"]==$_POST["user_pass2"]){
        try{
            $dsn  = "mysql:dbname=b202206;host=localhost;charset=utf8";
            $user = "b2022";
            $pass = "dB4bApUK";
            $dbh = new PDO($dsn,$user,$pass);
            $query = "select `user_id` from users where user_id = :id";
            $stmt = $dbh-> prepare($query);
            $stmt -> bindValue(":id",$_POST["user_id"],pdo::PARAM_STR);
            $stmt -> execute();
            if($result = $stmt->fetch(PDO::FETCH_ASSOC)){
                $flg=false;
                header("Location:new_form.php");
                exit();
            }else{
                $flg=true;
            }
        }catch(PDOException $e){
            print("データベースの接続に失敗しました".$e->getMessage());
            die();
        }
    }else{
        header("Location:new_form.php");
        exit();
    }

?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <script src="js/jquery-3.6.0.min.js"></script>
    <title>チャット</title>
</head>
<body>
    <div id="container">
        <header>
            <h1>チャット</h1>
        </header>
        <div id="contents">
            <form action="regist.php" method="post" enctype="multipart/form-data">
                <table>
                    <caption>登録フォーム<br>下記の内容でよろしいですか？</caption>
                    <tr>
                        <td>登録ID</td>
                        <td><input type="text" name="user_id" value="<?= $_POST["user_id"]?>"></td>
                    </tr>
                    <tr>
                        <td>password</td>
                        <td><input type="password" name="user_pass1" value="<?= $_POST["user_pass1"]?>"></td>
                    </tr>
                    <tr>
                        <td>ニックネーム</td>
                        <td><input type="text" name="user_name" value="<?= $_POST["user_name"]?>"></td>
                    </tr>
                    <tr>
                        <td colspan="2"><input type="submit" value="登録"></td>
                    </tr>
                </table>
            </form>
        </div>
        <footer>

        </footer>
    </div>
</body>
</html>