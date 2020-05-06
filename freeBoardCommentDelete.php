<?php

include '../DB/DBConnection.php';

//Post로 받은 데이터 가져오기
$comment_no = $_POST['comment_no'];

$sql = "DELETE FROM freeBoardComment WHERE comment_no = $comment_no;";
$conn->query($sql);

echo(json_encode(array("result" => $conn->affected_rows)));

$conn->close();

?>