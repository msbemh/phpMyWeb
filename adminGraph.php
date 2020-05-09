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
    <link rel="stylesheet" href="css/graph.css.css" />

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
        <figure class="highcharts-figure">
            <div id="container"></div>
            <p class="highcharts-description">
                Chart showing data loaded dynamically. The individual data points can
                be clicked to display more information.
            </p>
        </figure>
    </div>

</div>



<script type="text/javascript">
    $(document).on('ready', function(e){

    });

    Highcharts.chart('container', {

        title: {
            text: 'Solar Employment Growth by Sector, 2010-2016'
        },

        subtitle: {
            text: 'Source: thesolarfoundation.com'
        },

        yAxis: {
            title: {
                text: 'Number of Employees'
            }
        },

        xAxis: {
            accessibility: {
                rangeDescription: 'Range: 2010 to 2017'
            }
        },

        legend: {
            layout: 'vertical',
            align: 'right',
            verticalAlign: 'middle'
        },

        plotOptions: {
            series: {
                label: {
                    connectorAllowed: false
                },
                pointStart: 2010
            }
        },

        series: [{
            name: 'Installation',
            data: [43934, 52503, 57177, 69658, 97031, 119931, 137133, 154175]
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


</script>



</body>
</html>
