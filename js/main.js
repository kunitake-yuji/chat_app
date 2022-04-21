$(function(){
    // localStorage.setItem('friends', JSON.stringify({}))
    // localStorage.getItem('friends')
    // const = locall....

    
    let user_id;    //user_idを保存
    let check;      //talk check setinterval
    let check_tl;   //talk list check setinterval
    let checkdate;  //talk check date
    let talk_check_no;  //talk list check no
    let tweet_check_no;
    let retweet_count;

    //トークリストの更新処理
    function talkListCheck(check){
        $.ajax({
            type:"POST",
            url:"dbC.php",
            data:{
                "talk_list_check_no": check,
                "tweet_check_no":tweet_check_no
            },
            dataType:"json"
        }).done(function(data){
            talk_check_no=data["talk_check_no"]["check_no"];
            $.each(data["talk_list_up"],function(key,val){
                if($(".talk_list_row[data-id='"+val["target_id"]+"']").length > 0){
                    $(".talk_list_row[data-id='"+val["target_id"]+"']").remove();
                }else if($(".talk_list_row[data-id='"+val["user_id"]+"']").length > 0){
                    $(".talk_list_row[data-id='"+val["user_id"]+"']").remove();
                }
                if(val["user_id"]==$("#my_profile").attr("data-user")){
                    $("#talk_list").prepend(
                        "<div class='flex talk_start_btn talk_list_row' data-talk_no='"+val["talk_no"]+"' data-id='"+val["target_id"]+"'  data-img='"+val["target_img"]+"' data-name='"+val["target_name"]+"'><img class='talk_list_img' src='"+val["target_img"]+"'><span>"+val["target_name"]+"</span><div>あなた："+val["comment"]+"</div><div>"+val["datetime"]+"</div></div>"
                    );
                }else{
                    $("#talk_list").prepend(
                        "<div class='flex talk_start_btn talk_list_row' data-talk_no='"+val["talk_no"]+"' data-id='"+val["user_id"]+"' data-img='"+val["user_img"]+"' data-name='"+val["user_name"]+"'><img class='talk_list_img' src='"+val["user_img"]+"'><span>"+val["user_name"]+"</span><div>あいて："+val["comment"]+"</div><div>"+val["datetime"]+"</div></div>"
                    );
                }
                
            });
            if(tweet_check_no!=data["tweet_check_no"]["tweet_no"]){
                tweet_check_no=data["tweet_check_no"]["tweet_no"];
                $.each(data["tweet_up"],function(key,val){
                    if(val["user_id"]==user_id){
                        $('#tweet_disp').prepend(
                            "<div class='tweet_row' data-no="+val["tweet_no"]+">\
                                <div class='tweet_p'>\
                                    <img src='"+val["user_img"]+"' class='tweet_p_img'>\
                                    <span class='tweet_p_name'>"+
                                        val["user_name"]+
                                    "</span>\
                                    <span class='tweet_p_datetime'>"+
                                        val["datetime"]+
                                    "</span>\
                                </div>\
                                <div class='tweet_result'>"+
                                    val["tweet_comment"]+
                                "</div>\
                                <div>\
                                    <div class='retweet_count' data-no='"+val["tweet_no"]+"'>コメント数：<span class='retweet_count_disp'>0</span></div>\
                                    <button class='retweet_disp_btn' data-no='"+val["tweet_no"]+"'>コメント一覧</button>\
                                    <button class='retweet_btn' data-no='"+val["tweet_no"]+"'>コメントする</button>\
                                </div>\
                                <div class='retweet_disp_area'>\
                                </div>\
                            </div>"
                        );
                    }
                    $('#time_line_menu').after(
                        "<div class='tweet_row' data-no="+val["tweet_no"]+">\
                            <div class='tweet_p'>\
                                <img src='"+val["user_img"]+"' class='tweet_p_img target_page_btn pointer' data-id='"+val["user_id"]+"'>\
                                <span class='tweet_p_name target_page_btn pointer' data-id='"+val["user_id"]+"'>"+
                                    val["user_name"]+
                                "</span>\
                                <span class='tweet_p_datetime'>"+
                                    val["datetime"]+
                                "</span>\
                            </div>\
                            <div class='tweet_result'>"+
                                val["tweet_comment"]+
                            "</div>\
                            <div>\
                                <div class='retweet_count' data-no='"+val["tweet_no"]+"'>コメント数：<span class='retweet_count_disp'>0</span></div>\
                                <button class='retweet_disp_btn' data-no='"+val["tweet_no"]+"'>コメント一覧</button><button class='retweet_btn' data-no='"+val["tweet_no"]+"'>コメントする</button>\
                            </div>\
                            <div class='retweet_disp_area'>\
                            </div>\
                        </div>"
                    );
                })
            }
            if(retweet_count!=data["retweet_count"].length){
                retweet_count=data["retweet_count"].length;
                $.each(data["retweet_count"],function(key,val){
                    $(".retweet_count[data-no='"+val["tweet_no"]+"'] .retweet_count_disp").text(val["count"]);
                })
            }

        }).fail(function(){
            console.log("talk list check error")
        })
    }

    // トークの更新処理
    function talkCheck(check_id){
        $.ajax({
            type:"POST",
            url:"dbC.php",
            dataType:"json",
            data:{
                "datetime":checkdate,
                "check_id":check_id
            }
        }).done(function(data){
            console.log("check ok");
            checkdate=data["all_talk_date"]["datetime"];
            $.each(data["talk_up"],function(key,val){
                if(val["user_id"]==user_id){
                    $("#talk_result").append(
                        "<div class='user_talk_result'>\
                            <div class='talk_right'>"+
                                val["comment"]+
                            "</div>\
                            <div class='talk_time'>"+
                                val["datetime"]+
                            "</div>\
                        </div>"
                    );
                }else{
                    $("#talk_result").append(
                        "<div class='target_talk_result'>\
                            <div class='talk_left'>"+
                                val["comment"]+
                            "</div>\
                            <div class='target_user'>\
                                <img class='search_img' src='"+val["user_img"]+"'>\
                                <span>"+
                                    val["user_name"]+
                                "</span>\
                            <div>\
                            <div class='talk_time'>"+
                                val["datetime"]+
                            "</div>\
                        </div>"
                    );
                }
                $("#talk_result").animate(
                    { scrollTop: $("#talk_result").get(0).scrollHeight },
                    500
                );    
            });
        }).fail(function(){
            console.log("check error");
        });
    }
    //talk画面初期処理
    function talkLead(target_name,target_id,target_img=""){
        if($("#talk_box_container").hasClass("hidden")){
            $("#talk_box_container").removeClass("hidden");
            $("#talk_user").text(target_name);
            $("#talk_user").attr("data-id",target_id);
            if(target_id!="all"){
                $("#talk_user_img").empty();
                $("#talk_user_img").append(
                    "<img class='small_img' src=''>"
                );
            }else{
                $("#talk_user_img").empty();
            }
            $.ajax({
                type: "POST",
                url: "dbC.php",
                data:{
                    "talk_user": target_id
                },
                dataType: "json"
            }).done(function(data){
                if(target_id!="all"){
                    $("#talk_user_img img").attr("src",target_img)
                }
                checkdate=data["all_talk_date"]["datetime"];
                $("#talk_result").empty();
                $.each(data["all_talk"],function(key,val){
                    if(val["user_id"]==user_id){
                        $("#talk_result").append(
                            "<div class='user_talk_result'>\
                                <div class='talk_right'>"+
                                    val["comment"]+
                                "</div>\
                                <div class='talk_time'>"+
                                    val["datetime"]+
                                "</div>\
                            </div>"
                        );
                    }else{
                        $("#talk_result").append(
                            "<div class='target_talk_result'>\
                                <div class='talk_left'>"+
                                    val["comment"]+
                                "</div>\
                                <div class='target_user'>\
                                    <img class='search_img' src='"+val["user_img"]+"'>\
                                    <span>"+
                                    val["user_name"]+
                                    "</span>\
                                </div>\
                                <div class='talk_time'>"+
                                    val["datetime"]+
                                "</div>\
                            </div>"
                        );
                    }
                });
                check=setInterval(function(){
                    talkCheck(target_id);
                },1500);
                $("#talk_result").animate(
                    { scrollTop: $("#talk_result").get(0).scrollHeight },
                    500
                );
            }).fail(function(jqXHR, textStatus, errorThrown){
        
                console.log("sippai");
            })
        }
    }

    // index disp chenge
    $("#home_btn").click(function(){
        $("#div_slider:not(:animated)").animate({"margin-left":0 + "px"},600);
        $("nav ul li").removeClass();
        $(this).addClass("active");
    });
    $("#talk_btn").click(function(){
        $("#div_slider:not(:animated)").animate({"margin-left":-800 + "px"},600);
        $("nav ul li").removeClass();
        $(this).addClass("active");

    });
    $("#time_line_btn").click(function(){
        $("#div_slider:not(:animated)").animate({"margin-left":-1600 + "px"},600);
        $("nav ul li").removeClass();
        $(this).addClass("active");

    });


    //talk boxを開くイベント処理
    $("#friend_select_box,#talk_list").on("click",".talk_start_btn",function(){
        $("#friend_select_box").removeClass("absolute");
        $("#friend_select_box").addClass("hidden");
        talkLead($(this).attr("data-name"),$(this).attr("data-id"),$(this).attr("data-img"));

    })




    // フレンド検索処理
    $("#search_btn").click(function(){
        $("#search_list_disp").empty();
        if($("#search_list").hasClass("hidden")){
            $("#search_list").removeClass("hidden");
        }
        $.ajax({
            type: "POST",
            url: "dbC.php",
            dataType:"json",
            data:{
                "keyword": $("#search").val()
            }
        }).done(function(data){
            console.log(data);
            $.each(data["search_list"],function(key,val){
                $('#search_list_disp').append(
                    "<div class='search_row'><div class='target_page_btn' data-id='"+val["user_id"]+"'><img class='search_img' src='"+val["user_img"]+"'><span>" + val["user_name"] + "</span></div><form action='friend.php' method='post'><input type='hidden' name='user_id' value='"+val["user_id"]+"'><input type='submit' value='友達になる'></form></div>");
            });
        }).fail(function(){
            console.log("errore");
        });
    });
    // フレンド検索ボックスを閉じる処理 
    $("#search_list .cancel").click(function(){
        $("#search_list").addClass("hidden");
    });


    // フレンドのマイページを閉じる処理
    $("body").on("click","#friend_page .cancel",function(){
        $("#friend_page").removeClass("fixed");
        $("#friend_page").addClass("hidden");
    });
    //　フレンドのマイページを開く処理
    $("body").on("click",".target_page_btn",function(){
        $("#friend_page").removeClass("hidden");
        $("#friend_page").addClass("fixed");
        $("#f_tweet_disp").empty();
        let userid=$(this).attr("data-id");
        console.log(this);
        $.ajax({
            type:"POST",
            url:"dbC.php",
            dataType:"json",
            data:{
                "target_page":userid
            }
        }).done(function(data){
            console.log(userid);
            $("#f_avater_img img").attr("src",data["friend_profile"]["user_img"]);
            $("#friend_name").text(data["friend_profile"]["user_name"]);
            
            $("#f_friend_count").text(data["friend_friend"].length);
            $('#f_profile').html(data["friend_profile"]["user_profile"]);

            $.each(data["friend_tweet"],function(key,val){
                if(val["user_id"]==userid){
                    $('#f_tweet_disp').append(
                        "<div class='tweet_row' data-no="+val["tweet_no"]+">\
                            <div class='tweet_p'>\
                                <img src='"+val["user_img"]+"' class='tweet_p_img'>\
                                <span class='tweet_p_name'>"+
                                    val["user_name"]+
                                "</span>\
                                <span class='tweet_p_datetime'>"+
                                    val["datetime"]+
                                "</span>\
                            </div>\
                            <div class='tweet_result'>"+
                                val["tweet_comment"]+
                            "</div>\
                            <div>\
                                <div class='retweet_count' data-no='"+val["tweet_no"]+"'>コメント数：<span class='retweet_count_disp'>0</span></div>\
                                <button class='retweet_disp_btn' data-no='"+val["tweet_no"]+"'>コメント一覧</button>\
                                <button class='retweet_btn' data-no='"+val["tweet_no"]+"'>コメントする</button>\
                            </div>\
                        </div>"
                    );
                }
            })
            $.each(data["retweet_count"],function(key,val){
                $(".retweet_count[data-no='"+val["tweet_no"]+"'] .retweet_count_disp").text(val["count"]);
            })
    
    
        }).fail(function(){
            console.log("sippai");
        });
    });


    // index.php 読み込みでdbからデータを取得・書き換え
    $.ajax({
        type: "POST",
        url: "dbC.php",
        data:{
            "all": "all"
        },
        dataType: "json"
    }).done(function(data){
        console.log(data);
        retweet_count=data["retweet_count"].length;
        tweet_check_no=data["tweet_check_no"]["tweet_no"];
        talk_check_no=data["talk_check_no"]["check_no"];
        user_id=data["my_profile"]["user_id"];

        check_tl=setInterval(function(){
            talkListCheck(talk_check_no);
        },3000);

        $("#friend_count").text(data["my_friend"].length);
        $('#my_profile .profile').html(data["my_profile"]["user_profile"]);

        $.each(data["my_tweet"],function(key,val){
            if(val["user_id"]==user_id){
                $('#tweet_disp').append(
                    "<div class='tweet_row' data-no="+val["tweet_no"]+">\
                        <div class='tweet_p'>\
                            <img src='"+val["user_img"]+"' class='tweet_p_img'>\
                            <span class='tweet_p_name'>"+
                                val["user_name"]+
                            "</span>\
                            <span class='tweet_p_datetime'>"+
                                val["datetime"]+
                            "</span>\
                        </div>\
                        <div class='tweet_result'>"+
                            val["tweet_comment"]+
                        "</div>\
                        <div>\
                            <div class='retweet_count' data-no='"+val["tweet_no"]+"'>コメント数：<span class='retweet_count_disp'>0</span></div>\
                            <button class='retweet_disp_btn' data-no='"+val["tweet_no"]+"'>コメント一覧</button>\
                            <button class='retweet_btn' data-no='"+val["tweet_no"]+"'>コメントする</button>\
                        </div>\
                        <div class='retweet_disp_area'>\
                        </div>\
                    </div>"
                );
            }
            $('#time_line').append(
                "<div class='tweet_row' data-no="+val["tweet_no"]+">\
                    <div class='tweet_p'>\
                        <img src='"+val["user_img"]+"' class='tweet_p_img target_page_btn pointer' data-id='"+val["user_id"]+"'>\
                        <span class='tweet_p_name target_page_btn pointer' data-id='"+val["user_id"]+"'>"+
                            val["user_name"]+
                        "</span>\
                        <span class='tweet_p_datetime'>"+
                            val["datetime"]+
                        "</span>\
                    </div>\
                    <div class='tweet_result'>"+
                        val["tweet_comment"]+
                    "</div>\
                    <div>\
                        <div class='retweet_count' data-no='"+val["tweet_no"]+"'>コメント数：<span class='retweet_count_disp'>0</span></div>\
                        <button class='retweet_disp_btn' data-no='"+val["tweet_no"]+"'>コメント一覧</button><button class='retweet_btn' data-no='"+val["tweet_no"]+"'>コメントする</button>\
                    </div>\
                    <div class='retweet_disp_area'>\
                    </div>\
                </div>"
            );
        })

        $.each(data["retweet_count"],function(key,val){
            $(".retweet_count[data-no='"+val["tweet_no"]+"'] .retweet_count_disp").text(val["count"]);
        })


        $.each(data["my_friend"],function(key,val){
            
            $('#friend_list_box').append(
                "<div class='search_row target_page_btn pointer' data-id='"+val["friend_id"]+"'>\
                    <img class='search_img' src='"+val["friend_img"]+"'>\
                    <div>"+
                        val["friend_name"]+
                    "</div>\
                </div>"
            );
            $('#friend_select_box').append(
                "<div class='friend_list_row'>\
                    <div class='friend_list_username target_page_btn pointer' data-id='"+val["friend_id"]+"'>\
                        <img class='small_img' src='"+val["friend_img"]+"'>" + 
                        val["friend_name"] + 
                    "</div>\
                    <button class='talk_start_btn' data-id='"+val["friend_id"]+"' data-img='"+val["friend_img"]+"' data-name='"+val["friend_name"]+"'>トーク</button>\
                </div>"
            );

        });

        $.each(data["my_talk_list"],function(key,val){
            if(val["user_id"]==$("#my_profile").attr("data-user")){
                $("#talk_list").append(
                    "<div class='flex talk_start_btn talk_list_row' data-talk_no='"+val["talk_no"]+"' data-id='"+val["target_id"]+"'  data-img='"+val["target_img"]+"' data-name='"+val["target_name"]+"'><img class='talk_list_img' src='"+val["target_img"]+"'><span>"+val["target_name"]+"</span><div>あなた："+val["comment"]+"</div><div>"+val["datetime"]+"</div></div>"
                );
            }else{
                $("#talk_list").append(
                    "<div class='flex talk_start_btn talk_list_row' data-talk_no='"+val["talk_no"]+"' data-id='"+val["user_id"]+"' data-img='"+val["user_img"]+"' data-name='"+val["user_name"]+"'><img class='talk_list_img' src='"+val["user_img"]+"'><span>"+val["user_name"]+"</span><div>あいて："+val["comment"]+"</div><div>"+val["datetime"]+"</div></div>"
                );
            }
            
        });


    }).fail(function(jqXHR, textStatus, errorThrown){
        console.log("初期読み込みエラー");
    })

    //　リツイートをするテキストエリアと送信ボタンの表示
    $("body").on("click",".retweet_btn",function(){
        if($(this).parent().parent().children("div.retweet_up").length < 1){
            $(this).parent().parent().append(
                "<div class='retweet_up'><textarea class='retweet_text' data='"+$(this).attr("data-no")+"'></textarea><button class='retweet_submit' data-no='"+$(this).attr("data-no")+"'>コメント</button><div>"
            );
        }else{
            $(this).parent().parent().children("div.retweet_up").remove();
        }
    });

    //　リツイートが送信された時の処理
    $("body").on("click",".retweet_submit",function(){
        let address=$(this).parent().prev().prev().children(".retweet_disp_btn");
        let text = $(this).prev();
        let tweetNo=$(this).attr("data-no");
        $.ajax({
            type:"POST",
            url:"dbC.php",
            data:{
                "retweet_text":(text).val(),
                "tweet_no":tweetNo
            }
        }).done(function(){
            $(address).trigger("click");
            (text).val("");
            (text).parent().remove();
        }).fail(function(){
            console.log("retweet up 失敗")
        })
    });
    // リツイートを非表示する処理
    $("body").on("click",".tweet_row .cancel",function(){
        $(this).parent().parent().empty();
    })
    // リツイートを表示する処理
    $("body").on("click",".retweet_disp_btn",function(){
        let checkno= $(this).attr("data-no");
        let target_address=$(this).parent().next();
        $.ajax({
            type: "POST",
            url: "dbC.php",
            data:{
                "tweet_no": checkno
            },
            dataType: "json"
        }).done(function(data){
            $(target_address).empty();
            $(target_address).append(
                "<hr><div class='space'><p>-コメント一覧-</p><span class='cancel'>×</span></div>"
            );
            $.each(data["retweet"],function(key,val){
                $(target_address).append(
                    "<div class='retweet_box'>\
                        <div class='retweet_flex'><div>\
                            <img class='small_img' src='"+val["user_img"]+"'>\
                        </div>\
                        <div>"+
                            val["user_name"]+
                        "</div>\
                        <div>"+
                           val["dt"]+
                        "</div></div>\
                        <div>"+
                             val["comment"]+
                        "</div>\
                    </div>"
                )
            })
        }).fail(function(){
            console.log(e)
        }
        );
    });

    // ツイート投稿フォーム表示処理
    $(".tweet_btn").click(function(){
        if($("#tweet_form").hasClass("hidden")){
            $("#tweet_form").removeClass("hidden");
            $("#tweet_form").addClass("fixed");
        }
    });

    // ツイート投稿処理
    $("#tweet_form button").click(function(){
        $("#tweet_form").removeClass("fixed");
        $("#tweet_form").addClass("hidden");

        $.ajax({
            type:"POST",
            url:"dbC.php",
            data:{
                "tweet":$("#tweet_comment").val()
            }
        }).done(function(){
            $("#tweet_comment").val("")
        }).fail(function(){
            console.log("ツイート投稿エラー")
        });
    });
    //　ツイート投稿フォームを閉じる処理
    $("#tweet_form .cancel").click(function(){
            $("#tweet_form").addClass("hidden");
    });

    // ターゲット要素の外側をクリックされた時の処理
    $(document).on('click',function(e) {
        if(!$(e.target).closest('#friend_page').length && !$(e.target).closest('.target_page_btn').length) {
            if($("#friend_page").hasClass("fixed")){
                $("#friend_page").removeClass("fixed")
                $("#friend_page").addClass("hidden");;
            }
        }
        if(!$(e.target).closest('#tweet_form').length && !$(e.target).closest('.tweet_btn').length) {
            if(!$("#tweet_form").hasClass("hidden")){
                $("#tweet_form").addClass("hidden");
            }
        }
        if(!$(e.target).closest('#search_list').length && !$(e.target).closest('#search_btn').length) {
            if(!$("#search_list").hasClass("hidden")){
                $("#search_list").addClass("hidden");
            }
        }
        if(!$(e.target).closest('#profile_form').length && !$(e.target).closest('#profile_btn').length) {
            if(!$("#profile_form").hasClass("hidden")){
                $("#profile_form").addClass("hidden");
            }
        }
        if(!$(e.target).closest('#friend_list_box').length && !$(e.target).closest('#my_friend').length) {
            if(!$("friend_list_box").hasClass("hidden")){
                $("#friend_list_box").addClass("hidden");
            }
        }
        if(!$(e.target).closest('#talk_box_container').length && !$(e.target).closest('#all_talk_exe').length && !$(e.target).closest('.talk_start_btn').length) {
            if(!$("#talk_box_container").hasClass("hidden")){
                $("#talk_box_container").addClass("hidden");
            }
            clearInterval(check);
        }
        if(!$(e.target).closest('#friend_select_box').length && !$(e.target).closest('#friend_select').length) {
            if($("#friend_select_box").hasClass("absolute")){
                $("#friend_select_box").removeClass("absolute");
                $("#friend_select_box").addClass("hidden");
            }
        }
  
    });

    // フレンドボックスの表示する処理
    $("#my_friend").click(function(){
        if($("#friend_list_box").hasClass("hidden")){
            $("#friend_list_box").removeClass("hidden");
        }
    });

    // プロフィール変更フォームを表示
    $("#profile_btn").click(function(){
        if($("#profile_form").hasClass("hidden")){
            $("#profile_form").removeClass("hidden");
        }
    });
    //　プロフィール変更フォームを非表示
    $("#profile_form .cancel").click(function(){
        $("#profile_form").addClass("hidden");
    });

    // トークルームの非表示処理
    $("#talk_box .cancel").click(function(){
        $("#talk_box_container").addClass("hidden");
        clearInterval(check);
    });
    
    //　トークルーム(all)の表示処理
    $("#all_talk_exe").click(function(){
        talkLead("all","all");
    });
    //　フレンド一覧からトークルームを表示する処理
    $(".talk_start_btn").click(function(){
        console.log("a");
        $("#friend_select_box").removeClass("absolute");
        $("#friend_select_box").addClass("hidden");
        talkLead($(this).attr("data-name"),$(this).attr("data-id"));
    });


    //　トークの投稿処理
    $("#comment_submit").click(function(){
        $.ajax({
            type:"POST",
            url:"dbC.php",
            data:{
                "talk_target": $('#talk_user').attr("data-id"),
                "talk_comment":$("#comment_area").val()
            }
        }).done(function(){
            console.log("seikou");
            $("#comment_area").val("");
        }).fail(function(){
        });
    });
    //　トークページからフレンドを選択する画面の表示処理
    $("body").on("click","#friend_select",function(){
        if($("#friend_select_box").hasClass("hidden")){
            $("#friend_select_box").removeClass("hidden");
            $("#friend_select_box").addClass("absolute");

        }
    });
    //　トークページ　フレンド選択画面の非表示処理
    $("#friend_select_box .cancel").click(function(){
        if($("#friend_select_box").hasClass("absolute")){
            $("#friend_select_box").removeClass("absolute");
            $("#friend_select_box").addClass("hidden");

        }
    });

});