<?php

//쿠키가 존재하지 않는다면
if(!isset($_COOKIE['user_log_cookie'])){
    //쿠키생성
    setcookie('user_log_cookie', true, time() + 60*30);

    //user_log정보 insert
    include '../DB/DBConnection.php';

    //Post로 받은 데이터 가져오기
    $user_id = $_SESSION['userId'];
    $ip = $_SERVER["REMOTE_ADDR"];
    $previous_url = $_SERVER['HTTP_REFERER'];

    //현재 url 가져오기
    $http_host = $_SERVER['HTTP_HOST'];
    $request_uri = $_SERVER['REQUEST_URI'];
    $current_url = 'https://' . $http_host . $request_uri;

    //들어온 ip의 국가 가져오기
    $key = "2020050911165570795226";
    $data_format = "json";
    $url = "http://whois.kisa.or.kr/openapi/ipascc.jsp?query=$ip&key=$key&answer=$data_format";

    $ch = curl_init();                                              //curl 초기화
    curl_setopt($ch, CURLOPT_URL, $url);                      //URL 지정하기
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);     //요청 결과를 문자열로 반환
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);       //connection timeout 10초
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);    //원격 서버의 인증서가 유효한지 검사 안함

    $data = curl_exec($ch);

    $decodeJsonData = json_decode($data, true);
    $country = $decodeJsonData["whois"]["countryCode"];
    curl_close($ch);

    $sql = "INSERT INTO userLog (user_id, date, ip, previous_url, country, current_url) 
	        VALUES ('$user_id', now(), '$ip', '$previous_url', '$country', '$current_url')";

    $conn->query($sql);

    $conn->close();
}

?>