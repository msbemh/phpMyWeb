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

<!--    <ul>-->
<!--        <li onclick="goHome()">홈</li>-->
<!--        <li onclick="goFreeBoard()">자유 게시판</li>-->
<!--        <li onclick="goHome()">Contact</li>-->
<!--        <li onclick="goHome()">About</li>-->
<!--        <li onclick="goHome()">About</li>-->
<!--    </ul>-->
    <div>
        <nav class="navbar navbar-default">
            <div class="container-fluid">
                <!-- Brand and toggle get grouped for better mobile display -->
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" style="background: #e7e7e7" href="/main.php">Home</a>
                </div>

                <!-- Collect the nav links, forms, and other content for toggling -->
                <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                    <ul class="nav navbar-nav">
                        <li><a href="/freeBoard.php">자유게시판</a></li>
                        <li><a href="#">여행일정</a></li>
                    </ul>
                </div><!-- /.navbar-collapse -->
            </div><!-- /.container-fluid -->
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
