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

    <!-- 상단 부분 -->
    <?php include './topPart.php'?>

    <!-- 메뉴 -->
    <div class="container_medium">
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <a class="navbar-brand" href="/main.php">Home</a>
            <button class="navbar-toggler"  type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item" style="background: #E3E3E3">
                        <a class="nav-link" href="/freeBoard.php">자유게시판</a>
                    </li>
                    <li class="nav-item" style="margin-left:10px;">
                        <a class="nav-link" href="/travelPlan.php">여행일정</a>
                    </li>
                </ul>
            </div>
        </nav>
    </div>

    <div class="container_medium">
        <table class="table">
            <colgroup>
                <col width="7%">
                <col width="">
                <col width="20%">
                <col width="15%">
                <col width="7%">
                <col width="7%">
                <col width="7%">
            </colgroup>
            <thead>
            <tr>
                <th style="text-align: center;">번호</th>
                <th style="text-align: center;">제목</th>
                <th style="text-align: center;">작성자</th>
                <th style="text-align: center;">최근 작성일</th>
                <th style="text-align: center;">조회수</th>
                <th style="text-align: center;">좋아요</th>
                <th style="text-align: center;">북마크</th>
            </tr>
            </thead>
            <tbody style="text-align: center;">
            <!-- 게시판 목록 가져오기 -->
            <?php
            include '../DB/DBConnection.php';

            //게시글 시작위치
            $limit = ($pageNum-1)*$list;

            $sql = "select C.*, ifnull(D.like_num, 0) as like_num, ifnull(E.comment_num, 0) as comment_num, ifnull(F.bookmark_num, 0) as bookmark_num  from(
                        select A.*, B.nickName  from freeBoard A
                        inner join user B
                        on A.writer = B.userId) C
                        
                    left outer join (select freeBoardNo, count(freeBoardNo) as like_num from freeBoardLikes group by freeBoardNo) D
                    on C.idx = D.freeBoardNo
                    
                    left outer join (select board_no, count(board_no) as comment_num from freeBoardComment group by board_no) E
                    on C.idx = E.board_no
                    
                    left outer join (select freeBoardNo, count(freeBoardNo) as bookmark_num from freeBoardBookmark group by freeBoardNo) F
                    on C.idx = F.freeBoardNo
                    order by idx desc limit $limit,$list";
            $result = $conn->query($sql);
            while($row = $result->fetch_assoc()) {
                $datetime = explode(' ', $row['updateDate']);
                $date = $datetime[0];
                $time = $datetime[1];
                $comment_num = $row['comment_num'];
                if($date == Date('Y-m-d'))
                    $row['updateDate'] = $time;
                else
                    $row['updateDate'] = $date;
                ?>
                <tr class="freeBoardHover" onclick="goFreeBoardView(<?php echo $row['idx'] ?>)">
                    <td><?php echo $row['idx']?></td>
                    <td><?php echo $row['title'];
                        if($row['comment_num']>0){
                            echo "<span class='comment_num'>&nbsp;&nbsp;[$comment_num]</span>";
                        }?></td>
                    <td><?php echo $row['nickName'].'<br>( '.$row['writer'].' ) '?></td>
                    <td><?php echo $row['updateDate']?></td>
                    <td><?php echo $row['views']?></td>
                    <td><?php echo $row['like_num']?></td>
                    <td><?php echo $row['bookmark_num']?></td>
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
