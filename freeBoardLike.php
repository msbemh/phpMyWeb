<?php

include '../DB/DBConnection.php';

session_start();
$userId = $_SESSION['userId'];

//Post로 받은 데이터 가져오기
$idx= $_POST['idx'];

$sql = "SELECT count(*) FROM freeBoardLikes WHERE userID='$userId' AND freeBoardNo = $idx";
$result = $conn->query($sql);
$row = $result->fetch_assoc();
$count = $row["count(*)"];
//이미 좋아요 클릭했을경우
if($count>0){
    //좋아요 뺴기
    $sql = "DELETE FROM freeBoardLikes WHERE userID='$userId' AND freeBoardNo = $idx;";
    $conn->query($sql);

    //해당 게시판의 좋아요 갯수 세기
    $sql = "SELECT count(*) FROM freeBoardLikes WHERE freeBoardNo = $idx";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    $count = $row["count(*)"];
    echo(json_encode(array("count"=>$count)));
//이미 좋아요 클릭하지 않은 경우
}else{
    //좋아요 넣기
    $sql = "INSERT INTO freeBoardLikes (freeBoardNo, userId) VALUES ($idx,'$userId')";
    $conn->query($sql);

    //해당 게시판의 좋아요 갯수 세기
    $sql = "SELECT count(*) FROM freeBoardLikes WHERE freeBoardNo = $idx";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    $count = $row["count(*)"];
    echo(json_encode(array("count"=>$count)));
}

$conn->close();

?>