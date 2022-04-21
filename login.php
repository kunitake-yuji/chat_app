<?php
    session_start();
    if(isset($_SESSION["user_id"])){
        header("Location:index.php");
        exit();
    }
    if(isset($_POST["login_id"])){
        try{
            $dsn  = "mysql:dbname=b202206;host=localhost;charset=utf8";
            $user = "b2022";
            $pass = "dB4bApUK";
            $dbh = new PDO($dsn,$user,$pass);
            $query = "SELECT * FROM users where `user_id` = :id";
            $stmt = $dbh-> prepare($query);
            $stmt -> bindValue(":id",$_POST["login_id"],pdo::PARAM_STR);
            $stmt -> execute();
            if($result = $stmt->fetch(PDO::FETCH_ASSOC)){
                if(password_verify($_POST["login_pass"],$result["password"])){
                    $_SESSION["user_id"] = $result["user_id"];
                    $_SESSION["user_name"] = $result["user_name"];
                    $_SESSION["user_profile"] = $result["user_profile"];
                    $_SESSION["user_img"] = $result["user_img"];
                    header("Location:index.php");
                    exit();
                }else{
                    echo $_POST["login_pass"];
                    // header("Location:login.php");
                    // exit();
                }
            }else{
                echo $_POST["login_pass"];
                // header("Location:login.php");
                // exit();
            }
        }catch(PDOException $e){
            print("データベースの接続に失敗しました".$e->getMessage());
            die();
        }
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
            <h1>kunicchinry</h1>
        </header>
        <div id="contents">
            <div id="login_form">
                <form action="login.php" method="post">
                    <table>
                        <caption>ログインしてください。</caption>
                        <tr>
                            <td>ID</td>
                            <td><input type="text" name="login_id" required></td>
                        </tr>
                        <tr>
                            <td>password</td>
                            <td><input type="password" name="login_pass" required></td>
                        </tr>
                            <td colspan="2"><input type="submit" value="login"></td>
                        </tr>
                            <td colspan="2"><a href="new_form.php">新規登録はこちら</a></td>
                        </tr>
                    </table>
                </form>
            </div>
        </div>
        <footer>
        <nav>
            <ul>
            </ul>
        </nav>
        </footer>
    </div>
</body>
</html>