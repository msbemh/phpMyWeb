<?php
session_start();
//로그인 세션 없을때
if(!isset($_SESSION['userId'])){
    echo("<script>location.href='/index.php';</script>");
//로그인 세션 있을때
}else{

}

//페이징 관련 변수들 지정
$pageNum = ($_GET['page']) ? $_GET['page'] : 1; //page : default = 1
$list = ($_GET['list']) ? $_GET['list'] : 10; //page : default = 10


$b_pageNum_list = 10; //블럭에 나타낼 페이지 번호 갯수
$block = ceil($pageNum/$b_pageNum_list); //현재 리스트의 블럭 구하기


$b_start_page = ( ($block - 1) * $b_pageNum_list ) + 1; //현재 블럭에서 시작페이지 번호
$b_end_page = $b_start_page + $b_pageNum_list - 1; //현재 블럭에서 마지막 페이지 번호

?>
<!DOCTYPE html>
<html>
<head>
    <?php include './header.php'?>
</head>
<body>
<div class="container" style="min-width:550px; height: 500px;">
    <div style="position:relative; height: 80px">
        <h1 style="float:left;">여행 일정</h1>
        <div style="float:right; margin:20px 0px;">
            <button id="logOut" class="btn" style="background: #ffe8d6; font-weight:bold; font-size: 18px;">로그아웃</button>
        </div>
        <div style="float:right; margin:20px 0px; padding: 6px 12px; font-weight:bold; font-size: 18px;"><?php echo "{$_SESSION["nickName"]}" ?> 님</div>
    </div>
    <div style="clear: both"></div>

    <!-- 메뉴 -->
    <nav class="navbar navbar-default">
        <div class="container-fluid">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="/main.php">Home</a>
            </div>

            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav">
                    <li class="active"><a href="/freeBoard.php">자유게시판</a></li>
                    <li><a href="#">여행일정</a></li>
                </ul>
            </div><!-- /.navbar-collapse -->
        </div><!-- /.container-fluid -->
    </nav>

    <div class="container_medium">
        <table class="table">
            <colgroup>
                <col width="8%">
                <col width="10px">
                <col width="15%">
                <col width="15%">
                <col width="8%">
            </colgroup>
            <thead>
            <tr>
                <th style="text-align: center;">번호</th>
                <th style="text-align: center;">제목</th>
                <th style="text-align: center;">작성자</th>
                <th style="text-align: center;">작성일</th>
                <th style="text-align: center;">조회수</th>
            </tr>
            </thead>
            <tbody style="text-align: center;">
            <!-- 게시판 목록 가져오기 -->
            <?php
            include '../DB/DBConnection.php';

            //게시글 시작위치
            $limit = ($pageNum-1)*$list;

            $sql = "select * from freeBoard order by idx desc limit $limit,$list";
            $result = $conn->query($sql);
            while($row = $result->fetch_assoc()) {
                $datetime = explode(' ', $row['updateDate']);
                $date = $datetime[0];
                $time = $datetime[1];
                if($date == Date('Y-m-d'))
                    $row['updateDate'] = $time;
                else
                    $row['updateDate'] = $date;
                ?>
                <tr class="freeBoardHover" onclick="goFreeBoardView(<?php echo $row['idx'] ?>)">
                    <td><?php echo $row['idx']?></td>
                    <td><div style="max-height:17px;overflow: hidden"; ><?php echo $row['title']?></div></td>
                    <td><div style="max-height:17px;overflow: hidden"; ><?php echo $row['writer']?></div></td>
                    <td><?php echo $row['updateDate']?></td>
                    <td><?php echo $row['views']?></td>
                </tr>
                <?php
            }
            $conn->close();
            ?>
            </tbody>
        </table>

        <!-- 버튼 -->
        <div style="margin-bottom:100px; float:right;">
            <button id="write_btn" class="btn" style="background: #ffe8d6; font-weight:bold; font-size: 18px;">글쓰기</button>
        </div>

        <!-- 페이징 처리 -->
        <nav aria-label="Page navigation" style="text-align: center;">
        <ul class="pagination">
            <?php
            include '../DB/DBConnection.php';
            //DB에서 총 데이터 개수 가져오기
            $sql = "SELECT COUNT(*) FROM freeBoard";
            $result = $conn->query($sql);
            $row = $result->fetch_assoc();
            $total_count = $row["COUNT(*)"];

            $total_page =  ceil($total_count/$list); //총 페이지 수

            if ($b_end_page > $total_page)
                $b_end_page = $total_page;


            $before = $b_start_page-1;
            //페이징 block단위로 뒤로가기
            if($block != 1){?>
                <li><a href="/freeBoard.php?page=<?=$before?>">이전</a></li>
            <?php
            }

            //페이징 숫자부분
            for($i = $b_start_page; $i <=$b_end_page; $i++) {
                if($pageNum == $i){?>
                    <li><a style="background: silver;"><?=$i?></a></li>
                <?php
                }else{?>
                    <li><a href="/freeBoard.php?page=<?=$i?>"><?=$i?></a></li>
                <?php
                }
            }


            $next = $b_end_page+1;
            if($next>=$total_page){
                $next = $total_page;
            }
            $total_block = ceil($total_page/$b_pageNum_list);
            //페이징 block단위로 다음으로
            if($block != $total_block){?>
                <li><a href="/freeBoard.php?page=<?=$next?>">다음</a></li>
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
        $("#logOut").on("click", function() {
            location.href = "/logOut.php";
        });

        $("#write_btn").on("click", function() {
            location.href = "/freeBoardWrite.php";
        });
    });
    function goFreeBoardView(idx) {
        console.log("idx:",idx);
        location.href='/freeBoardView.php?idx='+idx;
    }
    function goHome() {
        location.href='/main.php';
    }
    function goFreeBoard() {
        location.href='/freeBoard.php';
    }
    function goHome() {
        location.href='/main.php';
    }
    function goHome() {
        location.href='/main.php';
    }

</script>



</body>
</html>
