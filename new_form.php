<?php
    session_start();
    if(isset($_SESSION["user_id"])){
        header("Location:login.php");
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
            <h1>kunicchinry</h1>
        </header>
        <div id="contents">
            <div id="login_form">
                <form action="confirm.php" method="post" enctype="multipart/form-data">
                    <table>
                        <caption>登録フォーム</caption>
                        <tr>
                            <td>登録ID(必須)</td>
                            <td><input type="text" name="user_id" required></td>
                        </tr>
                        <tr>
                            <td>password(必須)</td>
                            <td><input type="password" name="user_pass1" required></td>
                        </tr>
                        <tr>
                            <td>password(確認用)</td>
                            <td><input type="password" name="user_pass2" required></td>
                        </tr>
                        <tr>
                            <td>ニックネーム</td>
                            <td><input type="text" name="user_name" required></td>
                        </tr>
                        <tr>
                            <td colspan="2"><input type="submit" value="確認"></td>
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