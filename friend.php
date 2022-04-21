<?php
    session_start();
    include_once("function.php");
    if(isset($_POST["user_id"])){
        $dbh=dbConnect();
        $sql="insert into friend (user_id,friend_id) values(:user,:friend)";
        $stmt= $dbh->prepare($sql);
        $stmt->bindValue(":user",$_SESSION["user_id"],pdo::PARAM_STR);
        $stmt->bindValue(":friend",$_POST["user_id"],pdo::PARAM_STR);
        $stmt->execute();
        $sql="insert into friend (user_id,friend_id) values(:user,:friend)";
        $stmt= $dbh->prepare($sql);
        $stmt->bindValue(":friend",$_SESSION["user_id"],pdo::PARAM_STR);
        $stmt->bindValue(":user",$_POST["user_id"],pdo::PARAM_STR);
        $stmt->execute();

        header("Location:index.php");
        exit();


    }else{
        header("Location:index.php");
        exit();
    }
?>