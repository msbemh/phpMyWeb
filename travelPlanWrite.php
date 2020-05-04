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

//POST로 값 전달 받아야함
$travel_plan_no = 1;

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



//foreach ($my_travel_list as $item) {
//    foreach ($item as $key => $value) {
//        echo $key." ".$value."<br>";
//    }
//}

$conn->close();

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
<div class="container_big sign_table">
    <div class="fl" style="display: inline-block;">
        <span>제목 : </span><input id="title_input" class="title" />
        <span>시작날짜 : </span><input id="start_date_input" class="start_date" />
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


<script type="text/javascript">

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

    console.log("my_travel_list:",my_travel_list);

    //관광명소 추천 리로드
    travel_api_reload();

    //나의 여행장소 리로드
    my_location_reload(1);

    //나의 DAY 로드
    my_day_load();

    $(document).on('ready', function(e){
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

        // //관광명소 추천 (+)클릭
        // $("#mega_menu .item_add").on("click", function() {
        //
        //     let title_detail = $(this).prev().children(".title_detail").html();
        //     let latitude = $(this).parent().data('latitude');
        //     let longitude = $(this).parent().data('longitude');
        //     let sub = $(this).prev().children(".sub").html();
        //     let img_src = $(this).prevAll(".img_box ").children("img").attr("src");
        //     let day = $("#top_menu .day_on").data("day");
        //
        //     let data = {};
        //     data.title_detail = title_detail;
        //     data.latitude = latitude;
        //     data.longitude = longitude;
        //     data.sub = sub;
        //     data.image = img_src;
        //     data.day = day;
        //     console.log("data:",data);
        //
        //     //나의 여행장소 리스트에 push
        //     my_travel_list.push(data);
        //
        //     //order_num 순서대로 다시 주기
        //     for(let i=0; i<my_travel_list.length; i++){
        //         let order_num = i+1;
        //         my_travel_list[i].order_num= order_num;
        //     }
        //
        //     console.log("my_travel_list:",my_travel_list);
        //
        //     //나의 여행장소 리로드
        //     my_location_reload(day);
        //
        // });

        //나의 여행장소 (-)클릭
        // $("#major_menu .item_remove").on("click", function() {
        //     let order_num = $(this).parent().data('order_num');
        //     console.log("order_num:",order_num);
        // });

        $("#save").on("click", function() {

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

    //나의 여행장소 리로드 함수
    function my_location_reload(selected_day) {

        let $my_travel_ul = $("#major_menu ul");

        $my_travel_ul.html("");

        for(let i=0; i<my_travel_list.length; i++){
            let item = my_travel_list[i];
            let order_num = i+1;
            // console.log("item:",item);
            if(item.day == selected_day){
                let html =
                    "            <li>\n" +
                    "               <div class=\"data_piece\" data-latitude=\""+item.latitude+"\" data-longitude=\""+item.longitude+"\" data-order_num=\""+order_num+"\">\n" +
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
            }
        }

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
                    my_travel_list.splice(i,1);
                }
            }

            //order_num 순서대로 다시 주기
            for(let i=0; i<my_travel_list.length; i++){
                let order_num = i+1;
                my_travel_list[i].order_num= order_num;
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
            for(let i=0; i<my_travel_list.length; i++){
                let order_num = i+1;
                my_travel_list[i].order_num= order_num;
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

        //플러스 버튼 추가
        let plus_html =
            "            <li class=\"day_plus\">\n" +
            "                <i class=\"fas fa-plus-circle\"></i>\n" +
            "            </li>";
        $my_day_ul.append(plus_html);

        //DAY가 하나도 없을경우 1개 추가
        let $my_day_li = $("#top_menu ul li");
        if($my_day_li.length < 2){
            let day = 1;
            let html =
                "            <li class=\"day\" data-day=\""+day+"\">\n" +
                "                <div>DAY"+day+"</div>\n" +
                "            </li>\n";
            $my_day_ul.append(html);
        }

        //첫번째 DAY는 day_on 클래스 추가시키기
        $("#top_menu ul li").eq(0).addClass("day_on");

    }


</script>

<!-- 구글맵 관련 javascript -->
<script type="text/javascript" src="//dapi.kakao.com/v2/maps/sdk.js?appkey=db020925d06b61dd2f0089235b1f2b3a"></script>

<!-- 구글맵 관련 javascript -->
<script>

    let selectedMarker = null; // 클릭한 마커를 담을 변수
    let opened_window_info = null;

    let mapContainer = document.getElementById('map'), // 지도를 표시할 div
        mapOption = {
            center: new kakao.maps.LatLng(37.541, 126.986), // 지도의 중심좌표
            level: 3 // 지도의 확대 레벨
        };


    let map = new kakao.maps.Map(mapContainer, mapOption); // 지도를 생성합니다

    //지도에 마커를 표시
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
            "                    <div class =\"\" style='clear:both; text-align: center;' onclick=''>\n" +
            "                        <button class =\"btn\" style='background: black; color:white;'>추가하기</button>\n" +
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

    //윈도 인포 닫기
    function window_info_close(){
        if(opened_window_info != null){
            opened_window_info.close();
        }
    }



</script>


</body>
</html>
