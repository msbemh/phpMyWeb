<?php
session_start();
//로그인 세션 없을때
if(!isset($_SESSION['userId'])){
    echo("<script>location.href='/index.php';</script>");
//로그인 세션 있을때
}else{

}

$userId = $_SESSION['userId'];

//DB연결
include '../DB/DBConnection.php';

//get방식 data받기
$travel_plan_no =  $_GET["travel_plan_no"];

//조회수 증가시키기
$sql = "UPDATE travelPlan SET views = views + 1 WHERE travel_plan_no = $travel_plan_no";
$result = $conn->query($sql);

//나의 여행리스트 가져오기
$sql = "SELECT * FROM travelPlan A
        INNER JOIN travelPlanDetail B
        ON A.travel_plan_no = B.travel_plan_no
        WHERE A.travel_plan_no = $travel_plan_no";
$result = $conn->query($sql);
$my_travel_list = array();

while($row = $result->fetch_assoc()) {
    $my_travel_list[] = $row;
}

//여행일정 게시판 view내용 가져오기
$sql = "SELECT A.travel_plan_no, A.travel_plan_detail_no, A.order_num, A.title_detail, A.sub, A.latitude, A.longitude, A.discription, A.image, A.day, 
		        B.title, B.writer_email, B.writer_nick_name, B.travel_start_date, B.views, B.creation_date
        FROM travelPlanDetail A
        INNER JOIN travelPlan B
        ON A.travel_plan_no = B.travel_plan_no
        WHERE A.travel_plan_no = $travel_plan_no
        order by A.travel_plan_detail_no asc";
$result = $conn->query($sql);
$row = $result->fetch_assoc();

$title = $row["title"];

$writer_email = $row["writer_email"];
$writer_nick_name = $row["writer_nick_name"];
$travel_start_date = $row["travel_start_date"];
$creation_date = $row["creation_date"];

$datetime = explode(' ', $travel_start_date);
$date = $datetime[0];
$travel_start_date = $date;

//좋아요 총 개수 가져오기
$sql = "SELECT count(*) FROM travelBoardLikes WHERE travel_plan_no = $travel_plan_no";
$result = $conn->query($sql);
$row = $result->fetch_assoc();
$likes = $row["count(*)"];

//내가 좋아요를 선택했는지 안했는지 정보 가져오기
$sql = "SELECT count(*) FROM travelBoardLikes WHERE travel_plan_no = $travel_plan_no AND user_id ='$userId'";
$result = $conn->query($sql);
$row = $result->fetch_assoc();
$my_count = $row["count(*)"];

//북마트 총 개수 가져오기
$sql = "SELECT count(*) FROM travelBoardBookmark WHERE travel_plan_no = $travel_plan_no";
$result = $conn->query($sql);
$row = $result->fetch_assoc();
$total_book_mark = $row["count(*)"];

//내가 좋아요를 선택했는지 안했는지 정보 가져오기
$sql = "SELECT count(*) FROM travelBoardBookmark WHERE travel_plan_no = $travel_plan_no AND user_id ='$userId'";
$result = $conn->query($sql);
$row = $result->fetch_assoc();
$my_book_mark = $row["count(*)"];

//DB해제
$conn->close();
?>

<script type="text/javascript" src="./util.js"></script>
<script type="text/javascript" src="../smartEditor/js/service/HuskyEZCreator.js" charset="utf-8"></script>


<!DOCTYPE html>
<html>
<head>
    <?php include './header.php'?>
</head>
<body>
<?php include './shareModal.php'?>

<!-- 본문 -->
<div class="container" style="min-width:550px; height: 500px;">
    <!-- 상단 부분 -->
    <?php include './topPart.php'?>

    <!-- 글쓰기 부분 -->
    <div class="container_medium2" >
        <form id ="form" method="POST">
            <input type="hidden" id="travel_plan_no" name="travel_plan_no" class="form-control" value="<?php echo $travel_plan_no ?>"/><br>
            <h1 type="text" id="title" name="title" style="border-bottom: 3px solid silver; padding-bottom: 5px; "><?php echo $title?></h1>
            <div style="margin-bottom: 10px; margin-top:0px; font-weight:bold; font-size: 17px;"><?php echo 'No. '.$travel_plan_no ?></div>
            <div id="writer_nick_name" name="writer_nick_name" style="margin-bottom: 10px;"><?php echo 'by '.$writer_nick_name; echo ' ( '.$writer_email.' )' ?></div>
            <div id="$travel_start_date" name="$travel_start_date" style="margin-bottom: 20px; color: silver;"><?php echo '생성일 '.$creation_date; ?></div>
        </form>

        <!-- 여행일정 리스트 보여주기 -->
        <div class="day_list">

            <!-- 게시판 목록 가져오기 -->
            <?php
            include '../DB/DBConnection.php';

            //게시글 시작위치
            $limit = ($pageNum-1)*$list;

            $sql = "select * from travelPlanDetail
                    where travel_plan_no = $travel_plan_no
                    order by day asc, order_num asc";
            $result = $conn->query($sql);

            //day몇개 있는지 count
            $total_day_count = 0;

            $current_bringing_day = 0;

            while($row = $result->fetch_assoc()) {
                $datetime = explode(' ', $row['travel_data']);
                $date = $datetime[0];

                if($current_bringing_day != $row['day']){
                    if($current_bringing_day != 0){?>
                    </div>
                    <?php
                    }
                    $current_bringing_day = $row['day'];
                    $total_day_count ++;
                    ?>
                    <div class="day_item day_item_<?php echo $current_bringing_day?>">
                        <div class="day_item_info">
                            <div class="day_num fl">DAY<?php echo $current_bringing_day?></div>
                            <div class="date_start fl"><?php echo $date?></div>
                        </div>
                <?php
                }
                ?>
                        <div class="travel_item">
                            <div class="order_num"><?php echo $row['order_num']?></div>
                            <img src="<?php echo $row['image']?>">
                            <div class="title_detail"><?php echo $row['title_detail']?></div>
                            <div class="sub"><?php echo $row['sub']?></div>
                            <div class="description"><?php echo $row['discription']?></div>
                        </div>
                <?php
            }
            $conn->close();
            ?>

<!--            <div class="day_item">-->
<!--                <div class="day_item_info">-->
<!--                    <div class="day_num fl">DAY1</div>-->
<!--                    <div class="date_start fl">2015.06.27</div>-->
<!--                </div>-->
<!--                <div class="travel_item">-->
<!--                    <div class="order_num">1</div>-->
<!--                    <img src="http://img.earthtory.com/img/place_img/310/6645_0_et.jpg">-->
<!--                    <div class="title_detail">여행을 떠나자</div>-->
<!--                    <div class="description">안녕하세요</div>-->
<!--                </div>-->
            </div>
        </div>
        <div style="clear: both;"></div>


        <!-- 좋아요, 북마크, 공유 -->
        <div style=" height: 75px; position: relative; border-top: 3px solid silver; border-bottom: 3px solid silver; margin-top:30px; margin-bottom: 30px;">
            <div id="like" style="position: absolute; left:6%; top: 50%; transform: translateY(-50%); cursor:pointer;">
                <i class="far fa-heart" style="font-size: 40px;"></i>
                <span style="font-size: 30px;position: absolute;top: 50%; -ms-transform: translateY(-50%); transform: translateY(-50%);">&nbsp;Like(<span id="like_num" ><?php echo $likes?></span>)</span>
            </div>
            <div id="bookmark" style="position: absolute; left:38%; top: 50%; transform: translate(-50%,-50%); cursor:pointer;">
                <i class="far fa-bookmark" style="font-size: 40px;"></i>
                <span style="font-size: 30px;position: absolute;top: 50%; -ms-transform: translateY(-50%); transform: translateY(-50%);">&nbsp;Bookmark(<span id="total_book_mark" ><?php echo $total_book_mark?></span>)</span>
            </div>
            <div id="share" data-toggle="modal" data-target="#exampleModal" style="position: absolute; right:20%; top: 50%; transform: translateY(-50%); cursor:pointer;">
                <i class="fas fa-share-alt" style="font-size: 40px;"></i>
                <span style="font-size: 30px;position: absolute;top: 50%; -ms-transform: translateY(-50%); transform: translateY(-50%);">&nbsp;Share</span>
            </div>
        </div>

        <!-- 수정, 삭제 버튼 -->
        <?php
        if($writer_email == $_SESSION['userId']){ ?>
            <div style="float:right; margin-top: 10px;">
                <button type="button" id="update" class="btn btn-dark" >수정</button>
                <button type="button" id="delete" class="btn btn-dark">삭제</button>
            </div>
            <div style="clear: both;"></div>
            <?php
        }
        ?>

    </div><!-- container_medium2 끝부분 -->

    <!-- 지도를 표시할 div 입니다 -->
    <div id="map" class="travel_view_map"></div>


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

    //카카오 지도에서 나의 marker list
    let my_marker_list = [];
    //나의 여행리스트
    let my_travel_list = <?= json_encode($my_travel_list) ?>;
    //카카오 지도에 현재 선택된 날짜의 여행리스트 보내주기 위해서 만듦.
    let current_day_travel_list = [];
    //카카오 지도오에서 경로 선 list
    let polyline_list = [];


    $(document).on('ready', function(e){
        $("#update").on("click", function() {
            let travel_plan_no = $("#travel_plan_no").val();
            location.href='/travelPlanWrite.php?travel_plan_no='+travel_plan_no;
        });

        $("#delete").on("click", function() {
            if(confirm("정말 삭제하시겠습니까?") == true){
                let $form = $("#form");
                $form.attr("action", "travelPlanDelete.php");
                $form.trigger("submit");
            }
        });

        //좋아요 클릭
        $("#like").on("click", function() {
            let $svg = $("#like i");
            let $like_num = $("#like_num");
            let like_num_value = $like_num.html()*1;
            console.log("like_num_value:",like_num_value);
            //DB에서 존재하는 아이디인지 검사
            $.ajax({
                type: "POST",
                url : "/travelBoardLike.php",
                data: {"travel_plan_no":<?php echo $travel_plan_no ?>},
                dataType:"json",
                success : function(data, status, xhr) {
                    console.log("data:",data);
                    $svg.toggleClass("fas");
                    $like_num.html(data.count);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(jqXHR.responseText);
                }
            });
        });

        //북마크 클릭
        $("#bookmark").on("click", function() {
            let $bookmark = $("#bookmark i");
            let $total_book_mark = $("#total_book_mark");
            //DB에서 존재하는 아이디인지 검사
            $.ajax({
                type: "POST",
                url : "/travelBoardMark.php",
                data: {"travel_plan_no":<?php echo $travel_plan_no ?>},
                dataType:"json",
                success : function(data, status, xhr) {
                    console.log("data:",data);
                    $bookmark.toggleClass("fas");
                    $total_book_mark.html(data.count);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(jqXHR.responseText);
                }
            });
        });

        //공유 클릭
        $("#share").on("click", function() {
            $("#modal_input_url").val(window.location.href);
        });

        //처음 로딩될때 좋아요 선택or비선택 하게 만들기
        let $svg = $("#like i");
        //이미 좋아요 했다면
        if(<?php echo $my_count?>>0){
            $svg.addClass("fas");
            //아직 좋아요를 누르지 않았다면
        }else{

        }

        //처음 로딩될때 북마크 선택or비선택 하게 만들기
        let $bookmark = $("#bookmark i");
        //이미 북마크 선택했다면
        if(<?php echo $my_book_mark?>>0){
            $bookmark.addClass("fas");
            //아직 북마트 선택하지 않았다면
        }else{

        }

        $('#myModal').on('shown.bs.modal', function () {
            $('#myInput').trigger('focus')
        });



        //DAY위치에 맞는 map 변화시키기
        let current_day_position =1;
        $(window).on( "scroll", function() {
            let count = <?php echo  $total_day_count?>;
            let benchmark = 0;
            //DAY개수만큼 반복
            for(let i=1; i<=count; i++){
                let height = $(".day_item_"+i).height();
                benchmark += height;
                //해당 DAY위치에 왔을때
                if(($(window).scrollTop() < 150+ benchmark) ){
                    //DAY가 변할때만 감지
                    if(current_day_position != i){
                        //map reload시키기
                        let selected_day = i;
                        map_reload(i);

                    }
                    //초기화
                    current_day_position = i;
                    break;
                }
            }
        });

        //처음에 첫번째 날로 맵 리로드
        map_reload(1);

    });

    //현재 DAY에 맞는 리스트 세팅
    function set_current_day_travel_list(selected_day){
        //초기화
        current_day_travel_list.length = 0;

        //현재 DAY에 맞는 리스트 구하기
        for(let i=0; i<my_travel_list.length; i++){
            let item = my_travel_list[i];
            if(item.day == selected_day){
                current_day_travel_list.push(item);
            }
        }
    }

    //맵 리로드
    function map_reload(selected_day){
        //현재 DAY에 맞는 리스트 세팅
        set_current_day_travel_list(selected_day);
        //카카오 나의 여행장소 마커표시
        kakao_show_my_marker(current_day_travel_list);
        //카카오 나의 여행장소 경로표시
        kakao_route(current_day_travel_list);
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


</script>

</body>
</html>

<script type="text/javascript">
    function submitContents(elClickedObj) {
        try {
            let $form = $("#form");
            $form.attr("action", "freeBoardUpdateView.php");
            elClickedObj.form.submit();
        } catch(e) {
            console.log(e);
        }
    }
</script>
