<meta charset="utf-8" />
<?php

    include '../DB/DBConnection.php';

    //Post로 받은 데이터 가져오기
    $userId = $_POST['userId'];
    $nickName = $_POST['nickName'];
    $userPassword = password_hash($_POST['userPassword'], PASSWORD_DEFAULT);

    $sql = "INSERT INTO user (userId, nickName, userPassword, createDate, loginDate) VALUES ('$userId','$nickName' ,'$userPassword',now(),now());";

    if ($conn->query($sql) === TRUE) {
//        echo "New record created successfully";
        echo "<script type='text/javascript'>alert('회원가입이 완료됐습니다.');</script>";
    } else {
//        echo "Error: " . $sql . "<br>" . $conn->error;
        echo "<script type='text/javascript'>alert('회원가입에 실패했습니다.');</script>";
    }

    $conn->close();

    echo("<script>location.replace('/index.php');</script>");

?>


