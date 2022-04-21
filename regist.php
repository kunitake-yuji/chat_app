<?php
    session_start();
    if(isset($_SESSION["user_id"])){
        header("Location:login.php");
        exit();
    }

    if(isset($_POST["user_id"])){
            try{
                $dsn  = "mysql:dbname=b202206;host=localhost;charset=utf8";
                $user = "b2022";
                $pass = "dB4bApUK";
                $dbh = new PDO($dsn,$user,$pass);
                $query = "insert into users (`user_id`,`password`,`user_name`,`user_img`) values (:id, :pass, :name, 'images/free.jpg')";
                $stmt = $dbh-> prepare($query);
                $hash=password_hash($_POST["user_pass1"],PASSWORD_DEFAULT);
                $stmt -> bindValue(":id",$_POST["user_id"],pdo::PARAM_STR);
                $stmt -> bindValue(":pass",$hash,pdo::PARAM_STR);
                $stmt -> bindValue(":name",$_POST["user_name"],pdo::PARAM_STR);
                $stmt -> execute();
                header("Location:login.php");
            }catch(PDOException $e){
                print("データベースの接続に失敗しました".$e->getMessage());
                die();
            }
            
    }

?>