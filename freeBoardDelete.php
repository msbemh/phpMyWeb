<meta charset="utf-8" />
<?php
include '../DB/DBConnection.php';

//Post로 받은 데이터 가져오기
$idx = $_POST['idx'];

$sql = "DELETE FROM freeBoard WHERE idx = $idx";

if ($conn->query($sql) === TRUE) {
    echo "<script type='text/javascript'>alert('삭제가 완료됐습니다.');</script>";
} else {
    echo "<script type='text/javascript'>alert('삭제에 실패했습니다.');</script>";
}

$conn->close();

echo("<script>location.replace('/freeBoard.php');</script>");

?>


