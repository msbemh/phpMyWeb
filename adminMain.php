<?php

include './util.php';

session_start();
//로그인 세션 없을때
if(!isset($_SESSION['userId'])){
    echo("<script>location.href='/index.php';</script>");
//로그인 세션 있을때
}else{

}

//페이징 관련 변수들 지정
$page_num = ($_GET['page']) ? $_GET['page'] : 1; //page : default = 1
$list = ($_GET['list']) ? $_GET['list'] : 10; //page : default = 10


$block_page_num_list = 10; //블럭에 나타낼 페이지 번호 갯수
$block = ceil($page_num/$block_page_num_list); //현재 리스트의 블럭 구하기


$block_start_page = ( ($block - 1) * $block_page_num_list ) + 1; //현재 블럭에서 시작페이지 번호
$block_end_page = $block_start_page + $block_page_num_list - 1; //현재 블럭에서 마지막 페이지 번호

?>
<!DOCTYPE html>
<html>
<head>
    <?php include './header.php'?>
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
                <a class="on" href="/adminMain.php">표</a>
            </li>
            <li>
                <a href="/adminGraph.php">그래프</a>
            </li>
            <li>
                <a href="/adminMain.php">DAY3</a>
            </li>
        </ul>
    </nav>

    <!-- 유저로그 테이블 -->
    <div class="user_log fl">
        <table class="table">
            <colgroup>
                <col width="8%">
                <col width="10%">
                <col width="10%">
                <col width="15%">
                <col width="15%">
                <col width="15%">
                <col width="10%">
            </colgroup>
            <thead>
            <tr>
                <th style="text-align: center;">번호</th>
                <th style="text-align: center;">아이디</th>
                <th style="text-align: center;">IP</th>
                <th style="text-align: center;">국가</th>
                <th style="text-align: center;">이전경로</th>
                <th style="text-align: center;">들어온경로</th>
                <th style="text-align: center;">날짜</th>
            </tr>
            </thead>
            <tbody style="text-align: center;">
            <!-- userLog 목록 가져오기 -->
            <?php
            include '../DB/DBConnection.php';

            //게시글 시작위치
            $limit = ($page_num-1)*$list;

            $sql = "select * from userLog order by idx desc limit $limit,$list";
            $result = $conn->query($sql);
            while($row = $result->fetch_assoc()) {
                $datetime = explode(' ', $row['date']);
                $date = $datetime[0];
                $time = $datetime[1];
                if($date == Date('Y-m-d'))
                    $row['date'] = $time;
                else
                    $row['date'] = $date;
                ?>
                <tr class="freeBoardHover" onclick="goFreeBoardView(<?php echo $row['idx'] ?>)">
                    <td><?php echo $row['idx']?></td>
                    <td><div style="max-height:17px;overflow: hidden"; ><?php echo $row['user_id']?></div></td>
                    <td><div style="max-height:17px;overflow: hidden"; ><?php echo $row['ip']?></div></td>
                    <td><?php echo $row['country']?></td>
                    <td><?php echo $row['previous_url']?></td>
                    <td><?php echo $row['current_url']?></td>
                    <td><?php echo $row['date']?></td>
                </tr>
                <?php
            }
            $conn->close();
            ?>
            </tbody>
        </table>

        <!-- 페이징 처리 -->
        <nav aria-label="Page navigation" style="text-align: center;">
            <ul class="pagination">
                <?php
                include '../DB/DBConnection.php';
                //DB에서 총 데이터 개수 가져오기
                $sql = "SELECT COUNT(*) FROM userLog";
                $result = $conn->query($sql);
                $row = $result->fetch_assoc();
                $total_count = $row["COUNT(*)"];

                $total_page =  ceil($total_count/$list); //총 페이지 수

                if ($block_end_page > $total_page)
                    $block_end_page = $total_page;


                $before = $block_start_page-1;
                //페이징 block단위로 뒤로가기
                if($block != 1){?>
                    <li><a href="/adminMain.php?page=<?=$before?>">이전</a></li>
                    <?php
                }

                //페이징 숫자부분
                for($i = $block_start_page; $i <=$block_end_page; $i++) {
                    if($page_num == $i){?>
                        <li><a style="background: silver;"><?=$i?></a></li>
                        <?php
                    }else{?>
                        <li><a href="/adminMain.php?page=<?=$i?>"><?=$i?></a></li>
                        <?php
                    }
                }


                $next = $block_end_page+1;
                if($next>=$total_page){
                    $next = $total_page;
                }
                $total_block = ceil($total_page/$block_page_num_list);
                //페이징 block단위로 다음으로
                if($block != $total_block){?>
                    <li><a href="/adminMain.php?page=<?=$next?>">다음</a></li>
                    <?php
                }

                $conn->close();
                ?>

            </ul>
        </nav>
    </div>

</div>



<script type="text/javascript">
    $(document).on('ready', function(e){

    });


</script>



</body>
</html>
