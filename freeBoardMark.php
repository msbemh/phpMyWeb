<?php

include '../DB/DBConnection.php';

session_start();
$userId = $_SESSION['userId'];

//Post로 받은 데이터 가져오기
$idx= $_POST['idx'];

//사용자가 북마크를 했는지 안했는지 판별
$sql = "SELECT count(*) FROM freeBoardBookmark WHERE userID='$userId' AND freeBoardNo = $idx";
$result = $conn->query($sql);
$row = $result->fetch_assoc();
$count = $row["count(*)"];
//이미 북마크 클릭했을경우
if($count>0){
    //북마크 뺴기
    $sql = "DELETE FROM freeBoardBookmark WHERE userID='$userId' AND freeBoardNo = $idx;";
    $conn->query($sql);

    //해당 게시판의 북마크 갯수 세기
    $sql = "SELECT count(*) FROM freeBoardBookmark WHERE freeBoardNo = $idx";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    $count = $row["count(*)"];
    echo(json_encode(array("count"=>$count)));
//이미 북마크 클릭하지 않은 경우
}else{
    //북마크 넣기
    $sql = "INSERT INTO freeBoardBookmark (freeBoardNo, userId) VALUES ($idx,'$userId')";
    $conn->query($sql);

    //해당 게시판의 북마크 갯수 세기
    $sql = "SELECT count(*) FROM freeBoardBookmark WHERE freeBoardNo = $idx";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    $count = $row["count(*)"];
    echo(json_encode(array("count"=>$count)));
}

$conn->close();

?>