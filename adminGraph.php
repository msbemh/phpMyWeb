<?php

include './util.php';

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
            <h3 style="display: inline-block;">연도별, 월별, 일별 선택 : </h3>
            <select id="graph_select" style="display: inline-block;">
                <option value="">그래프 선택</option>
                <option value="year">연도별</option>
                <option value="month">월별</option>
                <option value="day">일별</option>
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
    let categories;
    let data;

    $(document).on('ready', function(e){
        $("#graph_select").on("change",function(){
            let select_value = $("#graph_select").val();
            if(select_value == "year"){

            }else if(select_value == "month"){
                title ="월별 방문자 수";
                subtitle = "";
                categories = ['1월', '2월', '3월', '4월', '5월', '6월', '7월', '8월', '9월', '10월', '11월', '12월'];
                data = [100, 1313, 123, 679, 66, 345, 12, 123];
                //그래프 리로드
                graph_reload(title, subtitle, categories, data);
            }else if(select_value == "day"){

            }
        });

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
