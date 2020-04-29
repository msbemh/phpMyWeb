<?php

include '../DB/DBConnection.php';

session_start();
$user_id = $_SESSION['userId'];
$nick_name = $_SESSION['nickName'];

//Post로 받은 데이터 가져오기
$idx = $_POST['idx'];
$content = $_POST['content'];

//댓글 넣기
$sql = "INSERT INTO freeBoardComment (board_no, writer_email, writer_nick_name, content, creation_date, update_date) VALUES ($idx,'$user_id','$nick_name','$content',now(),now())";
$conn->query($sql);
$comment_no = $conn->insert_id;

//방금 넣은 댓글 정보 가져오기
$sql = "SELECT * FROM freeBoardComment WHERE comment_no = $comment_no";
$result = $conn->query($sql);
$row = $result->fetch_assoc();

$comment_no = $row["comment_no"];
$writer_email = $row["writer_email"];
$writer_nick_name = $row["writer_nick_name"];
$content = $row["content"];
$update_date = $row["update_date"];
$update_date = explode(' ', $update_date);
$date = $update_date[0];
$time = $update_date[1];
if($date == Date('Y-m-d'))
    $update_date = $time;
else
    $update_date = $date;



//echo(json_encode(array("count"=>1)));

echo "<div clas=\"container_medium2\" style=\"border: 2px solid silver; margin-bottom: 20px;\">
    <div style=\"padding:10px;\">
        <div style=\"margin-bottom: 10px; color: #41169A; font-size: 15px; font-weight: bold;\">$writer_nick_name ( $writer_email )</div>
        <div style=\"width: 100%; margin-bottom: 10px;\">$content</div>
        <div style=\"color:silver;\">$update_date</div>
    </div>
</div>";

$conn->close();

?>