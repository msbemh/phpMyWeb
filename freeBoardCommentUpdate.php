<?php

include '../DB/DBConnection.php';

//Post로 받은 데이터 가져오기
$comment_no = $_POST['comment_no'];
$textarea_content = $_POST['textarea_content'];

$sql = "UPDATE freeBoardComment SET content = '$textarea_content', update_date = now() WHERE comment_no = $comment_no";
$conn->query($sql);

$sql = "SELECT content, update_date FROM freeBoardComment WHERE comment_no = $comment_no";
$result = $conn->query($sql);
$row = $result->fetch_assoc();

$datetime = explode(' ', $row['update_date']);
$date = $datetime[0];
$time = $datetime[1];
if($date == Date('Y-m-d'))
    $row['update_date'] = $time;
else
    $row['update_date'] = $date;


echo(json_encode(array("content" => $row["content"],"update_date" => $row["update_date"])));

$conn->close();

?>