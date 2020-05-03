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
        <!-- 버튼 -->
        <div style="margin-bottom:100px; float:right;">
            <button id="travel_plan_write_btn" class="btn" style="background: #ffe8d6; font-weight:bold; font-size: 18px;">여행 일정 만들기</button>
        </div>
        <!-- 지도를 표시할 div 입니다 -->
        <div id="map" style="width:100%;height:350px;"></div>
    </div>
</div>
<script type="text/javascript">
    $(document).on('ready', function(e){
        $("#travel_plan_write_btn").on("click", function() {
            location.href = "/travelPlanWrite.php";
        });

    });
</script>



</body>
</html>
