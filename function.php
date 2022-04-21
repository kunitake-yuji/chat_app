<?php
    function dbConnect() {
        $dsn  = "mysql:dbname=b202206;host=localhost;charset=utf8";
        $user = "b2022";
        $pass = "dB4bApUK";
        $dbh = new PDO($dsn,$user,$pass);
        return $dbh;
    }
    function dbSelectUser($dbh,$id){
        $sql="select * from users where user_id = :id";
        $stmt = $dbh-> prepare($sql);
        $stmt -> bindValue(":id",$id,pdo::PARAM_STR);
        $stmt -> execute();
        return $stmt -> fetch(pdo::FETCH_ASSOC);
    }
?>