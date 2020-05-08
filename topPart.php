<!-- 상단 부분 -->
<div style="position:relative; height: 80px">
    <h1 id="logo" style="float:left; cursor:pointer;"  >여행 일정</h1>
    <div style="float:right; margin:20px 0px;">
        <button id="logOut" class="btn" style="background: #ffe8d6; font-weight:bold; font-size: 18px;">로그아웃</button>
    </div>
    <div style="float:right; margin:20px 0px; padding: 6px 12px; font-weight:bold; font-size: 18px;"><?php echo "{$_SESSION["nickName"]}" ?> 님</div>
</div>
<div style="clear: both"></div>

<script type="text/javascript">
    $(document).on('ready', function(e) {
        $("#logOut").on("click", function () {
            location.href = "/logOut.php";
        });

        $("#logo").on("click", function () {
            if("<?=$_SESSION["userId"]?>" == "admin@naver.com"){
                location.href = "/adminMain.php";
            }else{
                location.href = "/main.php";
            }

        });
    });
</script>