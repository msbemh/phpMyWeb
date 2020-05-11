<?php
session_start();
//로그인 세션 없을때
if(!isset($_SESSION['userId'])){
    echo("<script>location.href='/index.php';</script>");
//로그인 세션 있을때
}else{

}

//페이징 관련 변수들 지정
$page_num = ($_GET['page']) ? $_GET['page'] : 1; //page : default = 1
$list = ($_GET['list']) ? $_GET['list'] : 9; //page : default = 10

$block_page_num_list = 10; //블럭에 나타낼 페이지 번호 갯수
$block = ceil($page_num/$block_page_num_list); //현재 리스트의 블럭 구하기

$block_start_page = ( ($block - 1) * $block_page_num_list ) + 1; //현재 블럭에서 시작페이지 번호
$block_end_page = $block_start_page + $block_page_num_list - 1; //현재 블럭에서 마지막 페이지 번호

?>
<!DOCTYPE html>
<html>
<head>
    <?php include './header.php'?>
</head>
<body>
<div class="container" style="min-width:550px; height: 500px;">

    <!-- 상단 부분 -->
    <?php include './topPart.php'?>

    <!-- 메뉴 -->
    <div class="container_medium">
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <a class="navbar-brand" href="/main.php">Home</a>
            <button class="navbar-toggler"  type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="/freeBoard.php">자유게시판</a>
                    </li>
                    <li class="nav-item" style="margin-left:10px; background: #E3E3E3">
                        <a class="nav-link" href="/travelPlan.php">여행일정</a>
                    </li>
                </ul>
            </div>
        </nav>
    </div>

    <div class="container_medium">
        <!-- 여행 리스트 -->
        <div class="plan_list">
            <!-- 게시판 목록 가져오기 -->
            <?php
            include '../DB/DBConnection.php';

            //게시글 시작위치
            $limit = ($page_num-1)*$list;

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
                    order by C.travel_plan_no desc
                    limit $limit,$list";
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
    </div>

    <div class="container_medium">
        <!-- 버튼 -->
        <div style="float:right;">
            <button id="travel_plan_write_btn" class="btn" style="background: #ffe8d6; font-weight:bold; font-size: 18px;">여행 일정 만들기</button>
        </div>
        <div style="clear: both;"></div>
    </div>

    <!-- 페이징 처리 -->
    <nav aria-label="Page navigation" style="text-align: center;">
        <ul class="pagination">
            <?php
            include '../DB/DBConnection.php';
            //DB에서 총 데이터 개수 가져오기
            $sql = "SELECT COUNT(*) FROM travelPlan";
            $result = $conn->query($sql);
            $row = $result->fetch_assoc();
            $total_count = $row["COUNT(*)"];

            $total_page =  ceil($total_count/$list); //총 페이지 수

            if ($block_end_page > $total_page)
                $block_end_page = $total_page;


            $before = $block_start_page-1;
            //페이징 block단위로 뒤로가기
            if($block != 1){?>
                <li><a href="/travelPlan.php?page=<?=$before?>">이전</a></li>
                <?php
            }

            //페이징 숫자부분
            for($i = $block_start_page; $i <=$block_end_page; $i++) {
                if($page_num == $i){?>
                    <li><a style="background: silver;"><?=$i?></a></li>
                    <?php
                }else{?>
                    <li><a href="/travelPlan.php?page=<?=$i?>"><?=$i?></a></li>
                    <?php
                }
            }


            $next = $block_end_page+1;
            if($next>=$total_page){
                $next = $total_page;
            }
            $total_block = ceil($total_page/$block_page_num_list);
            //페이징 block단위로 다음으로
            if($block != $total_block){?>
                <li><a href="/travelPlan.php?page=<?=$next?>">다음</a></li>
                <?php
            }

            $conn->close();
            ?>

        </ul>
    </nav>

</div>
<script type="text/javascript">
    $(document).on('ready', function(e){
        $("#travel_plan_write_btn").on("click", function() {
            location.href = "/travelPlanWrite.php";
        });

    });

    function goTravelPlanView(travel_plan_no) {
        console.log("travel_plan_no:",travel_plan_no);
        location.href='/travelPlanView.php?travel_plan_no='+travel_plan_no;
    }
</script>



</body>
</html>
