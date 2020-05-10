<?php
session_start();
//로그인 세션 없을때
if(!isset($_SESSION['userId'])){
    echo("<script>location.href='/index.php';</script>");
//로그인 세션 있을때
}else{

}

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
        <table class="table">
            <colgroup>
                <col width="8%">
                <col width="10px">
                <col width="15%">
                <col width="15%">
                <col width="8%">
            </colgroup>
            <thead>
            <tr>
                <th style="text-align: center;">번호</th>
                <th style="text-align: center;">제목</th>
                <th style="text-align: center;">작성자</th>
                <th style="text-align: center;">작성일</th>
            </tr>
            </thead>
            <tbody style="text-align: center;">
            <!-- 게시판 목록 가져오기 -->
            <?php
            include '../DB/DBConnection.php';

            //게시글 시작위치
            $limit = ($pageNum-1)*$list;

            $sql = "select * from travelPlan order by travel_plan_no desc";
            $result = $conn->query($sql);
            while($row = $result->fetch_assoc()) {
                $datetime = explode(' ', $row['update_date']);
                $date = $datetime[0];
                $time = $datetime[1];
                if($date == Date('Y-m-d'))
                    $row['update_date'] = $time;
                else
                    $row['update_date'] = $date;
                ?>
                <tr class="freeBoardHover" onclick="goTravelPlanView(<?php echo $row['travel_plan_no'] ?>)">
                    <td><?php echo $row['travel_plan_no']?></td>
                    <td><div style="max-height:17px;overflow: hidden"; ><?php echo $row['title']?></div></td>
                    <td><div style="max-height:17px;overflow: hidden"; ><?php echo $row['writer_email']?></div></td>
                    <td><?php echo $row['update_date']?></td>
                </tr>
                <?php
            }
            $conn->close();
            ?>
            </tbody>
        </table>

        <!-- 여행 리스트 -->
        <div class="plan_list">
            <!-- 게시판 목록 가져오기 -->
            <?php
            include '../DB/DBConnection.php';

            $sql = "select travel_plan_no, count(day) as day_count, title, writer_email, writer_nick_name, thumnail_image, travel_start_date   from (
                        select A.travel_plan_no, title, writer_email, writer_nick_name, thumnail_image, travel_start_date, day 
                        from travelPlan A
                        inner join travelPlanDetail B
                        on A.travel_plan_no = B.travel_plan_no
                        group by travel_plan_no, day
                    )A
                    group by A.travel_plan_no
                    order by travel_plan_no desc";
            $result = $conn->query($sql);
            while($row = $result->fetch_assoc()) {
                $datetime = explode(' ', $row['travel_start_date']);
                $date = $datetime[0];
                $row['travel_start_date'] = $date;
                ?>
                <div class="plan_item fl">
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
                            <span>&nbsp;Like(0)</span>
                            <i class="far fa-bookmark fas" style="font-size: 20px; margin-left: 8px;"></i>
                            <span>&nbsp;Bookmark(0)</span>
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


        </div>

    </div>

    <div class="container_medium">
        <!-- 버튼 -->
        <div style="margin-bottom:100px; float:right;">
            <button id="travel_plan_write_btn" class="btn" style="background: #ffe8d6; font-weight:bold; font-size: 18px;">여행 일정 만들기</button>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).on('ready', function(e){
        $("#travel_plan_write_btn").on("click", function() {
            location.href = "/travelPlanWrite.php";
        });

    });

    function goTravelPlanView(travel_plan_no) {
        console.log("travel_plan_no:",travel_plan_no);
        location.href='/travelPlanWrite.php?travel_plan_no='+travel_plan_no;
    }
</script>



</body>
</html>
