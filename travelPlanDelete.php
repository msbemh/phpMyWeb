<meta charset="utf-8" />
<?php
include '../DB/DBConnection.php';

//Post로 받은 데이터 가져오기
$travel_plan_no = $_POST['travel_plan_no'];

$sql = "DELETE FROM travelPlan WHERE travel_plan_no = $travel_plan_no";

if ($conn->query($sql) === TRUE) {
    $sql = "DELETE FROM travelPlanDetail WHERE travel_plan_no = $travel_plan_no";
    if ($conn->query($sql) === TRUE) {
        $sql = "DELETE FROM travelBoardBookmark WHERE travel_plan_no = $travel_plan_no";
        if ($conn->query($sql) === TRUE) {
            $sql = "DELETE FROM travelBoardLikes WHERE travel_plan_no = $travel_plan_no";
            if ($conn->query($sql) === TRUE) {
                echo "<script type='text/javascript'>alert('삭제가 완료됐습니다.');</script>";
            } else {
                echo "<script type='text/javascript'>alert('삭제에 실패했습니다.');</script>";
            }
        } else {
            echo "<script type='text/javascript'>alert('삭제에 실패했습니다.');</script>";
        }
    } else {
        echo "<script type='text/javascript'>alert('삭제에 실패했습니다.');</script>";
    }
} else {
    echo "<script type='text/javascript'>alert('삭제에 실패했습니다.');</script>";
}

$conn->close();

echo("<script>location.replace('/travelPlan.php');</script>");

?>


