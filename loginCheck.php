<?php

    include '../DB/DBConnection.php';

    header("Content-Type: application/json");
    $method = $_SERVER['REQUEST_METHOD'];
    $userId = "";
    $userPassword = "";

    if($method == "GET") {
        //자바스크립트 객체 또는 serialize() 로 전달
//        $userId = $_GET['userId'];
//        $userPassword = $_GET['userPassword'];
//        echo(json_encode(array("userId" => $userId, "userPassword" => $userPassword)));
        //JSON.stirngify() 함수로 전달
        //$data = json_decode($_GET['data']);
        //echo(json_encode(array("mode" => $_REQUEST['mode'], "name" => $data->name, "email" => $data->email)));
    } else if($method == "POST") {
        //자바스크립트 객체 또는 serialize() 로 전달
        $userId = $_POST['userId'];
        $userPassword = $_POST['userPassword'];


        $sql = "SELECT * FROM user WHERE userID = '$userId'";
        $result = $conn->query($sql);
        //해당하는 아이디가 존재할경우
        if ($result->num_rows == 1) {
            //각행 1개씩 꺼내기
            while($row = $result->fetch_assoc()) {
                //로그인 성공(패스워드 일치)
                if(password_verify($userPassword, $row["userPassword"])){
                    //session 설정
                    session_start();
                    $_SESSION['userId'] = $row["userId"];
                    $_SESSION['nickName'] = $row["nickName"];
                    echo(json_encode(array("result" => true)));
                //로그인 실패(패스워드 불일치)
                }else{
                    echo(json_encode(array("result" => false)));
                }
            }
        //해당하는 아이디가 아예 없을 경우
        } else {
            echo(json_encode(array("result" => false)));
        }

        $conn->close();

    }
?>
