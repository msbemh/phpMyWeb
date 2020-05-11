<?php

include './util.php';

session_start();
//로그인 세션 없을때
if(!isset($_SESSION['userId'])){
    echo("<script>location.href='/index.php';</script>");
//로그인 세션 있을때
}else{

}

include '../DB/DBConnection.php';

//랜덤 데이터 입력
//for($i=0; $i<100; $i++){
//
//    $random = rand(0,12);
//
//    $sql = "INSERT INTO userLog (user_id, date, ip, previous_url, country, current_url)
//    VALUES ('thdalsehf@naver.com', date_add(now(), interval +$random hour), '125.188.15.156', 'https://wowtravel.tk/index.php', 'KR', 'https://wowtravel.tk/main.php')";
//    $conn->query($sql);
//
//    $sql = "INSERT INTO userLog (user_id, date, ip, previous_url, country, current_url)
//	VALUES ('msbe@naver.com', date_add(now(), interval +$random hour), '125.188.15.156', 'https://wowtravel.tk/index.php', 'KR', 'https://wowtravel.tk/main.php')";
//    $conn->query($sql);
//
//    $sql = "INSERT INTO userLog (user_id, date, ip, previous_url, country, current_url)
//	VALUES ('msbe2@naver.com', date_add(now(), interval +$random hour), '125.188.15.156', 'https://wowtravel.tk/index.php', 'KR', 'https://wowtravel.tk/main.php')";
//    $conn->query($sql);
//
//    $sql = "INSERT INTO userLog (user_id, date, ip, previous_url, country, current_url)
//	VALUES ('msbe3@naver.com', date_add(now(), interval +$random hour), '125.188.15.156', 'https://wowtravel.tk/index.php', 'KR', 'https://wowtravel.tk/main.php')";
//    $conn->query($sql);
//
//}


$conn->close();


?>
<!DOCTYPE html>
<html>
<head>
    <?php include './header.php'?>

<!--    <script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>-->
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/modules/data.js"></script>
    <script src="https://code.highcharts.com/modules/series-label.js"></script>
    <script src="https://code.highcharts.com/modules/exporting.js"></script>
    <script src="https://code.highcharts.com/modules/export-data.js"></script>
    <script src="https://code.highcharts.com/modules/accessibility.js"></script>

    <!-- Additional files for the Highslide popup effect -->
    <script src="https://www.highcharts.com/media/com_demo/js/highslide-full.min.js"></script>
    <script src="https://www.highcharts.com/media/com_demo/js/highslide.config.js" charset="utf-8"></script>
    <link rel="stylesheet" type="text/css" href="https://www.highcharts.com/media/com_demo/css/highslide.css" />
    <link rel="stylesheet" href="css/graph.css" />

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
                <a href="/adminMain.php">표</a>
            </li>
            <li>
                <a class="on" href="/adminGraph.php">그래프</a>
            </li>
            <li>
                <a href="/adminMain.php">DAY3</a>
            </li>
        </ul>
    </nav>

    <!-- 유저로그 차트 -->
    <div class="admin_content_container fl">
        <div>
            <h3 style="display: inline-block;">연도별, 월별, 시간별 선택 : </h3>
            <select id="graph_select" style="display: inline-block;">
                <option value="">그래프 선택</option>
                <option value="year">연도별</option>
                <option value="month">월별</option>
                <option value="hour">시간별</option>
            </select>
        </div>
        <figure class="highcharts-figure" style="max-width: 100%;">
            <div id="container"></div>
        </figure>
    </div>

</div>



<script type="text/javascript">

    let title;
    let subtitle;
    let categories = [];
    let data =[];

    $(document).on('ready', function(e){
        $("#graph_select").on("change",function(){
            let select_value = $("#graph_select").val();
            if(select_value == "year"){
                $.ajax({
                    type: "POST",
                    url : "/userYearlyVisit.php",
                    data: {},
                    dataType:"json",
                    success : function(ajax_data, status, xhr) {
                        //초기화
                        categories = [];
                        data =[];

                        title ="연별 방문자 수";
                        subtitle = "";
                        for(let i=0; i<ajax_data.length; i++){
                            categories[i] = ajax_data[i].year+"년";
                            data[i] = ajax_data[i].count*1;
                        }
                        //그래프 리로드
                        graph_reload(title, subtitle, categories, data);

                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        console.log(jqXHR.responseText);
                    }
                });
            }else if(select_value == "month"){
                $.ajax({
                    type: "POST",
                    url : "/userMonthlyVisit.php",
                    data: {},
                    dataType:"json",
                    success : function(ajax_data, status, xhr) {
                        //초기화
                        categories = [];
                        data =[];

                        title ="월별 방문자 수";
                        subtitle = "올해기준";
                        categories = ['1월', '2월', '3월', '4월', '5월', '6월', '7월', '8월', '9월', '10월', '11월', '12월'];
                        for(let i=0; i<ajax_data.length; i++){
                            data[i] = ajax_data[i].count*1;
                        }
                        //그래프 리로드
                        console.log("data:",data);
                        graph_reload(title, subtitle, categories, data);

                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        console.log(jqXHR.responseText);
                    }
                });

            }else if(select_value == "hour"){
                $.ajax({
                    type: "POST",
                    url : "/userHourlyVisit.php",
                    data: {},
                    dataType:"json",
                    success : function(ajax_data, status, xhr) {
                        //초기화
                        categories = [];
                        data =[];

                        title ="시간별 방문자 수";
                        subtitle = "오늘기준";
                        for(let i=0; i<25; i++){
                            categories[i] = i+"시";
                        }
                        for(let i=0; i<ajax_data.length; i++){
                            data[i] = ajax_data[i].count*1;
                        }
                        //그래프 리로드
                        console.log("data:",data);
                        graph_reload(title, subtitle, categories, data);

                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        console.log(jqXHR.responseText);
                    }
                });
            }
        });

        //처음에 연도별 선택
        $("#graph_select").val('year').trigger('change');

    });


    //그래프 리로드
    function graph_reload(title, subtitle, categories, data){
        Highcharts.chart('container', {

            title: {
                text: title
            },

            subtitle: {
                text: subtitle
            },

            yAxis: {
                title: {
                    text: '방문자 수 [명]'
                }
            },

            xAxis: {
                categories: categories
            },

            legend: {
                layout: 'vertical',
                align: 'right',
                verticalAlign: 'middle'
            },

            plotOptions: {
                line: {
                    dataLabels: {
                        enabled: true
                    },
                    enableMouseTracking: false
                }
            },

            series: [{
                name: '방문자 수',
                data: data
            }],

            responsive: {
                rules: [{
                    condition: {
                        maxWidth: 500
                    },
                    chartOptions: {
                        legend: {
                            layout: 'horizontal',
                            align: 'center',
                            verticalAlign: 'bottom'
                        }
                    }
                }]
            }

        });
    }


</script>



</body>
</html>
