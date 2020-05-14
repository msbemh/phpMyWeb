<!--세션확인하기-->
<?php
session_start();

//로그인 세션 없을때
if(!isset($_SESSION['userId'])){

//로그인 세션 있을때
}else{
    echo("<script>location.href='/main.php';</script>");
}

//include './userLogo.php';

?>
<html>
<?php include './header.php'?>
<body>

<div style="position:relative; height: 500px">
    <div class="container" style="position: absolute; top:50%; left:50%; transform: translateX(-50%) translateY(-50%) ;width:400px;">
        <form id ="loginForm" >
            <h1 style="text-align:center" >로그인</h1>
            <input type="text" name="userId" class="form-control" id="userId" placeholder="Email address"/><br>
            <input type="password" name="userPassword" class="form-control" id="inputPassword" placeholder="Password"/><br>
            <div style="text-align:center;">
                <label  style="cursor:pointer"><input id="keep_id" type="checkbox" style="cursor:pointer">아이디 임시 저장</label>
            </div>

            <button id ="loginBtn" style="display: block; width:100%;" type="button" name="login" class="btn btn-warning">로그인</button><br>
            <button id ="signUpBtn" style="display: block; width:100%;" type="button" name="signUp" class="btn ">회원가입</button>
            </br>
        </form>
    </div>
</div>
</body>
</html>
<script type="text/javascript">

    $(document).on('ready', function(e){
        $("#loginBtn").on("click", function() {
            let data = $('#loginForm').serialize();
            console.log("data:",data);
            $.ajax({
                type: "POST",
                url : "/loginCheck.php",
                data: data,
                dataType:"json",
                success : function(data, status, xhr) {
                    console.log("data:",data);
                    //로그인 성공 했을 경우
                    if(data.result){
                        //로그인상태유지 체크박스 확인
                        if($("#keep_id").prop("checked")){
                            //쿠키생성
                            setCookie('userId', $("#userId").val(), 7); /* pop=event0405, 7일 뒤 만료됨 */
                        }else{
                            deleteCookie('userId');
                        }

                        if(data.userId == "admin@naver.com"){
                            location.href = "/adminMain.php";
                        }else{
                            location.href = "/main.php";
                        }

                    //실패 했을 경우
                    }else{
                        alert("아이디와 비밀번호가 맞지 않습니다.");
                        $("#inputPassword").val("");
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(jqXHR.responseText);
                }
            });
        });

        $("#signUpBtn").on("click", function() {
            location.href = "/signUp.php";
        });

        //로그인상태 유지 쿠키가 존재한다면
        if(getCookie('userId')){
            $("#keep_id").attr("checked", true);
            $("#userId").val(getCookie('userId'));
        }

    });

    //쿠기 생성
    function setCookie(name, value, exp) {
        let date = new Date();
        date.setTime(date.getTime() + exp*24*60*60*1000);
        document.cookie = name + '=' + value + ';expires=' + date.toUTCString() + ';path=/';
    }
    //쿠키 가져오기
    function getCookie(name) {
        var value = document.cookie.match('(^|;) ?' + name + '=([^;]*)(;|$)');
        return value? value[2] : null;
    }
    //쿠키 삭제
    function deleteCookie(name) {
        document.cookie = name + '=; expires=Thu, 01 Jan 1970 00:00:01 GMT;';
    }
</script>
