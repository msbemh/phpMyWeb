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
    <div style="position:relative; height: 80px">
        <h1 style="float:left;">여행 일정</h1>
        <div style="float:right; margin:20px 0px;">
            <button id="logOut" class="btn" style="background: #ffe8d6; font-weight:bold; font-size: 18px;">로그아웃</button>
        </div>
        <div style="float:right; margin:20px 0px; padding: 6px 12px; font-weight:bold; font-size: 18px;"><?php echo "{$_SESSION["nickName"]}" ?> 님</div>
    </div>
    <div style="clear: both"></div>
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
                    <button type="button" id="update" class="btn">수정</button>
                    <button type="button" id="delete" class="btn">삭제</button>
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
                <textarea style="width: 100%; height: 100px; margin-bottom: 10px;"></textarea>
                <div>
                    <button class="btn" style="background: black; color: white;">Post</button>
                </div>
            </div>
        </div>

        <!-- 댓글 결과창 -->
        <div class="container_medium2" style="border: 2px solid silver; margin-bottom: 20px;">
            <div style="padding:10px;">
                <div style="margin-bottom: 10px; color: #41169A; font-size: 15px; font-weight: bold;">너구리 ( msbe@naver.com )</div>
                <div style="width: 100%; margin-bottom: 10px;">안녕하세요</div>
                <div style="color:silver;">2019-07-23 09:43:11</div>
            </div>
        </div>

    </div><!-- container_medium2 끝부분 -->
</div>

<script type="text/javascript">
    $(document).on('ready', function(e){
        $("#logOut").on("click", function() {
            location.href = "/logOut.php";
        });

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

    });

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
