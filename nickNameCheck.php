<?php

include '../DB/DBConnection.php';

//Post로 받은 데이터 가져오기
$nickName = $_POST['nickName'];

$sql = "SELECT COUNT(*) FROM user WHERE nickName = '$nickName'";
$result = $conn->query($sql);
$row = $result->fetch_assoc();
echo(json_encode(array("result" => $row["COUNT(*)"])));

$conn->close();

?>


