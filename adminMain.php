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
<div class="container" style="min-width:550px;">
    <!-- 상단 부분 -->
    <?php include './topPart.php'?>
</div>
<div class="container_big" style="height: 100%; margin:20px;">
    <nav class="side_bar fl">
        <div class="sign">네비게이션</div>
        <ul>
            <li>
                <a href="/adminMain.php">메인</a>
            </li>
            <li>
                <a href="/adminMain.php">DAY2</a>
            </li>
            <li>
                <a href="/adminMain.php">DAY3</a>
            </li>
        </ul>
    </nav>
</div>

<script type="text/javascript">
    $(document).on('ready', function(e){

    });


</script>



</body>
</html>
