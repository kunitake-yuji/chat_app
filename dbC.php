<?php
    session_start();
    
    date_default_timezone_set('Asia/Tokyo');

    function dbConnect() {
        $dsn  = "mysql:dbname=b202206;host=localhost;charset=utf8";
        $user = "b2022";
        $pass = "dB4bApUK";
        $dbh = new PDO($dsn,$user,$pass);
        return $dbh;
    }
    class dbC{

        private $dbh;

        function __construct($pdo){
            $this->dbh=$pdo;
        }

        function Profile($user){

            $sql=   "SELECT user_id , user_name , user_profile , user_img 
                    FROM users 
                    WHERE user_id = :id";
        
            $stmt = $this->dbh -> prepare($sql);
            $stmt -> bindValue(":id",$user,pdo::PARAM_STR);
            $stmt -> execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }

        function allTalk(){

            $sql=   "SELECT all_talk.* , users.user_name , users.user_img 
                    FROM all_talk LEFT JOIN users ON all_talk.user_id = users.user_id 
                    ORDER BY datetime asc";

            $stmt = $this->dbh -> prepare($sql);
            $stmt -> execute();
        
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        function allTalkDate(){
            $sql=   "SELECT MAX(datetime) as datetime 
                    FROM all_talk";
            
            $stmt= $this->dbh -> prepare($sql);
            $stmt -> execute();
            return $stmt -> fetch(PDO::FETCH_ASSOC);
        }

        function allTalkCheck($datetime){
            $sql=   "SELECT all_talk.* , users.user_name , users.user_img 
                    FROM all_talk LEFT JOIN users ON all_talk.user_id = users.user_id 
                    WHERE all_talk.datetime > :datetime 
                    ORDER BY datetime asc";
            $stmt= $this->dbh ->prepare($sql);
            $stmt->bindValue(":datetime",$datetime);
            $stmt->execute();
            return $stmt ->fetchAll(PDO::FETCH_ASSOC);
        }

        function talk($user,$target){

            $sql=   "SELECT talk.* , users.user_name , users.user_img 
                    FROM talk 
                    LEFT JOIN users ON talk.user_id = users.user_id 
                    WHERE ( talk.user_id= :user AND talk.target_id= :target) or (talk.user_id= :target and talk.target_id= :user) 
                    ORDER BY datetime";

            $stmt = $this->dbh -> prepare($sql);
            $stmt -> bindValue(":user",$user,pdo::PARAM_STR);
            $stmt -> bindValue(":target",$target,pdo::PARAM_STR);
            $stmt -> execute();
        
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        function talkDate($user,$target){

            $sql=   "SELECT MAX(datetime) as datetime 
                    FROM talk 
                    WHERE (user_id= :user AND `target_id`= :target) or (user_id= :target and target_id= :user) ";

            $stmt = $this->dbh -> prepare($sql);
            $stmt -> bindValue(":user",$user,pdo::PARAM_STR);
            $stmt -> bindValue(":target",$target,pdo::PARAM_STR);
            $stmt -> execute();
        
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }

        function talkCheck($user,$target,$datetime){

            $sql=   "SELECT talk.* , users.user_name , users.user_img 
                    FROM talk 
                    LEFT JOIN users ON talk.user_id = users.user_id 
                    WHERE ( talk.user_id= :user AND talk.target_id= :target AND talk.datetime > :datetime) or (talk.user_id= :target AND talk.target_id= :user AND talk.datetime > :datetime) 
                    ORDER BY datetime";

            $stmt = $this->dbh -> prepare($sql);
            $stmt -> bindValue(":user",$user,pdo::PARAM_STR);
            $stmt -> bindValue(":target",$target,pdo::PARAM_STR);
            $stmt -> bindValue(":datetime",$datetime);
            $stmt -> execute();
        
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }


        function friendList($user){

            $sql=   "SELECT friend.friend_id , users.user_name AS 'friend_name' , users.user_img AS 'friend_img' 
                    FROM friend LEFT JOIN users ON friend.friend_id = users.user_id 
                    WHERE friend.user_id = :id 
                    ORDER BY friend_no desc";

            $stmt = $this->dbh -> prepare($sql);
            $stmt -> bindValue(":id",$user,pdo::PARAM_STR);
            $stmt -> execute();
        
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        function tweet(){
            $sql=   "SELECT tweet.*,users.user_img ,users.user_name 
                    FROM tweet LEFT JOIN users ON tweet.user_id = users.user_id 
                    WHERE tweet.user_id IN(
                        SELECT friend_id 
                        FROM friend 
                        WHERE user_id = :id
                    )
                    OR tweet.user_id = :id
                    ORDER BY datetime desc";

            $stmt = $this->dbh -> prepare($sql);
            $stmt -> bindValue(":id",$_SESSION["user_id"],pdo::PARAM_STR);
            $stmt -> execute();
        
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        function tweetCheckNo(){
            $sql=   "SELECT max(tweet.tweet_no) as tweet_no
                    FROM tweet LEFT JOIN users ON tweet.user_id = users.user_id 
                    WHERE tweet.user_id IN(
                        SELECT friend_id 
                        FROM friend 
                        WHERE user_id = :id
                    )
                    OR tweet.user_id = :id
                    ORDER BY datetime desc";

            $stmt = $this->dbh -> prepare($sql);
            $stmt -> bindValue(":id",$_SESSION["user_id"],pdo::PARAM_STR);
            $stmt -> execute();
        
            return $stmt->fetch(PDO::FETCH_ASSOC);

        }

        function tweetUp($no){
            $sql=   "SELECT tweet.*,users.user_img ,users.user_name 
                    FROM tweet LEFT JOIN users ON tweet.user_id = users.user_id 
                    WHERE (tweet.user_id IN(
                        SELECT friend_id 
                        FROM friend 
                        WHERE user_id = :id
                    ) AND tweet.tweet_no > :no)
                    OR (tweet.user_id = :id AND tweet.tweet_no > :no )
                    ORDER BY tweet.tweet_no";

            $stmt = $this->dbh -> prepare($sql);
            $stmt -> bindValue(":id",$_SESSION["user_id"],pdo::PARAM_STR);
            $stmt -> bindValue(":no",$no);
            $stmt -> execute();
        
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        function userSearch($keyword){

            $sql=   "SELECT user_id , user_name , user_img
                    FROM users
                    WHERE user_name LIKE :keyword 
                    AND user_id <> :id 
                    AND user_id NOT IN(
                        SELECT friend_id 
                        FROM friend 
                        WHERE user_id = :id
                    )";

            $stmt = $this->dbh-> prepare($sql);
            $word="%". $keyword . "%";
            $stmt -> bindValue(":keyword",$word);
            $stmt -> bindValue(":id",$_SESSION["user_id"]);
            $stmt -> execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);

        }

        function allTalkUp($user,$text){
            $comment=nl2br($text);
            $datetime=date("Y-m-d H:i:s");
            $sql="INSERT INTO all_talk(`user_id`, `comment`, `datetime`) VALUES (:id, :comment, :date)";
            $stmt=$this->dbh->prepare($sql);
            $stmt -> bindValue(":id",$user,pdo::PARAM_STR);
            $stmt -> bindValue(":comment",$comment,pdo::PARAM_STR);
            $stmt -> bindValue(":date",$datetime);
            $stmt -> execute();
            $a="seikoui";
            return $a;
        }

        function talkUp($user,$target_user,$text){
            $comment=nl2br($text);
            $datetime=date("Y-m-d H:i:s");
            $sql="INSERT INTO talk(`user_id`, `target_id`, `comment`, `datetime`) VALUES (:id, :target_id,:comment, :date)";
            $stmt=$this->dbh->prepare($sql);
            $stmt -> bindValue(":id",$user,pdo::PARAM_STR);
            $stmt -> bindValue(":target_id",$target_user,pdo::PARAM_STR);
            $stmt -> bindValue(":comment",$comment,pdo::PARAM_STR);
            $stmt -> bindValue(":date",$datetime);
            $stmt -> execute();
        }

        function talkList($user){
            $sql=<<<SQL
                SELECT talk.* , JOIN1.user_name,JOIN1.user_img,JOIN2.user_name AS target_name,JOIN2.user_img AS target_img
                FROM talk
                LEFT JOIN users AS JOIN1
                ON talk.user_id = JOIN1.user_id
                LEFT JOIN users AS JOIN2
                ON talk.target_id = JOIN2.user_id
                WHERE talk_no IN (
                    SELECT max(talk_no) AS dt
                    FROM (
                        SELECT 
                        CASE WHEN user_id>target_id THEN user_id ELSE target_id END AS t1,
                        CASE WHEN user_id>target_id THEN target_id ELSE user_id END AS t2,
                        talk_no
                        FROM talk
                        WHERE user_id = :id OR target_id = :id
                    ) AS T
                    GROUP BY T.t1, T.t2
                )
                ORDER BY talk.talk_no desc
            SQL; 
            $stmt=$this->dbh->prepare($sql);
            $stmt -> bindValue(":id",$user,pdo::PARAM_STR);
            $stmt -> execute();
            return $stmt->fetchAll(pdo::FETCH_ASSOC);
        }

        function talkListCheck($user,$checkNo){
            $sql=<<<SQL
                SELECT talk.* , JOIN1.user_name,JOIN1.user_img,JOIN2.user_name AS target_name,JOIN2.user_img AS target_img
                FROM talk
                LEFT JOIN users AS JOIN1
                ON talk.user_id = JOIN1.user_id
                LEFT JOIN users AS JOIN2
                ON talk.target_id = JOIN2.user_id
                WHERE talk_no > :no AND talk_no IN (
                    SELECT max(talk_no) AS dt
                    FROM (
                        SELECT 
                        CASE WHEN user_id>target_id THEN user_id ELSE target_id END AS t1,
                        CASE WHEN user_id>target_id THEN target_id ELSE user_id END AS t2,
                        talk_no
                        FROM talk
                        WHERE user_id = :id OR target_id = :id
                    ) AS T
                    GROUP BY T.t1, T.t2
                )
                ORDER BY talk.talk_no desc
            SQL; 
            $stmt=$this->dbh->prepare($sql);
            $stmt -> bindValue(":id",$user,pdo::PARAM_STR);
            $stmt -> bindValue(":no",$checkNo);
            $stmt -> execute();
            return $stmt->fetchAll(pdo::FETCH_ASSOC);

        }

        function talkListCheckNo($user){
            $sql=<<<SQL
                SELECT max(talk_no) as check_no
                FROM talk
                WHERE talk_no IN (
                    SELECT max(talk_no) AS dt
                    FROM (
                        SELECT 
                        CASE WHEN user_id>target_id THEN user_id ELSE target_id END AS t1,
                        CASE WHEN user_id>target_id THEN target_id ELSE user_id END AS t2,
                        talk_no
                        FROM talk
                        WHERE user_id = :id OR target_id = :id
                    ) AS T
                    GROUP BY T.t1, T.t2
                )
            SQL; 
            $stmt=$this->dbh->prepare($sql);
            $stmt -> bindValue(":id",$user,pdo::PARAM_STR);
            $stmt -> execute();
            return $stmt->fetch(pdo::FETCH_ASSOC);

        }

        function profileUpdate($user_id,$user_name,$user_profile,$user_img){

        }

        function retweetSelect($tweet_no){

            $sql=   "SELECT retweet.* ,users.user_id,users.user_name,users.user_img 
                    FROM retweet 
                    LEFT JOIN users 
                    ON retweet.user_id = users.user_id 
                    WHERE retweet.tweet_no = :tweet 
                    ORDER BY retweet.retweet_no desc
                    ";
            $stmt=$this->dbh->prepare($sql);
            $stmt -> bindValue(":tweet",$tweet_no);
            $stmt -> execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);

        }

        function retweetUpdate($tweet_no,$text){
            $sql=   "INSERT INTO retweet( tweet_no , user_id , comment , dt ) VALUES ( :no , :id , :text , :dt )";
            $dt=date("Y-m-d H:i:s");
            $stmt = $this->dbh -> prepare($sql);
            $stmt -> bindValue(":no",$tweet_no);
            $stmt -> bindValue(":id",$_SESSION["user_id"]);
            $stmt -> bindValue(":text",$text);
            $stmt -> bindValue(":dt",$dt);
            $stmt -> execute();
        }

        function retweetCount($user){
            $sql=<<<SQL
                SELECT retweet.tweet_no ,COUNT(retweet.tweet_no) AS 'count'
                FROM retweet
                LEFT JOIN tweet ON retweet.tweet_no = tweet.tweet_no
                WHERE tweet.user_id IN (
                    SELECT friend_id
                    FROM friend
                    WHERE user_id = :id
                ) OR tweet.user_id = :id

                GROUP BY retweet.tweet_no

            SQL;
            
            $stmt = $this->dbh -> prepare($sql);
            $stmt -> bindValue(":id",$user);
            $stmt -> execute();
            return $stmt -> fetchAll(PDO::FETCH_ASSOC);
        }

        function tweetInsert($date,$text){
            $sql="insert into tweet(user_id , tweet_comment , datetime) values(:id , :comment , :date)";
            $comment=nl2br($text);
            $stmt = $this->dbh-> prepare($sql);
            $stmt -> bindValue(":id",$_SESSION["user_id"],pdo::PARAM_STR);
            $stmt -> bindValue(":comment",$comment,pdo::PARAM_STR);
            $stmt -> bindValue(":date",$date);
            $stmt -> execute();
            return true;
        }

    }

    
    $dbh = dbConnect();

    $dbc = new dbC($dbh);
    //start ajax
    if(isset($_POST["all"])){
        $user=$_SESSION["user_id"];
        $array["my_profile"]=$dbc->Profile($user);
        $array["my_tweet"]=$dbc->tweet($user);
        $array["my_friend"]=$dbc->friendList($user);
        $array["my_talk_list"]=$dbc->talkList($user);
        $array["talk_check_no"]=$dbc->talkListCheckNo($user);
        $array["retweet_count"]=$dbc->retweetCount($user);
        $array["tweet_check_no"]=$dbc->tweetCheckNo();

        header('Content-type: application/json');
        echo json_encode($array);
        exit();
    }
    if(isset($_POST["target_page"])){
        $user=$_POST["target_page"];
        $array["friend_profile"]=$dbc->Profile($user);
        $array["friend_tweet"]=$dbc->tweet($user);
        $array["friend_friend"]=$dbc->friendList($user);
        $array["retweet_count"]=$dbc->retweetCount($user);

        header("Content-type: application/json");
        echo json_encode($array);
        exit();
    }

    if(isset($_POST["keyword"])){
        $array["search_list"]=$dbc->userSearch($_POST["keyword"]);
        
        header('Content-type: application/json');
        echo json_encode($array);
        exit();
    }
    
    if(isset($_POST["talk_user"])){
        if($_POST["talk_user"]=="all"){
            $array["all_talk"]=$dbc->allTalk();
            $array["all_talk_date"]=$dbc->allTalkDate();
        
            header('Content-type: application/json');
            echo json_encode($array);
            exit();
        }else{
            $array["all_talk"]=$dbc->talk($_SESSION["user_id"],$_POST["talk_user"]);
            $array["all_talk_date"]=$dbc->talkDate($_SESSION["user_id"],$_POST["talk_user"]);
            header('Content-type: application/json');
            echo json_encode($array);
            exit();
        }
    }
    if(isset($_POST["talk_target"])){
        if($_POST["talk_target"]=="all"){
            $dbc->allTalkUp($_SESSION["user_id"],$_POST["talk_comment"]);
            return true;
            exit();
        }else{

            $dbc->talkUp($_SESSION["user_id"],$_POST["talk_target"],$_POST["talk_comment"]);
            return true;
            exit();
        }
    }
    if(isset($_POST["check_id"])){
        if($_POST["check_id"]=="all"){
            $array["talk_up"]=$dbc->allTalkCheck($_POST["datetime"]);
            $array["all_talk_date"]=$dbc->allTalkDate();
            header('Content-type: application/json');
            echo json_encode($array);
            exit();
        }else{
            $array["talk_up"]=$dbc->talkCheck($_SESSION["user_id"],$_POST["check_id"],$_POST["datetime"]);
            $array["all_talk_date"]=$dbc->talkDate($_SESSION["user_id"],$_POST["check_id"]);
            header('Content-type: application/json');
            echo json_encode($array);
            exit();

        }
    }
    if(isset($_POST["tweet_no"])){
        $array["retweet"]=$dbc->retweetSelect($_POST["tweet_no"]);
        header('Content-type: application/json');
        echo json_encode($array);
        exit();
    }

    if(isset($_POST["retweet_text"])){
        $dbc->retweetUpdate($_POST["tweet_no2"],$_POST["retweet_text"]);
        exit();
    }

    if(isset($_POST["talk_list_check_no"])){
        $array["talk_list_up"]=$dbc->talkListCheck($_SESSION["user_id"],$_POST["talk_list_check_no"]);
        $array["talk_check_no"]=$dbc->talkListCheckNo($_SESSION["user_id"]);
        $array["tweet_check_no"]=$dbc->tweetCheckNo();
        $array["tweet_up"]=$dbc->tweetUp($_POST["tweet_check_no"]);
        $array["retweet_count"]=$dbc->retweetCount($_SESSION["user_id"]);
        header('Content-type: application/json');
        echo json_encode($array);
        exit();
    }

    
    if(isset($_POST["tweet"])){

        $datetime=date("Y-m-d H:i:s");
        $dbc->tweetInsert($datetime,$_POST["tweet"]);
        header("Location:index.php");
        return true;
        exit();
    }



?>