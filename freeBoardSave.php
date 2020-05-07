<meta charset="utf-8" />
<?php
include '../DB/DBConnection.php';

//Post로 받은 데이터 가져오기
$title = $_POST['title'];
$textarea = $_POST['textarea'];

session_start();
$session_id = $_SESSION["userId"];

$sql = "INSERT INTO freeBoard (title, writer, content, creationDate, updateDate) VALUES ('$title','$session_id' ,'$textarea',now(),now());";

if ($conn->query($sql) === TRUE) {
    echo "<script type='text/javascript'>alert('글쓰기가 완료됐습니다.');</script>";
} else {
    echo "<script type='text/javascript'>alert('글쓰기에 실패했습니다.');</script>";
}

$conn->close();

echo("<script>location.replace('/freeBoard.php');</script>");

?>


