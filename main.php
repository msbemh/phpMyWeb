<?php
session_start();
//로그인 세션 없을때
if(!isset($_SESSION['userId'])){
    echo("<script>location.href='/index.php';</script>");
//로그인 세션 있을때
}else{

}

include './userLog.php';

?>
<!DOCTYPE html>
<html>
<head>
    <?php include './header.php'?>
</head>
<body>

<!-- 채팅 창 -->
<div style="position: relative; margin:10px;">
    <div style="position: absolute; right:0px; top:80px; height: 900px;">
        <iframe style="height: 100%; border:1px solid black; margin-bottom: 10px;" id="chat_iframe" src="https://wowtravel.tk:3000/socketTest.html"></iframe>
    </div>
</div>

<div class="container" style="min-width:550px; height: 500px;">

    <!-- 상단 부분 -->
    <?php include './topPart.php'?>

    <!-- 메뉴 -->
    <div class="container_medium">
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <a class="navbar-brand" href="/main.php" style="background: #E3E3E3">Home</a>
            <button class="navbar-toggler"  type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="/freeBoard.php">자유게시판</a>
                    </li>
                    <li class="nav-item" style="margin-left:10px;">
                        <a class="nav-link" href="/travelPlan.php">여행일정</a>
                    </li>
                </ul>
            </div>
        </nav>


<!--        <div style="height: 200px; position: relative; margin-bottom: 10px;">-->
<!--            <img style="position: absolute; top:0; left: 0; width: 100%; height: 100%;" src="/img/main_image.jpg" alt="/img/empty_image.png">-->
<!--        </div>-->


        <div class="main_board_title">[ 여행일정 게시판 인기글 TOP3 ]</div>
        <!-- 여행 리스트 -->
        <div class="plan_list">
            <!-- 게시판 목록 가져오기 -->
            <?php
            include '../DB/DBConnection.php';

            //게시글 시작위치
            $sql = "select C.*, ifnull(D.like_num,0) as like_num, ifnull(E.bookmark_num,0) as bookmark_num from(
                        select travel_plan_no, count(day) as day_count, title, writer_email, writer_nick_name, thumnail_image, travel_start_date, views   from (
                                                select A.travel_plan_no, title, writer_email, writer_nick_name, thumnail_image, travel_start_date, day, views 
                                                from travelPlan A
                                                inner join travelPlanDetail B
                                                on A.travel_plan_no = B.travel_plan_no
                                                group by travel_plan_no, day
                                            )A
                                            group by A.travel_plan_no) C
                    left outer join (select travel_plan_no, count(travel_plan_no) as like_num from travelBoardLikes group by travel_plan_no ) D
                    on C.travel_plan_no = D.travel_plan_no
                    left outer join (select travel_plan_no, count(travel_plan_no) as bookmark_num from travelBoardBookmark group by travel_plan_no ) E
                    on C.travel_plan_no = E.travel_plan_no
                    order by like_num desc, bookmark_num desc, views desc
                    limit 3";
            $result = $conn->query($sql);
            while($row = $result->fetch_assoc()) {
                $datetime = explode(' ', $row['travel_start_date']);
                $date = $datetime[0];
                $row['travel_start_date'] = $date;
                ?>
                <div class="plan_item fl" onclick="goTravelPlanView(<?php echo $row['travel_plan_no'] ?>)">
                    <div class="plan_img_box">
                        <img src="<?php echo $row['thumnail_image']?>" alt="My Image">
                        <div class="plan_img_box_info">
                            <span class="travel_start_date"><?php echo $row['travel_start_date'] ?></span>
                            <span class="day_count"><?php echo $row['day_count'] ?>DAYS</span>
                            <div class="title"><?php echo $row['title'] ?></div>
                        </div>
                    </div>
                    <div class="plan_info_box">
                        <div class="heart_bookmark">
                            <i class="far fa-heart fas" style="font-size: 20px; color:red;"></i>
                            <span class="like_num">&nbsp;(<?php echo $row['like_num'] ?>)</span>
                            <i class="far fa-bookmark fas" style="font-size: 20px; margin-left: 8px;"></i>
                            <span class="bookmark_num">&nbsp;(<?php echo $row['bookmark_num'] ?>)</span>
                            <i class="fas fa-eye" style="font-size: 20px; margin-left: 8px;"></i>
                            <span class="view_num">&nbsp;(<?php echo $row['views'] ?>)</span>
                        </div>
                        <div class="email_nick_name">
                            <div class="writer_email"><?php echo $row['writer_email'] ?></div>
                            <div class="writer_nick_name"><?php echo $row['writer_nick_name'] ?></div>
                        </div>
                    </div>
                </div>
                <?php
            }
            $conn->close();
            ?>
        </div><!-- 여행리스트 끝 -->
        <div style="clear: both;"></div>

        <div class="main_board_title">[ 자유게시판 인기글 TOP5 ]</div>
        <table class="table">
            <colgroup>
                <col width="7%">
                <col width="">
                <col width="20%">
                <col width="15%">
                <col width="7%">
                <col width="7%">
                <col width="7%">
            </colgroup>
            <thead>
            <tr>
                <th style="text-align: center;">번호</th>
                <th style="text-align: center;">제목</th>
                <th style="text-align: center;">작성자</th>
                <th style="text-align: center;">최근 작성일</th>
                <th style="text-align: center;">조회수</th>
                <th style="text-align: center;">좋아요</th>
                <th style="text-align: center;">북마크</th>
            </tr>
            </thead>
            <tbody style="text-align: center;">
            <!-- 게시판 목록 가져오기 -->
            <?php
            include '../DB/DBConnection.php';

            $sql = "select C.*, ifnull(D.like_num, 0) as like_num, ifnull(E.comment_num, 0) as comment_num, ifnull(F.bookmark_num, 0) as bookmark_num  from(
                        select A.*, B.nickName  from freeBoard A
                        inner join user B
                        on A.writer = B.userId) C
                        
                    left outer join (select freeBoardNo, count(freeBoardNo) as like_num from freeBoardLikes group by freeBoardNo) D
                    on C.idx = D.freeBoardNo
                    
                    left outer join (select board_no, count(board_no) as comment_num from freeBoardComment group by board_no) E
                    on C.idx = E.board_no
                    
                    left outer join (select freeBoardNo, count(freeBoardNo) as bookmark_num from freeBoardBookmark group by freeBoardNo) F
                    on C.idx = F.freeBoardNo
                    order by views desc, like_num desc, bookmark_num desc, comment_num desc  limit 5";
            $result = $conn->query($sql);
            while($row = $result->fetch_assoc()) {
                $datetime = explode(' ', $row['updateDate']);
                $date = $datetime[0];
                $time = $datetime[1];
                $comment_num = $row['comment_num'];
                if($date == Date('Y-m-d'))
                    $row['updateDate'] = $time;
                else
                    $row['updateDate'] = $date;
                ?>
                <tr class="freeBoardHover" onclick="goFreeBoardView(<?php echo $row['idx'] ?>)">
                    <td><?php echo $row['idx']?></td>
                    <td><?php echo $row['title'];
                        if($row['comment_num']>0){
                            echo "<span class='comment_num'>&nbsp;&nbsp;[$comment_num]</span>";
                        }?></td>
                    <td><?php echo $row['nickName'].'<br>( '.$row['writer'].' ) '?></td>
                    <td><?php echo $row['updateDate']?></td>
                    <td><?php echo $row['views']?></td>
                    <td><?php echo $row['like_num']?></td>
                    <td><?php echo $row['bookmark_num']?></td>
                </tr>
                <?php
            }
            $conn->close();
            ?>
            </tbody>
        </table>
    </div>
</div>

<script type="text/javascript">

    function sendMessageIframe(){
        let iframe = document.getElementById('chat_iframe').contentWindow;
        // iframe.postMessage({ parentData : 'test parent data', 'http://123.com'});
    }

    $(document).on('ready', function(e){



    });
    function goHome() {
        location.href='/main.php';
    }
    function goFreeBoard() {
        location.href='/freeBoard.php';
    }
    function goHome() {
        location.href='/main.php';
    }
    function goHome() {
        location.href='/main.php';
    }

    function goTravelPlanView(travel_plan_no) {
        console.log("travel_plan_no:",travel_plan_no);
        location.href='/travelPlanView.php?travel_plan_no='+travel_plan_no;
    }

    function goFreeBoardView(idx) {
        console.log("idx:",idx);
        location.href='/freeBoardView.php?idx='+idx;
    }
</script>



</body>
</html>
