<?php

include '../DB/DBConnection.php';

//Post로 받은 데이터 가져오기
$title = $_POST['title'];
$start_date = $_POST['start_date'];
$my_travel_list = $_POST['my_travel_list'];
$travel_plan_no = $_POST['travel_plan_no'];

$first_image = $my_travel_list[0]["image"];
for ($i =0; $i < count($my_travel_list); $i++) {
    if($my_travel_list[$i]["day"] == 1 && $my_travel_list[$i]["order_num"] == 1){
        $first_image = $my_travel_list[$i]["image"];
    }
}

//세션정보 가져오기
session_start();
$session_id = $_SESSION["userId"];
$session_nick_name = $_SESSION["nickName"];


/* 이미 저장된 여행일저 존재하는지 count하기 */
$sql = "SELECT count(*) as cnt FROM travelPlan
        WHERE travel_plan_no = $travel_plan_no";
$result = $conn->query($sql);
$row = $result->fetch_assoc();

/* 이미 저장된 여행일정이 존재한다면 */
if($row["cnt"]>0){
    //travelPlan 테이블 수정
    $sql = "UPDATE travelPlan SET title = '$title', update_date = now(), travel_start_date = '$start_date', thumnail_image = '$first_image' WHERE travel_plan_no = $travel_plan_no";
    $result = $conn->query($sql);

    //travelPlanDetail 삭제
    $sql = "DELETE FROM travelPlanDetail WHERE travel_plan_no = $travel_plan_no";
    $result = $conn->query($sql);
/* 처음 여행일정을 작성한거라면 */
}else{
    $sql = "INSERT INTO travelPlan (title, writer_email, writer_nick_name, thumnail_image, travel_start_date, creation_date, update_date) 
	        VALUES ('$title', '$session_id', '$session_nick_name', '$first_image', '$start_date', now(), now())";
    $result = $conn->query($sql);
    //방금전 넣은 travel_plan_no 가져오기
    $travel_plan_no = $conn->insert_id;
}

//travelPlanDetail 데이터 넣기
for ($i =0; $i < count($my_travel_list); $i++) {
    $order_num = $my_travel_list[$i]["order_num"];
    $title_detail = $my_travel_list[$i]["title_detail"];
    $sub = $my_travel_list[$i]["sub"];
    $latitude = $my_travel_list[$i]["latitude"];
    $longitude = $my_travel_list[$i]["longitude"];
    $discription = $my_travel_list[$i]["discription"];
    $image = $my_travel_list[$i]["image"];
    $day = $my_travel_list[$i]["day"];
    $day_plus = $day-1;
//    echo(json_encode(array("travel_plan_no"=>$travel_plan_no
//                            ,"order_num"=>$order_num
//                            ,"title_detail"=>$title_detail)));

    $sql = "INSERT INTO travelPlanDetail (travel_plan_no, order_num, travel_data, title_detail, sub, latitude, longitude, discription, image, day) 
	        VALUES ($travel_plan_no, $order_num, date_add('$start_date',INTERVAL $day_plus DAY) , '$title_detail', '$sub', $latitude, $longitude, '$discription','$image',$day)";
    $result = $conn->query($sql);
}


echo(json_encode(array("result" => true, "travel_plan_no"=>$travel_plan_no)));


$conn->close();

?>