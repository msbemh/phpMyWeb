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
    <link rel="stylesheet" href="css/trevelPlan.css" />
</head>
<body>

<!-- 상단 부분 -->
<div class="container" style="height: 100%;">
    <?php include './topPart.php'?>
</div>


<!-- 여행일정 부분 -->
<div class="container_big" style="height: 100%;">
    <nav id="top_menu" class="fl">
        <div class="sign">날짜</div>
        <ul>
            <li class="day">
                <div>DAY1</div>
            </li>
            <li class="day">
                <div>DAY2</div>
            </li>
            <li class="day">
                <div>DAY3</div>
            </li>
            <li class="day_plus">
                <i class="fas fa-plus-circle"></i>
            </li>
        </ul>
    </nav>

    <nav id="mega_menu" class="fl">
        <div class="sign_btn">
            <span>관광명소 추천(서울)</span>
            <button class="btn">지역 변경</button>
        </div>
        <ul>
            <li>
                <div class="data_piece">
                    <div class ="img_box fl">
                        <img src="http://img.earthtory.com/img/place_img/310/6725_0_et.jpg"></img>
                    </div>
                    <div class ="info_box fl">
                        <div class="title">북촌 한옥마을</div>
                        <div class="sub">유명한거리/지역</div>
                    </div>
                    <div class="item_add_remove">
                        <i class="fas fa-plus-circle"></i>
                    </div>
                </div>
            </li>
            <li>
                <div class="data_piece">
                    <div class ="img_box fl">
                        <img src="http://img.earthtory.com/img/place_img/310/6725_0_et.jpg"></img>
                    </div>
                    <div class ="info_box fl">
                        <div class="title">북촌 한옥마을</div>
                        <div class="sub">유명한거리/지역</div>
                    </div>
                    <div class="item_add_remove">
                        <i class="fas fa-plus-circle"></i>
                    </div>
                </div>
            </li>
            <li>
                <div class="data_piece">
                    <div class ="img_box fl">
                        <img src="http://img.earthtory.com/img/place_img/310/6725_0_et.jpg"></img>
                    </div>
                    <div class ="info_box fl">
                        <div class="title">북촌 한옥마을</div>
                        <div class="sub">유명한거리/지역</div>
                    </div>
                    <div class="item_add_remove">
                        <i class="fas fa-plus-circle" ></i>
                    </div>
                </div>
            </li>
        </ul>
    </nav>

    <nav id="major_menu" class="fl">
        <div class="sign">나의 여행 장소</div>
        <ul>
            <li>
                <div class="data_piece">
                    <div class ="img_box fl">
                        <img src="http://img.earthtory.com/img/place_img/310/6725_0_et.jpg"></img>
                    </div>
                    <div class ="info_box fl">
                        <div class="title">북촌 한옥마을</div>
                        <div class="sub">유명한거리/지역</div>
                    </div>
                    <div class="item_add_remove">
                        <i class="fas fa-minus-circle"></i>
                    </div>
                </div>
            </li>

        </ul>
    </nav>
    <!-- 지도를 표시할 div 입니다 -->
    <div id="map" style="width:770px;height:600px; float:left;"></div>

</div>


<script type="text/javascript">
    $(document).on('ready', function(e){
        $("#travel_plan_write_btn").on("click", function() {
            location.href = "/travelPlanWrite.php";
        });

        $("#top_menu .day_plus").on("click", function() {
            let $side_ul = $("#top_menu ul")
            let length = $side_ul.children().size();
            console.log("$side_ul:",$side_ul);
            console.log("length:",length);

            let html =
                    "<li class=\"day\">\n" +
                        "<div>DAY"+length+"</div>\n" +
                    "</li>"
            $side_ul.children(":last").before(html);
        });

    });



    /* 관광 openapi 관련 javascript */
    // var xhr = new XMLHttpRequest();
    // var url = 'http://api.visitkorea.or.kr/openapi/service/rest/KorService/areaCode'; /*URL*/
    //
    //
    // var queryParams = '?' + encodeURIComponent('ServiceKey') + '=' + 'wiuAeP9MxfGYlDgQlH0QpjWnae39cLALhV3DUy56I5Wj8tPr%2FlmSseHS4EFRtHueUdeglqa7Z85FebCXBqJQPg%3D%3D'; /**/
    // queryParams += '&' + encodeURIComponent('numOfRows') + '=' + encodeURIComponent('10'); /**/
    // queryParams += '&' + encodeURIComponent('pageNo') + '=' + encodeURIComponent('1'); /**/
    // queryParams += '&' + encodeURIComponent('MobileOS') + '=' + encodeURIComponent('WIN'); /**/
    // queryParams += '&' + encodeURIComponent('MobileApp') + '=' + encodeURIComponent('AppTest'); /**/
    // queryParams += '&' + encodeURIComponent('areaCode') + '=' + encodeURIComponent('1'); /**/
    // queryParams += '&_type=json'; /**/
    // xhr.open('GET', url + queryParams);
    // xhr.onreadystatechange = function () {
    //     if (this.readyState == 4) {
    //         alert('Status: '+this.status+'nHeaders: '+JSON.stringify(this.getAllResponseHeaders())+'nBody: '+this.responseText);
    //     }
    // };
    //
    // xhr.send('');


</script>

<!-- 구글맵 관련 javascript -->
<script type="text/javascript" src="//dapi.kakao.com/v2/maps/sdk.js?appkey=db020925d06b61dd2f0089235b1f2b3a"></script>

<script>
    var mapContainer = document.getElementById('map'), // 지도를 표시할 div
        mapOption = {
            center: new kakao.maps.LatLng(33.450701, 126.570667), // 지도의 중심좌표
            level: 3 // 지도의 확대 레벨
        };

    // 지도를 표시할 div와  지도 옵션으로  지도를 생성합니다
    var map = new kakao.maps.Map(mapContainer, mapOption);


</script>


</body>
</html>
