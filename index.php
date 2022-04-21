<?php
    session_start();
    if(!isset($_SESSION["user_id"])){
        header("Location:login.php");
        exit();
    }
    include("function.php");

?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <script src="js/jquery-3.6.0.min.js"></script>
    <title>top</title>
</head>
<body>
    <div id="container" class="relative">
        <!-- header aera 画面固定 -->
        <header>
            <h1>kunicchinry</h1> 
            <!-- friend search area -->
            <div id="header_nav">
                <input type="search" name="search" id="search" placeholder="フレンド検索">
                <button id="search_btn">🔍</button>
                <a href="logout.php"><button>ログアウト</button></a>
                <div id="search_list" class="hidden">
                    <div class="space">
                        <p>検索結果</p>
                        <span class="cancel">×</span>
                    </div>
                    <div id="search_list_disp">
                    </div>
                </div>
            </div>
        </header>
        <!-- main area -->
        <div id="contents">
            <!-- main content area -->
            <div id="div_slider">
                <!-- my page area -->
                <div id="index">
                    <!-- my profile area -->
                    <div id="my_profile" data-user="<?= $_SESSION["user_id"]?>">
                        <div id="profile_top_area">
                            <div id="avater_img">
                                <img class="my_img" src="<?= $_SESSION["user_img"] ?>">
                            </div>
                            <div>
                                <h2><?= $_SESSION["user_name"]?></h2>
                            </div>
                            <div id="my_friend" class="relative pointer">
                                <p>フレンド　<span id="friend_count">0</span>人</p>
                                <div id="friend_list_box" class="hidden absolute"></div>
                            </div>
                            <div>
                                <button id="profile_btn">編集</button>
                            </div>
                        </div>
                        <div id="profile_bottom_area">
                            <h4>-自己紹介-</h4>
                            <p class="profile"></p>
                        </div>
                    </div>
                    <!-- my page button menu area -->
                    <div id="mypage_menu">
                        <button class="tweet_btn pointer">ツイート</button>
                    </div>
                    <hr>
                    <!-- tweet area -->
                    <div id="my_tweet" class="relative">
                        <!-- profile form area -->
                        <div id="profile_form" class="fixed hidden">
                            <form action="profile_update.php" method="post" enctype="multipart/form-data">
                                <table>
                                    <tr>
                                        <td class="space"><p>-プロフィール編集-</p> <span class="cancel">×</span></td>
                                    </tr>
                                    <tr>
                                        <td>ニックネーム：</td>
                                    </tr>
                                    <tr>
                                        <td><input type="text" name="user_name" value="<?= $_SESSION["user_name"] ?>"></td>
                                    </tr>
                                        <td>自己紹介文：</td>
                                    </tr>
                                    <tr>
                                        <td><textarea name="user_profile" cols="50" rows="20" ><?= $_SESSION["user_profile"] ?></textarea></td>
                                    </tr>
                                    <tr>
                                        <td>アバター画像：</td>
                                    </tr>
                                    <tr>
                                        <td><input type="file" name="user_img" accept=".png, .jpg, .jpeg"></td>
                                    </tr>
                                    <tr>
                                        <td><input type="submit" value="変更する"></td>
                                    </tr>
                                </table>
                            </form>
                        </div>
                        <p>ツイート一覧</p>
                        <div id="tweet_disp">
                        </div>
                    </div>
                </div>
                <!-- talk area -->
                <div id="talk" class="relative">
                    <div id="talk_box_container" class="hidden">
                        <div id="talk_box" class="absolute">
                            <div id="talk_box_header" class="space">
                                <div id="target_user"><div id="talk_user_img"></div><h4 id="talk_user" data-id="all">user name</h4></div><span class="cancel">×</span>
                            </div>
                            <div id="talk_result"></div>
                            <div id="talk_comment">
                                <textarea id="comment_area"></textarea>
                                <button type="botton" id="comment_submit">送信</button>
                            </div>
                        </div>
                    </div>
                    <div id="talk_menu">
                        <p>トーク一 覧</p>
                        <button id="all_talk_exe">all</button>
                        <button id="friend_select">friend</button>
                    </div>
                    <div id="friend_select_box" class="hidden">
                        <div class="space">
                            <p>friend list</p><span class="cancel">×</span>
                        </div>
                    </div>
                    <div id="talk_list">
                    </div>
                </div>
                <!-- time line area -->
                <div id="time_line">
                    <div id="time_line_menu">
                        <p>タイムライン一覧</p>
                        <button class="tweet_btn pointer">ツイート</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- footer nav 画面固定 -->
        <nav>
            <ul>
                <li id="home_btn" class="active"><img src="images//mypage.png" alt="mypage"><br><span>MyPage</span></li>
                <li id="talk_btn"><img src="images//talk.png" alt="talk"><br><span>Talk</span></li>
                <li id="time_line_btn"><img src="images//timeline.png" alt="timeline"><br><span>TimeLine</span></li>
            </ul>
        </nav>
    </div>
    <!-- tweet form area -->
    <div id="tweet_form" class="hidden">
            <table>
                <tr>
                    <td class="space"><h3>■tweet</h3> <span class="cancel">×</span></td>
                </tr>
                <tr>
                    <td><textarea name="tweet" id="tweet_comment" cols="50" rows="20"></textarea></td>
                </tr>
                <tr>
                    <td style="text-align:right;"><button>投稿する</button></td>
                </tr>
            </table>
    </div>
    <!-- friend profile area -->
    <div id="friend_page" class='hidden'>
        <div id="friend_page_top">
            <div id="friend_page_menu"><span class="cancel">×</span></div>
            <div id="friend_profile">
                <div id="f_avater_img">
                    <img class="my_img" src="images/free.jpg">
                </div>
                <div>
                    <h2 id="friend_name">name</h2>
                </div>
                <div id="f_friend" class="relative">
                    <p>フレンド　<span id="f_friend_count">0</span>人</p>
                    <div class="friend_list_box" class="hidden absolute"></div>
                </div>
            </div>
            <div id="f_profile_text">
                <h4>-自己紹介-</h4>
                <p id="f_profile"></p>
            </div>
        </div>
        <!-- my page button menu area -->
        <div>
            <hr>
            <!-- tweet area -->
            <div id="f_tweet" class="relative">
                <p>ツイート一覧</p>
                <div id="f_tweet_disp">
                </div>
            </div>
        </div>
    </div>
    <!-- js file -->
    <script src="js/main.js"></script>
</body>
</html>