<?php
session_start();
//로그인 세션 없을때
if(!isset($_SESSION['userId'])){
    echo("<script>location.href='/index.php';</script>");
//로그인 세션 있을때
}else{

}

$userId = $_SESSION['userId'];

//DB연결
include '../DB/DBConnection.php';

//get방식 data받기
$idx =  $_GET["idx"];

//조회수 증가시키기
$sql = "UPDATE freeBoard SET views = views + 1 WHERE idx = $idx";
$result = $conn->query($sql);

//자유 게시판 view내용 가져오기
$sql = "SELECT * FROM freeBoard INNER JOIN user ON freeBoard.writer = user.userId where idx = $idx";
$result = $conn->query($sql);
$row = $result->fetch_assoc();

$title = $row["title"];

$content = $row["content"];
$content = preg_replace("/\r\n|\r|\n/",'<br>',$content);

$writer = $row["writer"];
$nickName = $row["nickName"];
$updateDate = $row["updateDate"];

//좋아요 총 개수 가져오기
$sql = "SELECT count(*) FROM freeBoardLikes WHERE freeBoardNo = $idx";
$result = $conn->query($sql);
$row = $result->fetch_assoc();
$likes = $row["count(*)"];

//내가 좋아요를 선택했는지 안했는지 정보 가져오기
$sql = "SELECT count(*) FROM freeBoardLikes WHERE freeBoardNo = $idx AND userId ='$userId'";
$result = $conn->query($sql);
$row = $result->fetch_assoc();
$my_count = $row["count(*)"];

//북마트 총 개수 가져오기
$sql = "SELECT count(*) FROM freeBoardBookmark WHERE freeBoardNo = $idx";
$result = $conn->query($sql);
$row = $result->fetch_assoc();
$total_book_mark = $row["count(*)"];

//내가 좋아요를 선택했는지 안했는지 정보 가져오기
$sql = "SELECT count(*) FROM freeBoardBookmark WHERE freeBoardNo = $idx AND userId ='$userId'";
$result = $conn->query($sql);
$row = $result->fetch_assoc();
$my_book_mark = $row["count(*)"];

//댓글 총 개수 세기
$sql = "select count(*) from freeBoardComment WHERE board_no = $idx";
$result = $conn->query($sql);
$row = $result->fetch_assoc();
$total_comment_num = $row["count(*)"];
$list_num = 5; //한 페이징당 댓글 개수
$total_page = $total_comment_num/$list_num;

//DB해제
$conn->close();
?>

<script type="text/javascript" src="./util.js"></script>
<script type="text/javascript" src="../smartEditor/js/service/HuskyEZCreator.js" charset="utf-8"></script>

<!DOCTYPE html>
<html>
<head>
    <?php include './header.php'?>
</head>
<body>

<?php include './shareModal.php'?>


<!-- 본문 -->
<div class="container" style="min-width:550px; height: 500px;">

    <!-- 상단 부분 -->
    <?php include './topPart.php'?>

    <!-- 글쓰기 부분 -->
    <div class="container_medium2" >
        <form id ="form" method="POST">
            <input type="hidden" id="idx" name="idx" class="form-control" value="<?php echo $idx ?>"/><br>
            <h1 type="text" id="title" name="title" style="border-bottom: 3px solid silver; padding-bottom: 5px; "><?php echo $title?></h1><br>
            <div id="writer" name="writer" style="margin-bottom: 10px;"><?php echo 'by '.$nickName; echo ' ( '.$writer.' )' ?></div>
            <div id="updateDate" name="updateDate" style="margin-bottom: 20px; color: silver;"><?php echo 'published '.$updateDate; ?></div>
            <div id="content" name="content" style="margin:15px;"><?php echo $content ?></div>
            <?php
            if($writer == $_SESSION['userId']){ ?>
                <div style="float:right; margin-top: 10px;">
                    <button type="button" id="update" class="btn btn-dark" >수정</button>
                    <button type="button" id="delete" class="btn btn-dark">삭제</button>
                </div>
                <div style="clear: both;"></div>
                <?php
            }
            ?>
        </form>

        <!-- 좋아요, 북마크, 공유 -->
        <div style=" height: 75px; position: relative; border-top: 3px solid silver; border-bottom: 3px solid silver; margin-top:30px; margin-bottom: 30px;">
            <div id="like" style="position: absolute; left:6%; top: 50%; transform: translateY(-50%); cursor:pointer;">
                <i class="far fa-heart" style="font-size: 40px;"></i>
                <span style="font-size: 30px;position: absolute;top: 50%; -ms-transform: translateY(-50%); transform: translateY(-50%);">&nbsp;Like(<span id="like_num" ><?php echo $likes?></span>)</span>
            </div>
            <div id="bookmark" style="position: absolute; left:38%; top: 50%; transform: translate(-50%,-50%); cursor:pointer;">
                <i class="far fa-bookmark" style="font-size: 40px;"></i>
                <span style="font-size: 30px;position: absolute;top: 50%; -ms-transform: translateY(-50%); transform: translateY(-50%);">&nbsp;Bookmark(<span id="total_book_mark" ><?php echo $total_book_mark?></span>)</span>
            </div>
            <div id="share" data-toggle="modal" data-target="#exampleModal" style="position: absolute; right:20%; top: 50%; transform: translateY(-50%); cursor:pointer;">
                <i class="fas fa-share-alt" style="font-size: 40px;"></i>
                <span style="font-size: 30px;position: absolute;top: 50%; -ms-transform: translateY(-50%); transform: translateY(-50%);">&nbsp;Share</span>
            </div>
        </div>

        <!-- 댓글 입력창 -->
        <div class="container_medium2" style="background: #f0f0f0; margin-bottom: 20px;">
            <div style="padding:20px;">
                <div style="margin-bottom: 10px;">Comments</div>
                <textarea id="comment_textarea" style="width: 100%; height: 100px; margin-bottom: 10px;"></textarea>
                <div>
                    <button id="comment_post" class="btn" style="background: black; color: white;">Post</button>
                </div>
            </div>
        </div>

        <!-- 댓글 결과창 -->
        <div id="comment_result">
            <!-- 댓글 목록 가져오기 -->
            <?php
            include '../DB/DBConnection.php';

            //댓글 시작위치
            $sql = "SELECT * FROM freeBoardComment WHERE board_no = $idx ORDER BY comment_no DESC LIMIT 0, $list_num";
            $result = $conn->query($sql);
            while($row = $result->fetch_assoc()) {
                $datetime = explode(' ', $row['update_date']);
                $date = $datetime[0];
                $time = $datetime[1];
                $comment_no = $row['comment_no'];
                $content = $row['content'];
                if($date == Date('Y-m-d'))
                    $row['update_date'] = $time;
                else
                    $row['update_date'] = $date;
                ?>
                <div class="container_medium2 comment_view_<?php echo $comment_no?>" style="border: 2px solid silver; margin-bottom: 20px;">
                    <div style="padding:10px;">
                        <div style="float:left; margin-bottom: 10px; color: #41169A; font-size: 15px; font-weight: bold;"><?php echo $row['writer_nick_name'].' ( '.$row['writer_email'].' ) '?></div>
                        <!-- 나의 댓글만 수정 삭제 활성화 -->
                        <?php
                        if($userId == $row['writer_email']){?>
                            <div style="float:right; color:silver;"><span style="cursor: pointer;" onclick="comment_view_update(<?php echo $comment_no ?>)">수정</span>&nbsp;|&nbsp;<span style="cursor: pointer;" onclick="comment_delete(<?php echo $comment_no ?>)">삭제</span></div>
                        <?php
                        }?>
                        <div style="clear:both;"></div>
                        <div class="content" style="width: 100%; margin-bottom: 10px;"><?php echo $row['content']?></div>
                        <div class="update_date" style="color:silver;"><?php echo $row['update_date']?></div>
                    </div>
                </div>
                <div class="container_medium2 comment_textarea_<?php echo $comment_no?>" style="display:none; border: 2px solid silver; margin-bottom: 20px;">
                    <div style="padding:10px;">
                        <div style="float:left; margin-bottom: 10px; color: #41169A; font-size: 15px; font-weight: bold;"><?php echo $row['writer_nick_name'].' ( '.$row['writer_email'].' ) '?></div>
                        <!-- 나의 댓글만 수정 삭제 활성화 -->
                        <?php
                        if($userId == $row['writer_email']){?>
                            <div style="float:right; color:silver;"><span style="cursor: pointer;" onclick="comment_update_cancel(<?php echo $comment_no ?>)">수정취소</span></div>
                            <?php
                        }?>
                        <div style="clear:both;"></div>
                        <div style='width:100%; position: relative;'>
                            <textarea class="content" style='width: 90%;'></textarea>
                            <button style="background: black; color:white; position: absolute; right:0%; top: 50%; transform: translateY(-50%);"
                                    onclick="comment_update_btn(<?php echo $comment_no?>)" class="btn">수정</button>
                        </div>
                        <div class="update_date" style="color:silver;"><?php echo $row['update_date']?></div>
                    </div>
                </div>
                <?php
            }
            $conn->close();
            ?>
        </div>
    </div><!-- container_medium2 끝부분 -->
</div>

<script type="text/javascript">
    $(document).on('ready', function(e){
        $("#update").on("click", function() {
            let idx = $("#idx").val();
            location.href='/freeBoardUpdateView.php?idx='+idx;
        });

        $("#delete").on("click", function() {
            if(confirm("정말 삭제하시겠습니까?") == true){
                let $form = $("#form");
                $form.attr("action", "freeBoardDelete.php");
                $form.trigger("submit");
            }
        });

        //좋아요 클릭
        $("#like").on("click", function() {
            let $svg = $("#like i");
            let $like_num = $("#like_num");
            let like_num_value = $like_num.html()*1;
            console.log("like_num_value:",like_num_value);
            //DB에서 존재하는 아이디인지 검사
            $.ajax({
                type: "POST",
                url : "/freeBoardLike.php",
                data: {"idx":<?php echo $idx ?>},
                dataType:"json",
                success : function(data, status, xhr) {
                    console.log("data:",data);
                    $svg.toggleClass("fas");
                    $like_num.html(data.count);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(jqXHR.responseText);
                }
            });
        });

        //북마크 클릭
        $("#bookmark").on("click", function() {
            let $bookmark = $("#bookmark i");
            let $total_book_mark = $("#total_book_mark");
            //DB에서 존재하는 아이디인지 검사
            $.ajax({
                type: "POST",
                url : "/freeBoardMark.php",
                data: {"idx":<?php echo $idx ?>},
                dataType:"json",
                success : function(data, status, xhr) {
                    console.log("data:",data);
                    $bookmark.toggleClass("fas");
                    $total_book_mark.html(data.count);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(jqXHR.responseText);
                }
            });
        });

        //공유 클릭
        $("#share").on("click", function() {
            $("#modal_input_url").val(window.location.href);
        });

        //처음 로딩될때 좋아요 선택or비선택 하게 만들기
        let $svg = $("#like i");
        //이미 좋아요 했다면
        if(<?php echo $my_count?>>0){
            $svg.addClass("fas");
        //아직 좋아요를 누르지 않았다면
        }else{

        }

        //처음 로딩될때 북마크 선택or비선택 하게 만들기
        let $bookmark = $("#bookmark i");
        //이미 북마크 선택했다면
        if(<?php echo $my_book_mark?>>0){
            $bookmark.addClass("fas");
        //아직 북마트 선택하지 않았다면
        }else{

        }

        $('#myModal').on('shown.bs.modal', function () {
            $('#myInput').trigger('focus')
        });

        //댓글 post클릭
        $('#comment_post').on("click", function() {
            let comment_value = $("#comment_textarea").val();
            console.log("comment_value:",comment_value);
            if(comment_value == "" || comment_value == null){
                alert("댓글을 입력해주세요.");
                return;
            }
            $.ajax({
                type: "POST",
                url : "/freeBoardComment.php",
                data: {"idx":<?php echo $idx ?>,"content":$("#comment_textarea").val()},
                dataType:"html",
                success : function(data, status, xhr) {
                    // console.log("data:",data);
                    $("#comment_textarea").val("");
                    $("#comment_result").prepend(data);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    $("#comment_textarea").val("");
                    console.log(jqXHR.responseText);
                }
            });
        });

        //page = 0이 맨처음
        let page = 1;
        //스크롤 제일 밑으로 왔을때
        $(window).on( "scroll", function() {
            if (Math.floor($(window).scrollTop()) == Math.floor($(document).height() - window.innerHeight)) {
                console.log("제일 마지막 부분");
                //가져올 페이지가 존재한다면
                if(page <= <?php echo $total_page?>){
                    //로딩바 추가
                    let loading = '<div class="loading" style="text-align: center; margin-bottom: 10px;">' +
                                        '<div class="spinner-border" role="status" style="width: 40px; height: 40px;">\n' +
                                        '<span class="sr-only">Loading...</span>\n' +
                                        '</div>'+
                                  '</div>'
                    $("#comment_result").append(loading);

                    $.ajax({
                        type: "POST",
                        url : "/freeBoardInfiniteScroll.php",
                        data: {"page":page, "board_no":<?php echo $idx ?>},
                        dataType:"html",
                        async:false,
                        success : function(data, status, xhr) {

                            //로딩바 없애기
                            let loading = document.querySelector(".loading");	//제거하고자 하는 엘리먼트
                            let comment_result = document.querySelector("#comment_result");		// 그 엘리먼트의 부모 객체
                            comment_result.removeChild(loading);

                            //댓글 페이징결과 추가시키기
                            page++;
                            // console.log("page:",page);
                            // console.log("data:",data);
                            $("#comment_result").append(data);
                        },
                        error: function(jqXHR, textStatus, errorThrown) {
                            //로딩바 없애기
                            let loading = document.querySelector(".loading");	//제거하고자 하는 엘리먼트
                            let comment_result = document.querySelector("#comment_result");		// 그 엘리먼트의 부모 객체
                            comment_result.removeChild(loading);

                            console.log(jqXHR.responseText);
                        }
                    });
                }
            }
        });

    });

    //수정 클릭 (수정하는 창 띄우기)
    function comment_view_update(comment_no) {
        $(".comment_view_"+comment_no).css("display","none");
        $(".comment_textarea_"+comment_no).css("display","block");
        //수정전 내용 가져오기
        let before_content = $(".comment_view_"+comment_no+" .content").html();
        //수정창에 수정전 내용 넣어주기
        $(".comment_textarea_"+comment_no+" .content").val(before_content);

        console.log("before_content:",before_content);
        console.log("comment_no:",comment_no);


    }
    //수정 취소 클릭 (view창 띄우기)
    function comment_update_cancel(comment_no) {
        $(".comment_textarea_"+comment_no).css("display","none");
        $(".comment_view_"+comment_no).css("display","block");
        console.log("comment_no:",comment_no);


    }

    //수정 버튼 클릭 (수정 동작하기)
    function comment_update_btn(comment_no){
        //수정창에 있는 내용 가져오기
        let textarea_content = $(".comment_textarea_"+comment_no+" .content").val();
        console.log("comment_no:",comment_no);
        console.log("textarea_content:",textarea_content);
        $.ajax({
            type: "POST",
            url : "/freeBoardCommentUpdate.php",
            data: {"comment_no":comment_no,"textarea_content":textarea_content},
            dataType:"json",
            success : function(data, status, xhr) {
                console.log("data:",data);

                //데이터 넣어주기
                $(".comment_textarea_"+comment_no+" .content").val(data.content);
                $(".comment_textarea_"+comment_no+" .update_date").html(data.update_date);
                $(".comment_view_"+comment_no+" .content").html(data.content);
                $(".comment_view_"+comment_no+" .update_date").html(data.update_date);

                //댓글창 토글
                $(".comment_textarea_"+comment_no).css("display","none");
                $(".comment_view_"+comment_no).css("display","block");

            },
            error: function(jqXHR, textStatus, errorThrown) {
                $("#comment_textarea").val("");
                console.log(jqXHR.responseText);
            }
        });
    }

    //댓글 삭제버튼 클릭
    function  comment_delete(comment_no) {
        console.log("comment_no:",comment_no);
        let result = confirm("정말 삭제하시겠습니까?");

        if(result){
            let comment_view = document.querySelector(".comment_view_"+comment_no);	//제거하고자 하는 엘리먼트
            let comment_textarea = document.querySelector(".comment_textarea_"+comment_no); //제거하고자 하는 엘리먼트
            let comment_result = document.querySelector("#comment_result");		// 그 엘리먼트의 부모 객체
            comment_result.removeChild(comment_view);
            comment_result.removeChild(comment_textarea);
            $.ajax({
                type: "POST",
                url : "/freeBoardCommentDelete.php",
                data: {"comment_no":comment_no},
                dataType:"json",
                success : function(data, status, xhr) {
                    console.log("data:",data);
                    if(data.result > 0){
                        alert("삭제 완료됐습니다.");
                    }else{
                        alert("삭제에 실패했습니다.");
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    alert("삭제에 실패했습니다.");
                    console.log(jqXHR.responseText);
                }
            });
        }else{
            console.log("삭제취소");
        }
    }

</script>



</body>
</html>

<script type="text/javascript">
    function submitContents(elClickedObj) {
        try {
            let $form = $("#form");
            $form.attr("action", "freeBoardUpdateView.php");
            elClickedObj.form.submit();
        } catch(e) {
            console.log(e);
        }
    }
</script>
