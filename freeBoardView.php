<?php
session_start();
//로그인 세션 없을때
if(!isset($_SESSION['userId'])){
    echo("<script>location.href='/index.php';</script>");
//로그인 세션 있을때
}else{

}


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

//좋아요 정보 가져오기
$sql = "SELECT count(*) FROM freeBoardLikes WHERE freeBoardNo = $idx";
$result = $conn->query($sql);
$row = $result->fetch_assoc();
$likes = $row["count(*)"];

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
        <div style=" height: 75px; position: relative; border-top: 3px solid silver; border-bottom: 3px solid silver; margin-top:30px; margin-bottom: 30px;">
            <div id="like" style="position: absolute; left:9%; top: 50%; transform: translateY(-50%); cursor:pointer;">
                <i class="far fa-heart" style="font-size: 40px;"></i>
                <span style="font-size: 30px;position: absolute;top: 50%; -ms-transform: translateY(-50%); transform: translateY(-50%);">&nbsp;Like(<span id="like_num" ><?php echo $likes?></span>)</span>
            </div>
            <div style="position: absolute; left:43%; top: 50%; transform: translate(-50%,-50%); cursor:pointer;">

            </div>
            <div style="position: absolute; right:25%; top: 50%; transform: translateY(-50%); cursor:pointer;">
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
            //좋아요 이미 눌려진 상태라면
            if($svg.hasClass("fas")){
                like_num_value -= 1;
                $like_num.html(like_num_value);
            //좋아요 이미 누르지 않은 상태라면
            }else{
                like_num_value += 1;
                $like_num.html(like_num_value);
            }
            $svg.toggleClass("fas");
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
