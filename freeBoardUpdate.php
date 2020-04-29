<meta charset="utf-8" />
<?php
include '../DB/DBConnection.php';

//Post로 받은 데이터 가져오기
$idx = $_POST['idx'];
$title = $_POST['title'];
$textarea = $_POST['textarea'];

$sql = "UPDATE freeBoard SET title = '$title', content ='$textarea', updateDate = now() WHERE idx = $idx ";

if ($conn->query($sql) === TRUE) {
    echo "<script type='text/javascript'>alert('수정이 완료됐습니다.');</script>";
} else {
    echo "<script type='text/javascript'>alert('수정에 실패했습니다.');</script>";
}

$conn->close();

echo("<script>location.replace('/freeBoard.php');</script>");

?>


