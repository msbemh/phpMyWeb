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
    <div style="position:relative; height: 80px">
        <h1 style="float:left;">여행 일정</h1>
        <div style="float:right; margin:20px 0px;">
            <button id="logOut" class="btn" style="background: #ffe8d6; font-weight:bold; font-size: 18px;">로그아웃</button>
        </div>
        <div style="float:right; margin:20px 0px; padding: 6px 12px; font-weight:bold; font-size: 18px;"><?php echo "{$_SESSION["nickName"]}" ?> 님</div>
    </div>
    <div style="clear: both"></div>
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
                        <a class="nav-link" href="/freeBoard.php">여행일정</a>
                    </li>
                </ul>
            </div>
        </nav>


        <div style="height: 200px; position: relative; margin-bottom: 10px;">
            <img style="position: absolute; top:0; left: 0; width: 100%; height: 100%;" src="/img/main_image.jpg" alt="/img/empty_image.png">
        </div>
    </div>

</div>
<script type="text/javascript">
    $(document).on('ready', function(e){
        console.log("들어옴");
        $("#logOut").on("click", function() {
            console.log("들어옴");
            location.href = "/logOut.php";
        });

        $("#signUpBtn").on("click", function() {
            location.href = "/signUp.php";
        });
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
</script>



</body>
</html>
