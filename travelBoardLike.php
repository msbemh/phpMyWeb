<?php

include '../DB/DBConnection.php';

session_start();
$user_id = $_SESSION['userId'];

//Post로 받은 데이터 가져오기
$travel_plan_no = $_POST['travel_plan_no'];

$sql = "SELECT count(*) FROM travelBoardLikes WHERE user_id = '$user_id' AND travel_plan_no = $travel_plan_no";
$result = $conn->query($sql);
$row = $result->fetch_assoc();

$count = $row["count(*)"];
//이미 좋아요 클릭했을경우
if($count>0){
    //좋아요 뺴기
    $sql = "DELETE FROM travelBoardLikes WHERE user_id = '$user_id' AND travel_plan_no = $travel_plan_no";
    $conn->query($sql);

    //해당 게시판의 좋아요 갯수 세기
    $sql = "SELECT count(*) FROM travelBoardLikes WHERE travel_plan_no = $travel_plan_no";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    $count = $row["count(*)"];
    echo(json_encode(array("count"=>$count)));
//이미 좋아요 클릭하지 않은 경우
}else{
    //좋아요 넣기
    $sql = "INSERT INTO travelBoardLikes (travel_plan_no, user_id) VALUES ($travel_plan_no,'$user_id')";
    $conn->query($sql);

    //해당 게시판의 좋아요 갯수 세기
    $sql = "SELECT count(*) FROM travelBoardLikes WHERE travel_plan_no = $travel_plan_no";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    $count = $row["count(*)"];
    echo(json_encode(array("count"=>$count)));
}

$conn->close();

?>