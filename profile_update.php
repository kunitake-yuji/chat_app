<?php
    session_start();
    include_once("function.php");
    if(isset($_POST["user_name"])){
        $dbh=dbConnect();
            if($_FILES['user_img']['size'] === 0){
                $profile_comment=nl2br($_POST["user_profile"]);


                $sql="UPDATE users SET user_name = :user_name, user_profile = :user_profile WHERE user_id = :id;";
                $stmt = $dbh-> prepare($sql);
                $stmt -> bindValue(":user_name",$_POST["user_name"],pdo::PARAM_STR);
                $stmt -> bindValue(":user_profile",$profile_comment,pdo::PARAM_STR);
                $stmt -> bindValue(":id",$_SESSION["user_id"],pdo::PARAM_STR);
                $stmt -> execute();
                $_SESSION["user_name"] = $_POST["user_name"];
                $_SESSION["user_profile"] = $_POST["user_profile"];
                header("Location:index.php");
                exit();
    
            }else{
                $img_name = 'images/user_img/'.uniqid("",true).$_FILES['user_img']['name'];
                move_uploaded_file($_FILES['user_img']['tmp_name'], $img_name);
            


                $profile_comment=nl2br($_POST["user_profile"]);


                $sql="UPDATE users SET user_name = :user_name, user_profile = :user_profile, user_img = :user_img WHERE user_id = :id;";
                $stmt = $dbh-> prepare($sql);
                $stmt -> bindValue(":user_name",$_POST["user_name"],pdo::PARAM_STR);
                $stmt -> bindValue(":user_profile",$profile_comment,pdo::PARAM_STR);
                $stmt -> bindValue(":user_img",$img_name,pdo::PARAM_STR);
                $stmt -> bindValue(":id",$_SESSION["user_id"],pdo::PARAM_STR);
                $stmt -> execute();
                $_SESSION["user_name"] = $_POST["user_name"];
                $_SESSION["user_profile"] = $_POST["user_profile"];
                $_SESSION["user_img"] = $img_name;
                header("Location:index.php");
                exit();
            }

    }else{
        header("Location:index.php");
        exit();
    }

?>