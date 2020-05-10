<?php

session_start();
//로그인 세션 없을때
if(!isset($_SESSION['userId'])){
    echo("<script>location.href='/index.php';</script>");
//로그인 세션 있을때
}else{

}

//DB에서 여행일정 정보 가져오기
include '../DB/DBConnection.php';

//GET로 값 전달 받아야함
$travel_plan_no = -1;
if(isset($_GET["travel_plan_no"])){
    $travel_plan_no = $_GET["travel_plan_no"];
}


$sql = "SELECT * FROM travelPlan WHERE travel_plan_no = $travel_plan_no";
$result = $conn->query($sql);
$row = $result->fetch_assoc();
$title = $row["title"];
$travel_start_date = $row["travel_start_date"];
$travel_start_date_split = explode(' ', $travel_start_date);
$travel_start_date_format = $travel_start_date_split[0];


$sql = "SELECT * FROM travelPlan A
        INNER JOIN travelPlanDetail B
        ON A.travel_plan_no = B.travel_plan_no
        WHERE A.travel_plan_no = $travel_plan_no";
$result = $conn->query($sql);
$my_travel_list = array();

while($row = $result->fetch_assoc()) {
    $my_travel_list[] = $row;
}

//DAY가 종류별로 몇개있는지 세기
$sql = "SELECT count(D.day) as cnt FROM(
            SELECT day FROM(
                SELECT A.travel_plan_no, travel_plan_detail_no, day FROM travelPlan A
                INNER JOIN travelPlanDetail B
                ON A.travel_plan_no = B.travel_plan_no
                WHERE A.travel_plan_no = $travel_plan_no
            ) C
            GROUP BY C.day
        ) AS D";
$result = $conn->query($sql);
$row = $result->fetch_assoc();
$day_count = $row["cnt"];

$conn->close();

?>

<!DOCTYPE html>
<html>
<head>
    <?php include './header.php'?>
    <link rel="stylesheet" href="css/trevelPlan.css" />
    <script type="text/javascript" src="./util.js"></script>
    <!-- data picker를 위헌 css,js 추가 -->
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
</head>
<body>

<!-- 상단 부분 -->
<div class="container" style="height: 100%;">
    <?php include './topPart.php'?>
</div>


<!-- 여행일정 부분 -->
<div class="container_big sign_table">
    <div class="fl" style="display: inline-block;">
        <span>제목 : </span><input id="title_input" class="title" />
        <span>시작날짜 : </span><input id="start_date_input" class="start_date" disabled readonly/>
    </div>
    <button id ="save" class="btn fr" style="width: 500px; background: black; color:white;">여행일정 저장</button>
</div>
<div class="container_big" style="height: 100%;">
    <nav id="top_menu" class="fl">
        <div class="sign">날짜</div>
        <ul>
<!--            <li class="day day_on" data-day="1">-->
<!--                <div>DAY1</div>-->
<!--            </li>-->
<!--            <li class="day" data-day="2">-->
<!--                <div>DAY2</div>-->
<!--            </li>-->
<!--            <li class="day" data-day="3">-->
<!--                <div>DAY3</div>-->
<!--            </li>-->
<!--            <li class="day_plus">-->
<!--                <i class="fas fa-plus-circle"></i>-->
<!--            </li>-->
        </ul>
    </nav>

    <nav id="mega_menu" class="fl">
        <div class="sign_btn">
            <span>관광명소 추천(서울)</span>
            <button class="btn">지역 변경</button>
        </div>
        <ul>
<!--            <li>-->
<!--                <div class="data_piece" data-latitude="37.582419" data-longitude="126.983650">-->
<!--                    <div class ="img_box fl">-->
<!--                        <img src="http://img.earthtory.com/img/place_img/310/6725_0_et.jpg"></img>-->
<!--                    </div>-->
<!--                    <div class ="info_box fl">-->
<!--                        <div class="title_detail">북촌 한옥마을</div>-->
<!--                        <div class="sub">유명한거리/지역</div>-->
<!--                    </div>-->
<!--                    <div class="item_add">-->
<!--                        <i class="fas fa-plus-circle"></i>-->
<!--                    </div>-->
<!--                </div>-->
<!--            </li>-->
<!--            <li>-->
<!--                <div class="data_piece" data-latitude="37.551331" data-longitude="126.988227">-->
<!--                    <div class ="img_box fl">-->
<!--                        <img src="http://img.earthtory.com/img/place_img/310/6645_0_et.jpg"></img>-->
<!--                    </div>-->
<!--                    <div class ="info_box fl">-->
<!--                        <div class="title_detail">N서울타워</div>-->
<!--                        <div class="sub">랜드마크, 전경/야경</div>-->
<!--                    </div>-->
<!--                    <div class="item_add">-->
<!--                        <i class="fas fa-plus-circle"></i>-->
<!--                    </div>-->
<!--                </div>-->
<!--            </li>-->
<!--            <li>-->
<!--                <div class="data_piece" data-latitude="37.579796" data-longitude="126.977020" >-->
<!--                    <div class ="img_box fl">-->
<!--                        <img src="http://img.earthtory.com/img/place_img/310/6638_0_et.jpg"></img>-->
<!--                    </div>-->
<!--                    <div class ="info_box fl">-->
<!--                        <div class="title_detail">경복궁</div>-->
<!--                        <div class="sub">랜드마크, 성.궁궐</div>-->
<!--                    </div>-->
<!--                    <div class="item_add">-->
<!--                        <i class="fas fa-plus-circle" ></i>-->
<!--                    </div>-->
<!--                </div>-->
<!--            </li>-->
<!--            <li>-->
<!--                <div class="data_piece" data-latitude="37.576020" data-longitude="126.976809">-->
<!--                    <div class ="img_box fl">-->
<!--                        <img src="http://img.earthtory.com/img/place_img/310/6661_0_et.jpg"></img>-->
<!--                    </div>-->
<!--                    <div class ="info_box fl">-->
<!--                        <div class="title_detail">광화문</div>-->
<!--                        <div class="sub">역사적 명소</div>-->
<!--                    </div>-->
<!--                    <div class="item_add">-->
<!--                        <i class="fas fa-plus-circle" ></i>-->
<!--                    </div>-->
<!--                </div>-->
<!--            </li>-->
        </ul>
    </nav>

    <nav id="major_menu" class="fl">
        <div class="sign">나의 여행 장소</div>
        <ul>
<!--            <li>-->
<!--                <div class="data_piece">-->
<!--                    <div class="spot_order_box">1</div>-->
<!--                    <div class ="img_box fl">-->
<!--                        <img src="http://img.earthtory.com/img/place_img/310/6725_0_et.jpg"></img>-->
<!--                    </div>-->
<!--                    <div class ="info_box fl">-->
<!--                        <div class="title_detail">북촌 한옥마을</div>-->
<!--                        <div class="sub">유명한거리/지역</div>-->
<!--                    </div>-->
<!--                    <div class="item_remove">-->
<!--                        <i class="fas fa-minus-circle"></i>-->
<!--                    </div>-->
<!--                </div>-->
<!--            </li>-->
        </ul>
    </nav>
    <!-- 지도를 표시할 div 입니다 -->
    <div id="map" style="width:747px;height:600px; float:left;"></div>

</div>

<!-- 카카오맵 관련 javascript -->
<script type="text/javascript" src="//dapi.kakao.com/v2/maps/sdk.js?appkey=db020925d06b61dd2f0089235b1f2b3a"></script>

<script type="text/javascript">
    //카카오맵 map 초기화
    let selectedMarker = null; // 클릭한 마커를 담을 변수
    let opened_window_info = null;

    let mapContainer = document.getElementById('map'), // 지도를 표시할 div
        mapOption = {
            center: new kakao.maps.LatLng(37.565700, 126.977080), // 지도의 중심좌표
            level: 7 // 지도의 확대 레벨
        };
    let map = new kakao.maps.Map(mapContainer, mapOption); // 지도를 생성합니다


    // 임시 데이터 생성(관광명소 추천)
    let travel_api_list = new Array();
    let array = {};
    array.title_detail = "북촌 한옥마을";
    array.sub = "유명한거리/지역";
    array.latitude = 37.582419;
    array.longitude = 126.983650;
    array.image = "http://img.earthtory.com/img/place_img/310/6725_0_et.jpg";
    travel_api_list.push(array);

    array = {};
    array.title_detail = "N서울타워";
    array.sub = "랜드마크, 전경/야경";
    array.latitude = 37.551331;
    array.longitude = 126.988227;
    array.image = "http://img.earthtory.com/img/place_img/310/6645_0_et.jpg";
    travel_api_list.push(array);

    array = {};
    array.title_detail = "경복궁";
    array.sub = "랜드마크, 성.궁궐";
    array.latitude = 37.579796;
    array.longitude = 126.977020;
    array.image = "http://img.earthtory.com/img/place_img/310/6638_0_et.jpg";
    travel_api_list.push(array);

    array = {};
    array.title_detail = "광화문";
    array.sub = "역사적 명소";
    array.latitude = 37.576020;
    array.longitude = 126.976809;
    array.image = "http://img.earthtory.com/img/place_img/310/6661_0_et.jpg";
    travel_api_list.push(array);

    console.log("travel_api_list:",travel_api_list);


    let my_travel_list = <?= json_encode($my_travel_list) ?>;

    //카카오 지도에 현재 선택된 날짜의 여행리스트 보내주기 위해서 만듦.
    let current_day_travel_list = [];
    //카카오 지도에서 나의 marker list
    let my_marker_list = [];
    //카카오 지도오에서 경로 선 list
    let polyline_list = [];

    console.log("my_travel_list:",my_travel_list);

    //관광명소 추천 리로드
    travel_api_reload();

    //나의 여행장소 리로드
    my_location_reload(1);

    //나의 DAY 로드
    my_day_load();

    //---------------------------------------------[구글맵 관련]---------------------------------------------------
    // let selectedMarker = null; // 클릭한 마커를 담을 변수
    // let opened_window_info = null;
    //
    // let mapContainer = document.getElementById('map'), // 지도를 표시할 div
    //     mapOption = {
    //         center: new kakao.maps.LatLng(37.565700, 126.977080), // 지도의 중심좌표
    //         level: 7 // 지도의 확대 레벨
    //     };


    // let map = new kakao.maps.Map(mapContainer, mapOption); // 지도를 생성합니다



    //윈도 인포 닫기
    function window_info_close(){
        if(opened_window_info != null){
            opened_window_info.close();
        }
    }

    //윈도 인포에서 나의여행장소 추가하기
    function window_info_add(latitude, longitude, image, title_detail, sub){
        let day = $("#top_menu .day_on").data("day");

        let data = {};
        data.title_detail = title_detail;
        data.latitude = latitude;
        data.longitude = longitude;
        data.sub = sub;
        data.image = image;
        data.day = day;

        //나의 여행장소 리스트에 push
        my_travel_list.push(data);

        //order_num 순서대로 다시 주기
        let order_num = 0;
        for(let i=0; i<my_travel_list.length; i++){
            if(day == my_travel_list[i].day){
                order_num++;
                my_travel_list[i].order_num= order_num;
            }

        }

        //나의 여행장소 리로드
        my_location_reload(day);
    }

    //나의 여행장소 마커표시하기(노란색)
    function kakao_show_my_marker(current_day_travel_list){
        console.log("current_day_travel_list:",current_day_travel_list);

        //기존에 나의 마커 삭제
        hide_markers();
        my_marker_list.length = 0;

        current_day_travel_list.forEach(function(item, index){
            let imageSrc = "/res/marker.png";
            if(item.order_num <11){
                imageSrc = "/res/marker_"+item.order_num+".png";
            }else{
                imageSrc = "/res/marker_what.png";
            }

            // 마커 이미지의 이미지 크기 입니다
            let imageSize = new kakao.maps.Size(50, 50);

            // 마커 이미지를 생성합니다
            let markerImage = new kakao.maps.MarkerImage(imageSrc, imageSize);

            // 마커가 표시될 위치입니다
            let markerPosition  = new kakao.maps.LatLng(item.latitude, item.longitude);

            // 마커를 생성합니다
            let marker = new kakao.maps.Marker({
                position: markerPosition,
                title: item.title_detail,
                image : markerImage // 마커 이미지
            });
            my_marker_list.push(marker);
            // 마커가 지도 위에 표시되도록 설정합니다
            marker.setMap(map);
        });
        console.log("my_marker_list:",my_marker_list);

    }


    function set_markers(map) {
        for (let i = 0; i < my_marker_list.length; i++) {
            my_marker_list[i].setMap(map);
        }
    }

    function hide_markers() {
        set_markers(null);
    }

    //경로 표시하기
    function kakao_route(current_day_travel_list) {
        hide_line();
        console.log("[카카오경로]current_day_travel_list:",current_day_travel_list);

        // 선을 구성하는 좌표 배열입니다. 이 좌표들을 이어서 선을 표시합니다
        let linePath = [];
        current_day_travel_list.forEach(function(item, index){
            linePath.push(new kakao.maps.LatLng(item.latitude, item.longitude))
        })

        // 지도에 표시할 선을 생성합니다
        let polyline = new kakao.maps.Polyline({
            path: linePath, // 선을 구성하는 좌표배열 입니다
            strokeWeight: 5, // 선의 두께 입니다
            strokeColor: '#000000', // 선의 색깔입니다
            strokeOpacity: 0.7, // 선의 불투명도 입니다 1에서 0 사이의 값이며 0에 가까울수록 투명합니다
            strokeStyle: 'solid' // 선의 스타일입니다
        });

        polyline_list.push(polyline);

        // 지도에 선을 표시합니다
        polyline.setMap(map);
    }

    function set_lines(map) {
        for (let i = 0; i < polyline_list.length; i++) {
            polyline_list[i].setMap(map);
        }
    }

    function hide_line() {
        set_lines(null);
    }

    //-------------------------------------------------------------------------------------------------------

    $(document).on('ready', function(e){

        $("#start_date_input").datepicker({
            showOn: "both",
            buttonImage: "/res/calendar.png",
            buttonImageOnly: true,
            buttonText: "Select date",
            dateFormat: 'yy-mm-dd'
        });

        $("#travel_plan_write_btn").on("click", function() {
            location.href = "/travelPlanWrite.php";
        });

        //날짜 추가(+) 버튼 클릭
        $("#top_menu .day_plus").on("click", function() {
            let $side_ul = $("#top_menu ul")
            let length = $side_ul.children().size();

            let html =
                    "<li class=\"day\" data-day=\""+length+"\">\n" +
                        "<div>DAY"+length+"</div>\n" +
                    "</li>"
            $side_ul.children(":last").before(html);


            //이벤트 초기화
            $(".day").off("click");
            //날짜(DAY) 버튼 클릭 이벤트 다시 추가
            $(".day").on("click", function() {
                console.log("$(this):",$(this));
                $(this).addClass("day_on");
                $(this).siblings().removeClass("day_on");

                console.log("데이터:",$(this).data('day'));

                let selected_day = $(this).data('day');

                //나의 여행장소 리로드
                my_location_reload(selected_day);

            });

        });

        //날짜(DAY) 버튼 클릭
        $(".day").on("click", function() {
            console.log("$(this):",$(this));
            $(this).addClass("day_on");
            $(this).siblings().removeClass("day_on");

            console.log("데이터:",$(this).data('day'));
            let selected_day = $(this).data('day');

            //나의 여행장소 리로드
            my_location_reload(selected_day);

        });

        //여행일정 저장
        $("#save").on("click", function() {

            let title_input = $("#title_input").val();
            let start_date_input = $("#start_date_input").val();
            let travel_plan_no = <?=$travel_plan_no?>;
            if(isEmpty(title_input)){
                alert("제목을 입력해주세요.");
                return;
            }else if(isEmpty(start_date_input)){
                alert("시작날짜를 입력해주세요.");
                return;
            }else if(my_travel_list.length == 0){
                alert("나의 여행장소가 없습니다.");
                return;
            }

            console.log("저장전 my_travel_list:",my_travel_list);

            $.ajax({
                type: "POST",
                url : "/travelPlanSave.php",
                data: {"title":title_input, "start_date" : start_date_input, "travel_plan_no": travel_plan_no, "my_travel_list": my_travel_list},
                dataType:"json",
                success : function(data, status, xhr) {
                    console.log("data:",data);
                    if(data.result){
                        alert("정상적으로 저장이 완료됐습니다.");
                        location.href = "/travelPlan.php";
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    alert("저장에 실패했습니다.")
                    console.log(jqXHR.responseText);
                }
            });

        });

        //여행일정 제목에 값넣어주기
        $("#title_input").val("<?=$title?>");

        //여행일정 시작날짜에 값넣어주기
        <?php ;?>
        $("#start_date_input").val("<?=$travel_start_date_format?>");


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



    //나의 여행장소 리로드 함수
    function my_location_reload(selected_day) {

        let $my_travel_ul = $("#major_menu ul");

        $my_travel_ul.html("");

        //초기화
        current_day_travel_list.length = 0;

        //나의 여행장소 order(순서)
        let order_num = 0;
        for(let i=0; i<my_travel_list.length; i++){
            let item = my_travel_list[i];
            // console.log("item:",item);
            if(item.day == selected_day){
                order_num++;
                let html =
                    "            <li>\n" +
                    "               <div class=\"data_piece\" data-latitude=\""+item.latitude+"\" data-longitude=\""+item.longitude+"\" data-order_num=\""+order_num+"\">\n" +
                    "                    <div class=\"spot_order_box\">"+order_num+"</div>\n"+
                    "                    <div class =\"img_box fl\">\n" +
                    "                        <img src=\""+item.image+"\"></img>\n" +
                    "                    </div>\n" +
                    "                    <div class =\"info_box fl\">\n" +
                    "                        <div class=\"title_detail\">"+item.title_detail+"</div>\n" +
                    "                        <div class=\"sub\">"+item.sub+"</div>\n" +
                    "                    </div>\n" +
                    "                    <div class=\"item_remove\">\n" +
                    "                        <i class=\"fas fa-minus-circle\"></i>\n" +
                    "                    </div>\n" +
                    "                </div>\n" +
                    "            </li>";
                $my_travel_ul.append(html);

                current_day_travel_list.push(item);

            }
        }
        //카카오 나의 여행장소 마커표시
        kakao_show_my_marker(current_day_travel_list);
        //카카오 경로표시
        kakao_route(current_day_travel_list);

        //이벤트 초기화
        $("#major_menu .item_remove").off("click");

        //나의 여행장소 (-)클릭 이벤트 추가
        $("#major_menu .item_remove").on("click", function() {
            let order_num = $(this).parent().data('order_num');
            let day = selected_day;
            console.log("order_num:",order_num);
            console.log("day:",day);
            for(let i=0; i<my_travel_list.length; i++){
                console.log("my_travel_list[i].order_num:",my_travel_list[i].order_num);
                console.log(" my_travel_list[i].day:", my_travel_list[i].day);
                if(day == my_travel_list[i].day && order_num == my_travel_list[i].order_num){
                    console.log("삭제동작");
                    my_travel_list.splice(i,1);
                }
            }

            //order_num 순서대로 다시 주기
            order_num = 0;
            for(let i=0; i<my_travel_list.length; i++){
                if(day == my_travel_list[i].day){
                    order_num++;
                    my_travel_list[i].order_num= order_num;
                }

            }

            console.log("my_travel_list:",my_travel_list);

            my_location_reload(day);

        });

    }

    //관광명소 추천 리로드
    function travel_api_reload() {

        let $travel_api_ul = $("#mega_menu ul");

        $travel_api_ul.html("");

        for(let i=0; i<travel_api_list.length; i++){
            let item = travel_api_list[i];
            // console.log("item:",item);
            let html =
                "           <li>\n" +
                "                <div class=\"data_piece\" data-latitude=\""+item.latitude+"\" data-longitude=\""+item.longitude+"\">\n" +
                "                    <div class =\"img_box fl\">\n" +
                "                        <img src=\""+item.image+"\"></img>\n" +
                "                    </div>\n" +
                "                    <div class =\"info_box fl\">\n" +
                "                        <div class=\"title_detail\">"+item.title_detail+"</div>\n" +
                "                        <div class=\"sub\">"+item.sub+"</div>\n" +
                "                    </div>\n" +
                "                    <div class=\"item_add\">\n" +
                "                        <i class=\"fas fa-plus-circle\"></i>\n" +
                "                    </div>\n" +
                "                </div>\n" +
                "            </li>";
            $travel_api_ul.append(html);
        }

        //----- 카카오지도에 마커를 표시 -----
        travel_api_list.forEach(function(item, index){

            // 마커가 표시될 위치입니다
            let markerPosition  = new kakao.maps.LatLng(item.latitude, item.longitude);

            // 마커를 생성합니다
            let marker = new kakao.maps.Marker({
                position: markerPosition,
                title: item.title_detail
            });

            let infowindow_html =
                "           <div class=\"window_info\">\n" +
                "                <div class=\"data_piece\" data-latitude=\""+item.latitude+"\" data-longitude=\""+item.longitude+"\">\n" +
                "                    <div class =\"img_box fl\">\n" +
                "                        <img src=\""+item.image+"\"></img>\n" +
                "                    </div>\n" +
                "                    <div class =\"info_box fl\">\n" +
                "                        <div class=\"title_detail\">"+item.title_detail+"</div>\n" +
                "                        <div class=\"sub\">"+item.sub+"</div>\n" +
                "                    </div>\n" +
                "                    <div class =\"close_box fr\" onclick='window_info_close()'>\n" +
                "                        <i class=\"fas fa-times-circle\"></i>\n" +
                "                    </div>\n" +
                "                    <div style='clear:both; text-align: center;' onclick=''>\n" +
                "                        <button class =\"btn\" onclick=\"window_info_add("+item.latitude+","+item.longitude+",'"+item.image+"','"+item.title_detail+"','"+item.sub+"')\">추가하기</button>\n" +
                "                    </div>\n" +
                "                </div>\n" +
                "            </div>";

            // 마커에 표시할 인포윈도우를 생성합니다
            let infowindow = new kakao.maps.InfoWindow({
                content: infowindow_html
            });

            // for문에서 클로저를 만들어 주지 않으면 마지막 마커에만 이벤트가 등록됩니다
            kakao.maps.event.addListener(marker, 'click', function() {
                if(opened_window_info != null){
                    opened_window_info.close();
                }
                infowindow.open(map, marker);
                opened_window_info = infowindow;
            });

            // 마커가 지도 위에 표시되도록 설정합니다
            marker.setMap(map);
        });
        //----- 카카오지도에 마커를 표시 끝! -----

        //이벤트 초기화
        $("#major_menu .item_remove").off("click");

        //관광명소 추천 (+)클릭
        $("#mega_menu .item_add").on("click", function() {

            let title_detail = $(this).prev().children(".title_detail").html();
            let latitude = $(this).parent().data('latitude');
            let longitude = $(this).parent().data('longitude');
            let sub = $(this).prev().children(".sub").html();
            let img_src = $(this).prevAll(".img_box ").children("img").attr("src");
            let day = $("#top_menu .day_on").data("day");

            let data = {};
            data.title_detail = title_detail;
            data.latitude = latitude;
            data.longitude = longitude;
            data.sub = sub;
            data.image = img_src;
            data.day = day;
            console.log("data:",data);

            //나의 여행장소 리스트에 push
            my_travel_list.push(data);

            //order_num 순서대로 다시 주기
            let order_num = 0;
            for(let i=0; i<my_travel_list.length; i++){
                if(day == my_travel_list[i].day){
                    order_num++;
                    my_travel_list[i].order_num= order_num;
                }

            }

            console.log("my_travel_list:",my_travel_list);

            //나의 여행장소 리로드
            my_location_reload(day);

        });

    }

    //나의 DAY 로드
    function my_day_load(selected_day) {
        let $my_day_ul = $("#top_menu ul");
        $my_day_ul.html("");

        //DAY추가
        for(let i=0; i<<?=$day_count?>; i++){
            let day = i+1;
            let html =
                "            <li class=\"day\" data-day=\""+day+"\">\n" +
                "                <div>DAY"+day+"</div>\n" +
                "            </li>\n";
            $my_day_ul.append(html);
        }

        //DAY가 하나도 없을경우 1개 추가
        let $my_day_li = $("#top_menu ul li");
        if($my_day_li.length < 1){
            let day = 1;
            let html =
                "            <li class=\"day\" data-day=\""+day+"\">\n" +
                "                <div>DAY"+day+"</div>\n" +
                "            </li>\n";
            $my_day_ul.append(html);
        }

        //플러스 버튼 추가
        let plus_html =
            "            <li class=\"day_plus\">\n" +
            "                <i class=\"fas fa-plus-circle\"></i>\n" +
            "            </li>";
        $my_day_ul.append(plus_html);

        //첫번째 DAY는 day_on 클래스 추가시키기
        $("#top_menu ul li").eq(0).addClass("day_on");

    }

</script>


</body>
</html>
